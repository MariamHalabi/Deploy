<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;

require __DIR__."/modeles/Client.php";
require __DIR__."/modeles/Produit.php";

require __DIR__ . '/vendor/autoload.php';

const JWT_SECRET = "makey1234567";

$app = AppFactory::create();
$app->addBodyParsingMiddleware();


$app->post('/api/login', function (Request $request, Response $response) {
   /* $body = (array)$request->getParsedBody();
    $login = $body['login'];
    $password = $body['password'];

    $jwt = createJWT($login, $password);
    return $response->withHeader("Authorization", "Bearer $jwt");*/

    $err=false;
    $inputJSON = file_get_contents('php://input');
    $body = json_decode( $inputJSON, TRUE );
    $login = $body['login'] ?? "";
    $password = $body['password'] ?? "";

    //check format login and password
    if (empty($login) || empty($password)|| !preg_match("/^[a-zA-Z0-9]+$/", $login) || !preg_match("/^[a-zA-Z0-9]+$/", $password)) {
        $err=true;
    }

    // global $entityManager;
    // $user = $entityManager->getRepository('Client')->findOneBy(array('login' => $login, 'password' => $password));
    // $id = $user->getId();

    if (!$err && $user) {
        $response = createJwT($response, $login, $password);
        $response = addHeaders($response);
        $data = array('login' => $login, 'id' => $id);
        $response->getBody()->write(json_encode($data));
    }
    else{
        $response = $response->withStatus(401);
    }
    return $response;
});

function createJWT($login, $password) : string
{
    $issuedAt = time();
    $expirationTime = $issuedAt + 600;
    $payload = array(
        'login' => $login,
        'password' => $password,
        'iat' => $issuedAt,
        'exp' => $expirationTime
    );

    return JWT::encode($payload, JWT_SECRET);
}
function  addHeaders (Response $response) : Response {
    $response = $response
    ->withHeader("Content-Type", "application/json")
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'Content-Type,  Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
    ->withHeader('Access-Control-Expose-Headers', 'Authorization');

    return $response;
}

#PRODUIT

$app->get('/api/catalogue', function (Request $request, Response $response) {
    global $entityManager;
    $products = $entityManager->getRepository('Produit')->findAll();
    $response = addHeaders($response);
    $response->getBody()->write(json_encode ($products));
    return $response;
});

/*function getProductsJSON() {
    return file_get_contents(__DIR__ . '\mock\catalogueMock.json');
}
*/
$app->get('/api/product/{id}', function (Request $request, Response $response, $args) {
   /* $id = $args ['id'];
    $json = getProductsJSON();
    $products = json_decode($json, true);

    $product = filterArrayById($products, $id);
    $response->getBody()->write(json_encode($product));
    return $response;*/

 global $entityManager;
    $product = $entityManager->getRepository('Produit')->findOneBy(array('id' => $args['id']));
    $response = addHeaders($response);
    $response->getBody()->write(json_encode ($product));
    return $response;
});

function filterArrayById($array, $id)
{
    $filtered_array = array_filter($array, function ($elem) use ($id) {
        if (isset($elem['id'])) {
            return $elem['id'] == $id;
        }
        return false;
    });
    return current($filtered_array);
}

$app->post('/api/inscription', function (Request $request, Response $response) {
     $inputJSON = file_get_contents('php://input');
    $body = json_decode( $inputJSON, TRUE );

    $login = $body ['login'] ?? "";
    $password = $body ['password'] ?? "";
        $firstName = $body ['firstname'] ?? "";
    $lastName = $body ['lastname'] ?? "";
    $email = $body ['email'] ?? "";
    $phone = $body ['phone'] ?? "";
    $address = $body ['address'] ?? "";
    $city = $body ['city'] ?? "";
    $codecity = $body ['cp'] ?? "";
    $country = $body ['country'] ?? "";
    $civility = $body ['civility'] ?? "";
    $err=false;


    if (!$err) {
        global $entityManager;
        $client = new Client;
        $client->setLastname($lastName);
        $client->setFirstname($firstName);
        $client->setEmail($email);
        $client->setPhone($phone);
        $client->setAddress($address);
        $client->setCity($city);
        $client->setCP($codecity);
        $client->setCountry($country);
        $client->setLogin($login);
        $client->setPassword($password);
        $client->setCivility($civility);
        $entityManager->persist($client);
        $entityManager->flush();
        $response = addHeaders($response);
        $response->getBody()->write(json_encode ($client));
    }
    else{
        $response = $response->withStatus(401);
        $response->getBody()->write(json_encode ($err));

    }
    return $response;
});


$app->post('/api/catalogue', function (Request $request, Response $response, $args) {
    $inputJSON = file_get_contents('php://input');
    $body = json_decode( $inputJSON, TRUE ); //convert JSON into array
    $id = $body ['id'] ?? "";
    $name = $body ['name'] ?? "";
    $description = $body ['description'] ?? "";
     $price = $body ['price'] ?? "";
    $image = $body ['image'] ?? "";
    $category = $body ['summary'] ?? "";
    $err=false;

    //check format name, price, description and image
    if (empty($name) || empty($price) || empty($description) || empty($image) ||
    !preg_match("/^[a-zA-Z0-9]+$/", $name)  || !preg_match("/^[a-zA-Z0-9]+$/", $description)
    ) {
        $err=true;
    }

    if (!$err) {
        global $entityManager;
        $product = new Produit;
        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription($description);
        $product->setImage($image);
        $product->setSummary($summary);
        $entityManager->persist($product);
        $entityManager->flush();
        $response = addHeaders($response);
        $response->getBody()->write(json_encode ($product));
    }
    else{
        $response = $response->withStatus(401);
    }
    return $response;
});

function createClientFromBody($body): Client {
    $client = new Client();
    $client->firstname = $body['firstname'];
    $client->lastname = $body['lastname'];
    $client->email = $body['email'];
    $client->login = $body['login'];
    $client->password = $body['password'];
    $client->phone = $body['phone'];
    $client->locale = $body['locale'];
    $client->address = $body['address'];
    $client->city = $body['city'];
    $client->zip = $body['cp'];
    $client->country = $body['country'];
    $client->civility = $body['civility'];
    return $client;
}

$app->put('/api/product/{id}', function (Request $request, Response $response, $args) {
    $inputJSON = file_get_contents('php://input');
    $body = json_decode( $inputJSON, TRUE ); //convert JSON into array
    $name = $body ['name'] ?? "";
    $price = $body ['price'] ?? "";
    $description = $body ['description'] ?? "";
    $image = $body ['image'] ?? "";
    $category = $body ['summary'] ?? "";
    $err=false;

    //check format name, price, description and image
    if (empty($name) || empty($price) || empty($description) || empty($image) ||
    !preg_match("/^[a-zA-Z0-9]+$/", $name)  || !preg_match("/^[a-zA-Z0-9]+$/", $description)
    ) {
        $err=true;
    }

    if (!$err) {
        $id = $args ['id'];
        global $entityManager;
        $product = $entityManager->getRepository('Produit')->findOneBy(array('id' => $id));
        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription($description);
        $product->setImage($image);
        $product->setSummary($summary);
        $entityManager->persist($product);
        $entityManager->flush();
        $response = addHeaders($response);
        $response->getBody()->write(json_encode ($product));
    }
    else{
        $response = $response->withStatus(401);
    }
    return $response;
});

$options = [
    "attribute" => "token",
    "header" => "Authorization",
    "regexp" => "/Bearer\s+(.*)$/i",
    "secure" => false,
    "algorithm" => ["HS256"],
    "secret" => JWT_SECRET,
    "path" => ["/api"],
    "ignore" => ["/api/login", "/api/inscription", "/api/catalogue"],
    "error" => function ($response) {
        $data = array('ERREUR' => 'Connexion', 'ERREUR' => 'JWT Non valide');
        $response = $response->withStatus(401);
        return $response->withHeader("Content-Type", "application/json")->getBody()->write(json_encode($data));
    }
];



$app->add(new Tuupola\Middleware\JwtAuthentication($options));
$app->add(new Tuupola\Middleware\CorsMiddleware([
    "origin" => ["*"],
    "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
    "headers.allow" => ["Authorization", "Content-Type"],
    "headers.expose" => ["Authorization"],
    "headers.origin" => ["*"],
]));

$app->run();