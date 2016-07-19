<?php

namespace Commissions\Model;

use Commissions\Entity\DiscountEntity;
use Commissions\Config\Config;

class CountCommissions
{
    private $operations;
    private $discounts = [];

    public function __construct(array $operations)
    {
        $this->operations = $operations;
    }

    /**
     * Changing currency by given rate
     *
     * @param $amount
     * @param $rate
     * @return float
     */
    private function convertByRate($amount, $rate)
    {
        return $amount/$rate;
    }

    /**
     * Fees condition when an individual or juridical person deposit some money
     *
     * @param $amount
     * @param $rate
     * @return mixed
     */
    private function cashInCondition($amount, $rate)
    {
        $commissionPercent = Config::get('cash_in_percent');
        $cashInMaxFee = Config::get('cash_in_max_fee');

        $limit = $rate * $cashInMaxFee;
        $tax = $amount * ($commissionPercent/100);
        return $tax <= $limit ? $tax : $limit;
    }

    /**
     * Checking if the given discount already exists
     *
     * @param DiscountEntity $givenDiscount
     * @return int
     */
    private function findInDiscounts(DiscountEntity $givenDiscount)
    {
        $userId2 = $givenDiscount->getUserId();
        $weekId2 = $givenDiscount->getWeekId();

        foreach ($this->discounts as $discount) {
            if ($discount->getUserId() == $userId2 &&
                $discount->getWeekId() == $weekId2) {
                return $discount;
            }
        }
        return null;
    }

    /**
     * Discount for the first 3 operations with a money limit
     *
     * @param $userId
     * @param $amount
     * @param $weekId
     * @return int
     */
    private function firstOperationsDiscount($userId, $amount, $weekId)
    {
        $discountLimit = Config::get('discount_limit');
        $discountTimes = Config::get('discount_times');

        $discountObj = new DiscountEntity($userId, $weekId, $discountLimit, $discountTimes);

        $discount = $this->findInDiscounts($discountObj);

        if (!$discount) {
            array_push($this->discounts, $discountObj);
            $discount = $this->findInDiscounts($discountObj);
        }

        $discountSum = $discount->getDiscountSum();
        $discountSum -= $amount;
        $timesLeft = $discount->getTimesLeft();

        if ($discountSum > 0 && $timesLeft > 0) {
            $payFor = 0;
            $discount->setDiscountSum($discountSum);
        } else {
            $payFor = $amount;
            $payFor -= ($timesLeft > 0) ? $discount->getDiscountSum() : 0;
            $discount->setDiscountSum(0);
        }

        $timesLeft -= $timesLeft > 0 ? 1 : 0;
        $discount->setTimesLeft($timesLeft);

        return $payFor;
    }

    /**
     * Fees condition when an individual withdraw some money
     *
     * @param $userId
     * @param $amount
     * @param $weekId
     * @param $rate
     * @return int
     */
    private function cashOutNaturalCondition($userId, $amount, $weekId, $rate)
    {
        $commissionPercent = Config::get('cash_out_percent.natural');
        $payFor = $this->firstOperationsDiscount($userId, $amount, $weekId);

        return $payFor * ($commissionPercent/100) * $rate;
    }

    /**
     * Fees condition when a juridical person withdraw some money
     *
     * @param $amount
     * @param $rate
     * @return mixed
     */
    private function cashOutJuridicalCondition($amount, $rate)
    {
        $commissionPercent = Config::get('cash_out_percent.juridical');
        $cashOutMinFee = Config::get('cash_out_min_fee.juridical');

        $limit = $rate * $cashOutMinFee;
        $tax = $amount * ($commissionPercent/100) * $rate;
        return $tax > $limit ? $tax : $limit;
    }

    /**
     * Defining commissions of each operation
     */
    public function defineCommissions()
    {
        foreach ($this->operations as $operation) {
            $userId =  $operation->getUserId();
            $amount = $operation->getAmount();
            $weekId = $operation->getDate()->format("YW");
            $currency = $operation->getCurrency();
            $rate = Config::get('rates.'.$currency);

            $amount = $this->convertByRate($amount, $rate);

            if ($operation->getTransactionType() == "cash_in") {
                $tax = $this->cashInCondition($amount, $rate);
            } else {
                if ($operation->getPersonType() == "juridical") {
                    $tax = $this->cashOutJuridicalCondition($amount, $rate);
                } else {
                    $tax = $this->cashOutNaturalCondition($userId, $amount, $weekId, $rate);
                }
            }

            $operation->setCommissions($tax);
        }
    }
}
