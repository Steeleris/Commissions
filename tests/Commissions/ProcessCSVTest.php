<?php

use Commissions\Model\ProcessCSV;

class ProcessCSVTest extends \PHPUnit_Framework_TestCase
{
    function testFileNotFound()
    {
        $process = new ProcessCSV();

        try {
            $process->getAllOperations(__DIR__ . '/wrong_file.csv');
        } catch(\Exception $e){
            $this->assertEquals("File does not exist!", $e->getMessage());
            return;
        }
        $this->fail("No exception was found!");
    }

    function testObjectsOfFileData()
    {
        $process = new ProcessCSV();
        $operations = $process->getAllOperations(__DIR__ . '/Fixtures/input.csv');
        $this->assertEquals($operations[0]->getUserId(), 3);
        $this->assertEquals($operations[1]->getUserId(), 1);
        $this->assertEquals($operations[2]->getUserId(), 2);
    }

    private function checkException($filename, $message)
    {
        $process = new ProcessCSV();
        try {
            $process->getAllOperations(__DIR__ . '/Fixtures/' . $filename);
        } catch(\Exception $e){
            $this->assertContains($message, $e->getMessage());
            return;
        }
        $this->fail("No exception was found!");
    }

    function testGivenParametersException()
    {
        $this->checkException('wrong_parameters.csv', 'Wrong amount of input parameters');
    }

    function testWrongDateException()
    {
        $this->checkException('wrong_date.csv', 'Wrong DATE');
    }

    function testWrongIdException()
    {
        $this->checkException('wrong_id.csv', 'Wrong ID format');
    }

    function testNotIntegerIdException()
    {
        $this->checkException('not_integer_id.csv', 'ID is not an integer');
    }

    function testWrongPersonTypeException()
    {
        $this->checkException('wrong_person.csv', 'Wrong PERSON TYPE');
    }

    function testWrongOperationTypeException()
    {
        $this->checkException('wrong_operation.csv', 'Wrong TRANSACTION TYPE');
    }

    function testWrongAmountException()
    {
        $this->checkException('wrong_amount.csv', 'Wrong AMOUNT format');
    }

    function testWrongCurrencyException()
    {
        $this->checkException('wrong_currency.csv', 'Wrong CURRENCY specified');
    }
}