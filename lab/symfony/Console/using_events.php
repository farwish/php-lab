<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Console\ConsoleEvents;

/**
 * @author farwish
 */

// Init terminal app.
$app = new Application();

// Just a event container.
$dispatcher = new EventDispatcher();

// Store listeners.
// event named ConsoleEvents::COMMAND will executed before command.
// total three event on console: before , after , error
$dispatcher->addListener(ConsoleEvents::COMMAND, function (ConsoleCommandEvent $event) {
    // $input = $event->getInput();
    $output = $event->getOutput();
    $command = $event->getCommand();
    $output->writeln(sprintf('Before running command <info>%s</info>', $command->getName()));
    throw new \Exception('TEST EXCEPTION');
    // $app = $command->getApplication();
});

// Handle exceptions thrown during the command execution.
$dispatcher->addListener(ConsoleEvents::ERROR, function (ConsoleErrorEvent $event) {
    $output = $event->getOutput();
    $command = $event->getCommand();
    $output->writeln(sprintf('Oops, exception happen in command <info>%s</info>, exitcode ' . $event->getExitCode(), $command->getName()));
});

// To perform some cleanup actions after the command executed.
$dispatcher->addListener(ConsoleEvents::TERMINATE, function (ConsoleTerminateEvent $event) {
    $output = $event->getOutput();
    $command = $event->getCommand();
    $output->writeln(sprintf('Fire command <info>%s</info>', $command->getName()));
    // $app = $command->getApplication();
});

// Set dispatcher for app
$app->setDispatcher($dispatcher);

// Run
$app->run();