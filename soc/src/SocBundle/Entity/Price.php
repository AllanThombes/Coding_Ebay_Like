<?php

namespace SocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
      * @ORM\OneToMany(targetEntity="SocBundle\Entity\Bid", mappedBy="price", cascade={"remove"})
      */
     protected $bids;

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
     *
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
    * @Assert\Callback
    */
  public function isContentValid(ExecutionContextInterface $context)
  {
    if (!$this->getStartingPrice() && !$this->getImmediatePrice()) {
      $context
        ->buildViolation("You can't have both immediate and starting price null.")
        ->atPath('content')
        ->addViolation() // trigg the error
      ;
    }
    if ($this->getStartingPrice()) {
      if(!$this->getMinBid()) {
        $context
          ->buildViolation("You must set the minimum bid.")
          ->atPath('content')
          ->addViolation() // trigg the error
        ;
      }
      if(!$this->getEndDate()) {
        $context
          ->buildViolation("You must set the end date.")
          ->atPath('content')
          ->addViolation() // trigg the error
        ;
      }
      if($this->getImmediatePrice() && $this->getImmediatePrice() < $this->getStartingPrice()) {
        $context
          ->buildViolation("Immediate price must be higher than starting price.")
          ->atPath('content')
          ->addViolation() // trigg the error
        ;
      }
    }
  }

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

    /**
     * Set the value of Id
     *
     * @param int id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of Product
     *
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set the value of Product
     *
     * @param mixed product
     *
     * @return self
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }


    /**
     * Get the value of Bids
     *
     * @return mixed
     */
    public function getBids()
    {
        return $this->bids;
    }

    /**
     * Set the value of Bids
     *
     * @param mixed bids
     *
     * @return self
     */
    public function setBids($bids)
    {
        $this->bids = $bids;

        return $this;
    }


}
