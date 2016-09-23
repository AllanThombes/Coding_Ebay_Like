<?php

namespace SocBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="SocBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;


    /**
     * @ORM\OneToMany(targetEntity="SocBundle\Entity\Product", mappedBy="user", cascade={"remove"})
     */
    protected $products;

    /**
     * @ORM\OneToMany(targetEntity="SocBundle\Entity\Bid", mappedBy="user", cascade={"remove"})
     */
    protected $bids;

    /**
     * @ORM\OneToMany(targetEntity="SocBundle\Entity\Rating", mappedBy="rater", cascade={"remove"})
     */
    protected $ratings;

    /**
     * @ORM\OneToMany(targetEntity="SocBundle\Entity\Rating", mappedBy="userRated", cascade={"remove"})
     */
    protected $rated;



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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
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
     * Get the value of Products
     *
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set the value of Products
     *
     * @param mixed products
     *
     * @return self
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Get the value of Ratings
     *
     * @return mixed
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * Set the value of Ratings
     *
     * @param mixed ratings
     *
     * @return self
     */
    public function setRatings($ratings)
    {
        $this->ratings = $ratings;

        return $this;
    }


    /**
     * Get the value of Rated
     *
     * @return mixed
     */
    public function getRated()
    {
        return $this->rated;
    }

    /**
     * Set the value of Rated
     *
     * @param mixed rated
     *
     * @return self
     */
    public function setRated($rated)
    {
        $this->rated = $rated;

        return $this;
    }

}
