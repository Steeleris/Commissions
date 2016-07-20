<?php

namespace Commissions\Entity;

class OperationEntity
{
    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $personType;

    /**
     * @var string
     */
    private $transactionType;

    /**
     * @var double
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var float
     */
    private $commissions = 0;

    public function __construct($date, $userId, $personType, $transactionType, $amount, $currency)
    {
        $this->setDate($date);
        $this->setUserId($userId);
        $this->setPersonType($personType);
        $this->setTransactionType($transactionType);
        $this->setAmount($amount);
        $this->setCurrency($currency);
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getPersonType()
    {
        return $this->personType;
    }

    /**
     * @param string $personType
     */
    public function setPersonType($personType)
    {
        $this->personType = $personType;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param string $transactionType
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @return double
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param double $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getCommissions()
    {
        return $this->commissions;
    }

    /**
     * @param float $commissions
     */
    public function setCommissions($commissions)
    {
        $this->commissions = $commissions;
    }
}
