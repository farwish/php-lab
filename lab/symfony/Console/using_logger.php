<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Psr\Log\LogLevel;

/**
 * php using_logger.php UsingLogger
 *
 * @author farwish
 */
(new Application())
    ->register('UsingLogger')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $output->writeln('aa');

        // Logger instance, contains output , level , format
        $logger = new ConsoleLogger($output, [
            LogLevel::INFO => OutputInterface::VERBOSITY_NORMAL
        ], [
            LogLevel::INFO => ConsoleLogger::ERROR
        ]);
        $logger->info('Log info : Emmmm');

    })
    ->getApplication()
    ->run();