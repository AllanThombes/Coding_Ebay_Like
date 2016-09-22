<?php

namespace SocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 *
 * @ORM\Table(name="price")
 * @ORM\Entity(repositoryClass="SocBundle\Repository\PriceRepository")
 */
class Price
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="SocBundle\Entity\Product", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
     private $product;

    /**
     * @var float
     *
     * @ORM\Column(name="actualPrice", type="float")
     */
    private $actualPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="immediatePrice", type="float", nullable=true)
     */
    private $immediatePrice;

    /**
     * @var float
     *
     * @ORM\Column(name="startingPrice", type="float", nullable=true)
     */
    private $startingPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="minBid", type="float", nullable=true)
     */
    private $minBid;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime", nullable=true)
     */
    private $endDate;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set actualPrice
     *
     * @param float $actualPrice
     *
     * @return Price
     */
    public function setActualPrice($actualPrice)
    {
        $this->actualPrice = $actualPrice;

        return $this;
    }

    /**
     * Get actualPrice
     *
     * @return float
     */
    public function getActualPrice()
    {
        return $this->actualPrice;
    }

    /**
     * Set immediatePrice
     *
     * @param float $immediatePrice
     *
     * @return Price
     */
    public function setImmediatePrice($immediatePrice)
    {
        $this->immediatePrice = $immediatePrice;

        return $this;
    }

    /**
     * Get immediatePrice
     *
     * @return float
     */
    public function getImmediatePrice()
    {
        return $this->immediatePrice;
    }

    /**
     * Set startingPrice
     *
     * @param float $startingPrice
     *
     * @return Price
     */
    public function setStartingPrice($startingPrice)
    {
        $this->startingPrice = $startingPrice;

        return $this;
    }

    /**
     * Get startingPrice
     *
     * @return float
     */
    public function getStartingPrice()
    {
        return $this->startingPrice;
    }

    /**
     * Set minBid
     *
     * @param float $minBid
     *
     * @return Price
     */
    public function setMinBid($minBid)
    {
        $this->minBid = $minBid;

        return $this;
    }

    /**
     * Get minBid
     *
     * @return float
     */
    public function getMinBid()
    {
        return $this->minBid;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Price
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Price
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
}
