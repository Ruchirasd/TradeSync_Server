<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 */
class Stock
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $stockCode;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $users;

    /**
     * @var string
     */
    private $exchange;

    /**
     * @var float
     */
    private $lastPrice;

    /**
     * @var int
     */
    private $status;

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set stockCode
     *
     * @param string $stockCode
     * @return Stock
     */
    public function setStockCode($stockCode)
    {
        $this->stockCode = $stockCode;

        return $this;
    }

    /**
     * Get stockCode
     *
     * @return string
     */
    public function getStockCode()
    {
        return $this->stockCode;
    }

    /**
     * Set lastPrice
     *
     * @param float $lastPrice
     * @return Stock
     */
    public function setLastPrice($lastPrice)
    {
        $this->lastPrice = $lastPrice;

        return $this;
    }

    /**
     * Get lastPrice
     *
     * @return float
     */
    public function getLastPrice()
    {
        return $this->lastPrice;
    }

    /**
     * Set exchange
     *
     * @param \AppBundle\Entity\Exchange $exchange
     * @return Stock
     */
    public function setExchange(\AppBundle\Entity\Exchange $exchange = null)
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * Get exchange
     *
     * @return \AppBundle\Entity\Exchange
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * Add users
     *
     * @param \AppBundle\Entity\User $users
     * @return Stock
     */
    public function addUser(\AppBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \AppBundle\Entity\User $users
     */
    public function removeUser(\AppBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}
