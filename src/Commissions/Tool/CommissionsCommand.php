<?php

namespace Commissions\Tool;

use Commissions\Model\CountCommissions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Commissions\Model\ProcessCSV;

class CommissionsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('commissions')
            ->setDescription('Counts commissions of given operations')
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Path to the input file'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = new ProcessCSV();
        $path = $input->getArgument('path');
        $operations = $process->getAllOperations($path);

        $commissionsCounter = new CountCommissions($operations);
        $commissionsCounter->defineCommissions();

        foreach ($operations as $operation) {
            $tax = ceil($operation->getCommissions() * 100) / 100;
            $tax = number_format($tax, 2, '.', ',');
            $output->writeln($tax);
        }

        if (!$operations) {
            $output->writeln('<info>There are no operations given!</info>');
        }
    }
}
