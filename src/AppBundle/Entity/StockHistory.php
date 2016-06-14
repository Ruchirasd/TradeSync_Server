<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StockHistory
 */
class StockHistory
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $stockId;

    /**
     * @var float
     */
    private $maxPrice;

    /**
     * @var float
     */
    private $minPrice;

    /**
     * @var \DateTime
     */
    private $date;



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
     * Set stockId
     *
     * @param integer $stockId
     * @return StockHistory
     */
    public function setStockId($stockId)
    {
        $this->stockId = $stockId;

        return $this;
    }

    /**
     * Get stockId
     *
     * @return integer 
     */
    public function getStockId()
    {
        return $this->stockId;
    }

    /**
     * Set maxPrice
     *
     * @param float $maxPrice
     * @return StockHistory
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * Get maxPrice
     *
     * @return float 
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * Set minPrice
     *
     * @param float $minPrice
     * @return StockHistory
     */
    public function setMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    /**
     * Get minPrice
     *
     * @return float 
     */
    public function getMinPrice()
    {
        return $this->minPrice;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return StockHistory
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
}
