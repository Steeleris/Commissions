<?php

use Commissions\Model\CountCommissions;
use Commissions\Entity\OperationEntity;
use Commissions\Config\Config;

class CountCommissionsTest extends \PHPUnit_Framework_TestCase
{
    public function setUp(){
        Config::setParamsFile(__DIR__ . '/Fixtures/parameters.php');
    }

    private function settingCommissions($operation)
    {
        $countCommissions = new CountCommissions(array($operation));
        $countCommissions->defineCommissions();
        return $operation->getCommissions();
    }

    public function testSingleNaturalCashInEUR()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_in", 10000, "EUR");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 3);
    }

    public function testSingleNaturalCashInUSD()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_in", 10000, "USD");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 2.61);
    }

    public function testSingleNaturalCashInJPY()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_in", 1000000, "JPY");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 2.32);
    }

    public function testSingleNaturalCashOutEUR()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 10000, "EUR");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 27);
    }

    public function testSingleNaturalCashOutUSD()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 10000, "USD");
        $tax = $this->settingCommissions($operation);
        $this->assertEquals($tax, 26.56);
    }

    public function testSingleNaturalCashOutJPY()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 1000000, "JPY");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 2611.41);
    }

    public function testSingleJuridicalCashInEUR()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "juridical", "cash_in", 10000, "EUR");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 3);
    }

    public function testSingleJuridicalCashInUSD()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "juridical", "cash_in", 10000, "USD");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 2.61);
    }

    public function testSingleJuridicalCashInJPY()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "juridical", "cash_in", 1000000, "JPY");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 2.32);
    }

    public function testSingleJuridicalCashOutEUR()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "juridical", "cash_out", 10000, "EUR");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 30);
    }

    public function testSingleJuridicalCashOutUSD()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "juridical", "cash_out", 10000, "USD");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 30);
    }

    public function testSingleJuridicalCashOutJPY()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "juridical", "cash_out", 1000000, "JPY");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 3000);
    }

    public function testSingleNaturalCashOutFullDiscount()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 1000, "EUR");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 0);
    }

    public function testNaturalCashOutWhenNoDiscountTimesLeft()
    {
        $operations = array(
            new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 10, "EUR"),
            new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 10, "EUR"),
            new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 10, "EUR"),
            new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 1000, "EUR")
        );

        $countCommissions = new CountCommissions($operations);
        $countCommissions->defineCommissions();

        $tax = $operations[3]->getCommissions();

        $this->assertEquals($tax, 3);
    }

    public function testOnTheEdgeFullDiscountNaturalCashOutEUR()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 1000, "EUR");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 0);
    }

    public function testOnTheEdgeFullDiscountNaturalCashOutUSD()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 1149.7, "USD");
        $tax = $this->settingCommissions($operation);
        $this->assertEquals($tax, 0);
    }

    public function testOnTheEdgeFullDiscountNaturalCashOutJPY()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 129530, "JPY");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 0);
    }

    public function testOnTheEdgeSmallPaymentNaturalCashOutEUR()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 1000.01, "EUR");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 0.01);
    }

    public function testOnTheEdgeSmallPaymentNaturalCashOutUSD()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 1149.71, "USD");
        $tax = $this->settingCommissions($operation);
        $this->assertEquals($tax, 0.01);
    }

    public function testOnTheEdgeSmallPaymentNaturalCashOutJPY()
    {
        $operation = new OperationEntity(new \DateTime("2016-01-01"), 1, "natural", "cash_out", 129530.01, "JPY");
        $tax = $this->settingCommissions($operation);

        $this->assertEquals($tax, 0.01);
    }
}