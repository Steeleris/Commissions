<?php

use Commissions\Config\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function setUp(){
        Config::$items = array();
        Config::setParamsFile(__DIR__ . '/Fixtures/testing_parameters.php');
    }

    public function testNoKeyGiven()
    {
        $param = Config::get();
        $this->assertEquals($param, []);
    }

    public function testThirdLevelParameter()
    {
        $param = Config::get('a.b.c');
        $this->assertEquals($param, 'd');
    }

    public function testSecondLevelParameter()
    {
        $param = Config::get('rates.GBP');
        $this->assertEquals($param, 0.84);
    }

    public function testFirstLevelParameter()
    {
        $param = Config::get('second_parameter');
        $this->assertEquals($param, 5);
    }

    public function testNonExistingParameter()
    {
        try {
            Config::get('GBP');
        } catch(\Exception $e){
            $this->assertContains("Undefined index", $e->getMessage());
            return;
        }
        $this->fail("No exception was found!");
    }

    public function testNonExistingParameterStartingWithExistingPrefix()
    {
        try {
            Config::get('rates.OK');
        } catch(\Exception $e){
            $this->assertContains('Undefined index', $e->getMessage());
            return;
        }
        $this->fail("No exception was found!");
    }

    public function testTooShortParameterPath()
    {
        try {
            Config::get('rates');
        } catch(\Exception $e){
            $this->assertContains('Undefined index', $e->getMessage());
            return;
        }
        $this->fail("No exception was found!");
    }
}