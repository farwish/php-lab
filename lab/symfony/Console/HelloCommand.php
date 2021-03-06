<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HelloCommand
 */
class HelloCommand extends Command
{
    protected function configure()
    {
        parent::configure(); // TODO: Change the autogenerated stub

        $this->setName('app:hello')
            ->setDescription('Outputs "hello"')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('hello...');
    }
}
