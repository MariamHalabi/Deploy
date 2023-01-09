<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
date_default_timezone_set('America/Lima');
require_once "vendor/autoload.php";
$isDevMode = true;
$config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/config/yaml"), $isDevMode);
$conn = array(
'host' => 'host',
'driver' => 'pdo_pgsql',
'user' => '',
'password' => '12345678',
'dbname' => 'OnlineShop',
'port' => '5432'
);
$entityManager = EntityManager::create($conn, $config);
