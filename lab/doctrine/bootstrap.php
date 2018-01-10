<?php

/**
 * configuration
 *
 * @doc http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/configuration.html
 * @license Apache-2.0
 * @author farwish <farwish@foxmail.com>
 */

include_once __DIR__ . '/../../vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = [ trim(shell_exec('pwd')) . '/entity' ];
$isDevMode = true;

$dbParams = array(
    'driver'   => 'pdo_mysql',
	'host'     => '127.0.0.1',
	'port'     => 3306,
	'charset'  => 'utf8mb4',
    'user'     => 'root',
    'password' => '123456',
    'dbname'   => 'alconseek',
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);
