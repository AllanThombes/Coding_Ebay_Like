<?php

namespace SocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="SocBundle\Repository\ProductRepository")
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     * @Assert\Range(
     *      min = 0.01,
     *      minMessage = "Price can't be null"
     * )
     */
    private $price;

    /**
     * @ORM\OneToOne(targetEntity="SocBundle\Entity\Price", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
     private $bid;

    /**
     * @ORM\ManyToOne(targetEntity="SocBundle\Entity\User", inversedBy="products")
     * @ORM\JoinColumn(name="user_id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="SocBundle\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(name="categ_id", nullable=false)
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="SocBundle\Entity\Rating", mappedBy="productRated", cascade={"remove"})
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
     * Set title
     *
     * @param string $title
     *
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
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
     * Get the value of Category
     *
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of Category
     *
     * @param mixed category
     *
     * @return self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }



    /**
     * Get the value of Bid
     *
     * @return mixed
     */
    public function getBid()
    {
        return $this->bid;
    }

    /**
     * Set the value of Bid
     *
     * @param mixed bid
     *
     * @return self
     */
    public function setBid($bid)
    {
        $this->bid = $bid;

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
