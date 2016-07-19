<?php

use Commissions\Tool\CommissionsCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CommissionsCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new CommissionsCommand());

        $command = $application->find('commissions');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'path' => __DIR__ . '/Fixtures/input.csv',
        ));

        $this->assertContains("0.06\n0.90\n0.00", $commandTester->getDisplay());
    }

    public function testExecuteNoOperationsException()
    {
        $application = new Application();
        $application->add(new CommissionsCommand());

        $command = $application->find('commissions');
        $commandTester = new CommandTester($command);

        try {
            $commandTester->execute(array(
                'command' => $command->getName(),
                'path' => __DIR__ . '/Fixtures/input_empty.csv',
            ));
        } catch(\Exception $e){
            $this->assertContains('There are no operations given!', $e->getMessage());
            return;
        }
        $this->fail("No exception was found!");
    }
}