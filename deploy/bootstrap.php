<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
date_default_timezone_set('America/Lima');
require_once "vendor/autoload.php";
$isDevMode = true;
$config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/config/yaml"), $isDevMode);
$conn = array(
'host' => 'dpg-ceu1ru82i3mj6pk2gd9g-a',
'driver' => 'pdo_pgsql',
'user' => 'projetdb_user',
'password' => 'ad36XZ52hrHbPNJVAbvxW198OLBPdLcW',
'dbname' => 'projetdb',
'port' => '5432'
);
$entityManager = EntityManager::create($conn, $config);
