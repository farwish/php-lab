#!/usr/bin/env php
<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @doc http://symfony.com/doc/current/components/console/single_command_tool.html
 *
 * SingleApp v0.1
 *
 * @author farwish
 */
(new Application('SingleApp', 'v0.1'))
    ->register('app:hello')
    // `php single_app.php -h`
    ->addArgument('arg1', InputArgument::OPTIONAL, 'arg1 value')
    ->addOption('env', 'e', InputOption::VALUE_REQUIRED, '运行环境(dev/prod)', 'dev')
    ->setCode(function(InputInterface $input, OutputInterface $output) {
        $output->writeln('hello...');
        $output->writeln('Environment is ' . $input->getOption('env'));
    })

    // `php single_app.php app:test`
    ->getApplication()
    ->register('app:test')
    ->setCode(function(InputInterface $input, OutputInterface $output) {
        $output->writeln('test...');
    })

    // `php single_app.php`
    ->getApplication()
    ->setDefaultCommand('app:hello')

    ->run();
