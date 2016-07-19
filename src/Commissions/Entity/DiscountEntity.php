<?php

namespace Commissions\Entity;

class DiscountEntity
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $weekId;

    /**
     * @var float
     */
    private $discountSum;

    /**
     * @var int
     */
    private $timesLeft;

    public function __construct($userId, $weekId, $discountSum, $timesLeft)
    {
        $this->setUserId($userId);
        $this->setWeekId($weekId);
        $this->setDiscountSum($discountSum);
        $this->setTimesLeft($timesLeft);
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
     * @return int
     */
    public function getWeekId()
    {
        return $this->weekId;
    }

    /**
     * @param int $weekId
     */
    public function setWeekId($weekId)
    {
        $this->weekId = $weekId;
    }

    /**
     * @return mixed
     */
    public function getDiscountSum()
    {
        return $this->discountSum;
    }

    /**
     * @param mixed $discountSum
     */
    public function setDiscountSum($discountSum)
    {
        $this->discountSum = $discountSum;
    }

    /**
     * @return int
     */
    public function getTimesLeft()
    {
        return $this->timesLeft;
    }

    /**
     * @param int $timesLeft
     */
    public function setTimesLeft($timesLeft)
    {
        $this->timesLeft = $timesLeft;
    }
}
