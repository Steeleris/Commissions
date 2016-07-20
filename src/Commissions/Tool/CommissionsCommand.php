<?php

namespace Commissions\Tool;

use Commissions\Model\CountCommissions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Commissions\Model\ProcessCSV;
use Commissions\Config\Config;

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
        Config::setParamsFile(__DIR__ . '/../Config/parameters.php');

        $process = new ProcessCSV();
        $path = $input->getArgument('path');
        $operations = $process->getAllOperations($path);

        $commissionsCounter = new CountCommissions($operations);
        $commissionsCounter->defineCommissions();

        foreach ($operations as $operation) {
            $tax = sprintf("%.2f", $operation->getCommissions());
            $output->writeln($tax);
        }

        if (!$operations) {
            throw new \Exception('There are no operations given!');
        }
    }
}
