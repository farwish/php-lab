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
$isDevMode = false;

$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => '123456',
    'dbname'   => 'alconseek',
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);

