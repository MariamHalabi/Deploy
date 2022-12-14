<?php declare(strict_types=1);

namespace Neomerx\Cors;

/**
 * Copyright 2015-2019 info@neomerx.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use Neomerx\Cors\Contracts\AnalysisResultInterface;
use Neomerx\Cors\Contracts\AnalysisStrategyInterface;
use Neomerx\Cors\Contracts\AnalyzerInterface;
use Neomerx\Cors\Contracts\Constants\CorsRequestHeaders;
use Neomerx\Cors\Contracts\Constants\CorsResponseHeaders;
use Neomerx\Cors\Contracts\Constants\SimpleRequestHeaders;
use Neomerx\Cors\Contracts\Constants\SimpleRequestMethods;
use Neomerx\Cors\Contracts\Factory\FactoryInterface;
use Neomerx\Cors\Log\LoggerAwareTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

/**
 * @package Neomerx\Cors
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Analyzer implements AnalyzerInterface
{
    use LoggerAwareTrait {
        LoggerAwareTrait::setLogger as psrSetLogger;
    }

    /** HTTP method for pre-flight request */
    const PRE_FLIGHT_METHOD = 'OPTIONS';

    /**
     * @var array
     */
    private const SIMPLE_METHODS = [
        SimpleRequestMethods::GET  => true,
        SimpleRequestMethods::HEAD => true,
        SimpleRequestMethods::POST => true,
    ];

    /**
     * @var string[]
     */
    private const SIMPLE_LC_HEADERS_EXCLUDING_CONTENT_TYPE = [
        SimpleRequestHeaders::LC_ACCEPT,
        SimpleRequestHeaders::LC_ACCEPT_LANGUAGE,
        SimpleRequestHeaders::LC_CONTENT_LANGUAGE,
    ];

    /**
     * @var AnalysisStrategyInterface
     */
    private $strategy;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @param AnalysisStrategyInterface $strategy
     * @param FactoryInterface          $factory
     */
    public function __construct(AnalysisStrategyInterface $strategy, FactoryInterface $factory)
    {
        $this->factory  = $factory;
        $this->strategy = $strategy;
    }

    /**
     * Create analyzer instance.
     *
     * @param AnalysisStrategyInterface $strategy
     *
     * @return AnalyzerInterface
     */
    public static function instance(AnalysisStrategyInterface $strategy): AnalyzerInterface
    {
        return static::getFactory()->createAnalyzer($strategy);
    }

    /**
     * @inheritdoc
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->psrSetLogger($logger);
        $this->strategy->setLogger($logger);
    }

    /**
     * @inheritdoc
     *
     * @see http://www.w3.org/TR/cors/#resource-processing-model
     */
    public function analyze(RequestInterface $request): AnalysisResultInterface
    {
        $this->logDebug('CORS analysis for request started.');

        $result = $this->analyzeImplementation($request);

        $this->logDebug('CORS analysis for request completed.');

        return $result;
    }

    /**
     * @param RequestInterface $request
     *
     * @return AnalysisResultInterface
     */
    protected function analyzeImplementation(RequestInterface $request): AnalysisResultInterface
    {
        // check 'Host' request
        if ($this->strategy->isCheckHost() === true && $this->checkIsSameHost($request) === false) {
            return $this->createResult(AnalysisResultInterface::ERR_NO_HOST_HEADER);
        }

        // Request handlers have common part (#6.1.1 - #6.1.2 and #6.2.1 - #6.2.2)

        // #6.1.1 and #6.2.1
        if (empty($requestOrigin = $this->getOriginHeader($request)) === true) {
            $this->logInfo('Request is not CORS (request origin is empty).');
            return $this->createResult(AnalysisResultInterface::TYPE_REQUEST_OUT_OF_CORS_SCOPE);
        }
        if ($this->checkIsCrossOrigin($requestOrigin) === false) {
            return $this->createResult(AnalysisResultInterface::TYPE_REQUEST_OUT_OF_CORS_SCOPE);
        }

        // #6.1.2 and #6.2.2
        if ($this->strategy->isRequestOriginAllowed($requestOrigin) === false) {
            $this->logInfo(
                'Request origin is not allowed. Check config settings for Allowed Origins.',
                ['origin' => $requestOrigin]
            );
            return $this->createResult(AnalysisResultInterface::ERR_ORIGIN_NOT_ALLOWED);
        }

        // Since this point handlers have their own path for
        // - simple CORS and actual CORS request (#6.1.3 - #6.1.4)
        // - pre-flight request (#6.2.3 - #6.2.10)

        if ($request->getMethod() === self::PRE_FLIGHT_METHOD) {
            return $this->analyzeAsPreFlight($request, $requestOrigin);
        }

        return $this->analyzeAsRequest($request, $requestOrigin);
    }

    /**
     * Analyze request as simple CORS or/and actual CORS request (#6.1.3 - #6.1.4).
     *
     * @param RequestInterface $request
     * @param string           $requestOrigin
     *
     * @return AnalysisResultInterface
     */
    protected function analyzeAsRequest(RequestInterface $request, string $requestOrigin): AnalysisResultInterface
    {
        $this->logDebug('Request is identified as an actual CORS request.');

        $headers = [];

        // #6.1.3
        $headers[CorsResponseHeaders::ALLOW_ORIGIN] = $requestOrigin;
        if ($this->strategy->isRequestCredentialsSupported($request) === true) {
            $headers[CorsResponseHeaders::ALLOW_CREDENTIALS] = CorsResponseHeaders::VALUE_ALLOW_CREDENTIALS_TRUE;
        }
        // #6.4
        $headers[CorsResponseHeaders::VARY] = CorsRequestHeaders::ORIGIN;

        // #6.1.4
        $exposedHeaders = $this->strategy->getResponseExposedHeaders($request);
        if (empty($exposedHeaders) === false) {
            $headers[CorsResponseHeaders::EXPOSE_HEADERS] = $exposedHeaders;
        }

        return $this->createResult(AnalysisResultInterface::TYPE_ACTUAL_REQUEST, $headers);
    }

    /**
     * Analyze request as CORS pre-flight request (#6.2.3 - #6.2.10).
     *
     * @param RequestInterface $request
     * @param string           $requestOrigin
     *
     * @return AnalysisResultInterface
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function analyzeAsPreFlight(RequestInterface $request, string $requestOrigin): AnalysisResultInterface
    {
        // #6.2.3
        $requestMethod = $request->getHeader(CorsRequestHeaders::METHOD);
        if (empty($requestMethod) === true) {
            $this->logDebug('Request is not CORS (header ' . CorsRequestHeaders::METHOD . ' is not specified).');

            return $this->createResult(AnalysisResultInterface::TYPE_REQUEST_OUT_OF_CORS_SCOPE);
        }
        $requestMethod = \reset($requestMethod);

        // OK now we are sure it's a pre-flight request
        $this->logDebug('Request is identified as a pre-flight CORS request.');

        /** @var string $requestMethod */

        // #6.2.4
        $lcRequestHeaders = $this->getRequestedHeadersInLowerCase($request);

        // #6.2.5
        if ($this->strategy->isRequestMethodSupported($requestMethod) === false) {
            $this->logInfo(
                'Request method is not supported. Check config settings for Allowed Methods.',
                ['method' => $requestMethod]
            );
            return $this->createResult(AnalysisResultInterface::ERR_METHOD_NOT_SUPPORTED);
        }

        // #6.2.6
        if ($this->strategy->isRequestAllHeadersSupported($lcRequestHeaders) === false) {
            return $this->createResult(AnalysisResultInterface::ERR_HEADERS_NOT_SUPPORTED);
        }

        // pre-flight response headers
        $headers = $this->createPreFlightResponseHeaders($request, $requestOrigin, $requestMethod, $lcRequestHeaders);

        return $this->createResult(AnalysisResultInterface::TYPE_PRE_FLIGHT_REQUEST, $headers);
    }

    /**
     * @param RequestInterface $request
     * @param string           $requestOrigin
     * @param string           $requestMethod
     * @param array            $lcRequestHeaders
     *
     * @return array
     */
    protected function createPreFlightResponseHeaders(
        RequestInterface $request,
        string $requestOrigin,
        string $requestMethod,
        array  $lcRequestHeaders
    ): array {
        $headers = [];

        // #6.2.7
        $headers[CorsResponseHeaders::ALLOW_ORIGIN] = $requestOrigin;
        if ($this->strategy->isRequestCredentialsSupported($request) === true) {
            $headers[CorsResponseHeaders::ALLOW_CREDENTIALS] = CorsResponseHeaders::VALUE_ALLOW_CREDENTIALS_TRUE;
        }
        // #6.4
        $headers[CorsResponseHeaders::VARY] = CorsRequestHeaders::ORIGIN;

        // #6.2.8
        if ($this->strategy->isPreFlightCanBeCached($request) === true) {
            $headers[CorsResponseHeaders::MAX_AGE] = $this->strategy->getPreFlightCacheMaxAge($request);
        }

        // #6.2.9
        $isSimpleMethod = isset(static::SIMPLE_METHODS[$requestMethod]);
        if ($isSimpleMethod === false || $this->strategy->isForceAddAllowedMethodsToPreFlightResponse() === true) {
            $headers[CorsResponseHeaders::ALLOW_METHODS] = $this->strategy->getRequestAllowedMethods($request);
        }

        // #6.2.10
        // Has only 'simple' headers excluding Content-Type
        $isSimpleExclCT = empty(\array_diff($lcRequestHeaders, static::SIMPLE_LC_HEADERS_EXCLUDING_CONTENT_TYPE));
        if ($isSimpleExclCT === false || $this->strategy->isForceAddAllowedHeadersToPreFlightResponse() === true) {
            $headers[CorsResponseHeaders::ALLOW_HEADERS] = $this->strategy->getRequestAllowedHeaders($request);
        }

        return $headers;
    }

    /**
     * @param RequestInterface $request
     *
     * @return string[]
     */
    protected function getRequestedHeadersInLowerCase(RequestInterface $request): array
    {
        $requestHeaders = [];

        foreach ($request->getHeader(CorsRequestHeaders::HEADERS) as $headersList) {
            $headersList = \strtolower($headersList);
            foreach (\explode(CorsRequestHeaders::HEADERS_SEPARATOR, $headersList) as $header) {
                // after explode header names might have spaces in the beginnings and ends so trim them
                $header = \trim($header);
                if (empty($header) === false) {
                    $requestHeaders[] = $header;
                }
            }
        }

        return $requestHeaders;
    }

    /**
     * @param RequestInterface $request
     *
     * @return string
     */
    protected function getOriginHeader(RequestInterface $request): string
    {
        if ($request->hasHeader(CorsRequestHeaders::ORIGIN) === true) {
            $header = $request->getHeader(CorsRequestHeaders::ORIGIN);
            if (empty($header) === false) {
                return \reset($header);
            }
        }

        return '';
    }

    /**
     * @param RequestInterface $request
     *
     * @return bool
     */
    protected function checkIsSameHost(RequestInterface $request): bool
    {
        $serverOriginHost = $this->strategy->getServerOriginHost();
        $serverOriginPort = $this->strategy->getServerOriginPort();

        $host = $this->getRequestHostHeader($request);

        // parse `Host` header
        //
        // According to https://tools.ietf.org/html/rfc7230#section-5.4 `Host` header could be
        //
        //                     "uri-host" OR "uri-host:port"
        //
        // `parse_url` function thinks the first value is `path` and the second is `host` with `port`
        // which is a bit annoying so...
        $portOrNull = \parse_url($host, PHP_URL_PORT);
        $hostUrl    = $portOrNull === null ? $host : \parse_url($host, PHP_URL_HOST);

        // Neither MDN, nor RFC tell anything definitive about Host header comparison.
        // Browsers such as Firefox and Chrome do not add the optional port for
        // HTTP (80) and HTTPS (443).
        // So we require port match only if it specified in settings.

        $isHostUrlMatch = \strcasecmp($serverOriginHost, $hostUrl) === 0;
        $isSameHost     =
            $isHostUrlMatch === true &&
            ($serverOriginPort === null || $serverOriginPort === $portOrNull);

        if ($isSameHost === false) {
            $this->logInfo(
                'Host header in request either absent or do not match server origin. ' .
                'Check config settings for Server Origin and Host Check.',
                ['host' => $host, 'server_origin_host' => $serverOriginHost, 'server_origin_port' => $serverOriginPort]
            );
        }

        return $isSameHost;
    }

    /**
     * @param string $requestOrigin
     *
     * @return bool
     *
     * @see http://tools.ietf.org/html/rfc6454#section-5
     */
    protected function checkIsCrossOrigin(string $requestOrigin): bool
    {
        $parsedUrl = \parse_url($requestOrigin);
        if ($parsedUrl === false) {
            $this->logWarning('Request origin header URL cannot be parsed.', ['url' => $requestOrigin]);

            return false;
        }

        // check `host` parts
        $requestOriginHost = $parsedUrl['host'] ?? '';
        $serverOriginHost  = $this->strategy->getServerOriginHost();
        if (\strcasecmp($requestOriginHost, $serverOriginHost) !== 0) {
            return true;
        }

        // check `port` parts
        $requestOriginPort = \array_key_exists('port', $parsedUrl) === true ? (int)$parsedUrl['port'] : null;
        $serverOriginPort  = $this->strategy->getServerOriginPort();
        if ($requestOriginPort !== $serverOriginPort) {
            return true;
        }

        // check `scheme` parts
        $requestOriginScheme = $parsedUrl['scheme'] ?? '';
        $serverOriginScheme  = $this->strategy->getServerOriginScheme();
        if (\strcasecmp($requestOriginScheme, $serverOriginScheme) !== 0) {
            return true;
        }

        $this->logInfo(
            'Request is not CORS (request origin equals to server one).',
            [
                'request_origin'       => $requestOrigin,
                'server_origin_scheme' => $serverOriginScheme,
                'server_origin_host'   => $serverOriginHost,
                'server_origin_port'   => $serverOriginPort
            ]
        );

        return false;
    }

    /**
     * @param int   $type
     * @param array $headers
     *
     * @return AnalysisResultInterface
     */
    protected function createResult($type, array $headers = []): AnalysisResultInterface
    {
        return $this->factory->createAnalysisResult($type, $headers);
    }

    /**
     * @return FactoryInterface
     */
    protected static function getFactory(): FactoryInterface
    {
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        return new \Neomerx\Cors\Factory\Factory();
    }

    /**
     * @param RequestInterface $request
     *
     * @return null|string
     */
    private function getRequestHostHeader(RequestInterface $request): ?string
    {
        $hostHeaderValue = $request->getHeader(CorsRequestHeaders::HOST);
        $host            = empty($hostHeaderValue) === true ? null : \reset($hostHeaderValue);

        return $host;
    }
}
