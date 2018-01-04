<?php
/**
 * cli-config.php
 *
 * @doc http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/configuration.html
 * @license Apache-2.0
 * @author farwish <farwish@foxmail.com>
 */

include_once 'bootstrap.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;

return ConsoleRunner::createHelperSet($entityManager);
