<?php

namespace SocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bid
 *
 * @ORM\Table(name="bid")
 * @ORM\Entity(repositoryClass="SocBundle\Repository\BidRepository")
 */
class Bid
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
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var bool
     *
     * @ORM\Column(name="automatic", type="boolean")
     */
    private $automatic;

    /**
     * @var float
     *
     * @ORM\Column(name="maxBid", type="float", nullable=true)
     */
    private $maxBid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="bidDate", type="datetime")
     */
    private $bidDate;

    /**
     * @ORM\ManyToOne(targetEntity="SocBundle\Entity\User", inversedBy="bids")
     * @ORM\JoinColumn(name="user_id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="SocBundle\Entity\Price", inversedBy="bids")
     * @ORM\JoinColumn(name="price_id", nullable=false)
     */
    protected $price;

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
     * Set amount
     *
     * @param float $amount
     *
     * @return Bid
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set automatic
     *
     * @param boolean $automatic
     *
     * @return Bid
     */
    public function setAutomatic($automatic)
    {
        $this->automatic = $automatic;

        return $this;
    }

    /**
     * Get automatic
     *
     * @return bool
     */
    public function getAutomatic()
    {
        return $this->automatic;
    }

    /**
     * Set maxBid
     *
     * @param float $maxBid
     *
     * @return Bid
     */
    public function setMaxBid($maxBid)
    {
        $this->maxBid = $maxBid;

        return $this;
    }

    /**
     * Get maxBid
     *
     * @return float
     */
    public function getMaxBid()
    {
        return $this->maxBid;
    }

    /**
     * Set bidDate
     *
     * @param \DateTime $bidDate
     *
     * @return Bid
     */
    public function setBidDate($bidDate)
    {
        $this->bidDate = $bidDate;

        return $this;
    }

    /**
     * Get bidDate
     *
     * @return \DateTime
     */
    public function getBidDate()
    {
        return $this->bidDate;
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
     * Get the value of User
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of User
     *
     * @param mixed user
     *
     * @return self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of Price
     *
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of Price
     *
     * @param mixed price
     *
     * @return self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

}
