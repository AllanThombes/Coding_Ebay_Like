<?php

namespace SocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rating
 *
 * @ORM\Table(name="rating")
 * @ORM\Entity(repositoryClass="SocBundle\Repository\RatingRepository")
 */
class Rating
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
     * @var int
     *
     * @ORM\Column(name="rate", type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      minMessage = "Rate must be positive or null",
     *      maxMessage = "Rate must be lower or equal to 5"
     * )
     */
    private $rate;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="SocBundle\Entity\User", inversedBy="ratings")
     * @ORM\JoinColumn(name="rater_id", nullable=false)
     */
    protected $rater;

    /**
     * @ORM\ManyToOne(targetEntity="SocBundle\Entity\User", inversedBy="rated")
     * @ORM\JoinColumn(name="user_id", nullable=true)
     */
    protected $userRated;

    /**
     * @ORM\ManyToOne(targetEntity="SocBundle\Entity\Product", inversedBy="rated")
     * @ORM\JoinColumn(name="product_id", nullable=true)
     */
    protected $productRated;

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
     * Set rate
     *
     * @param integer $rate
     *
     * @return Rating
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Rating
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
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
     * Get the value of Rater
     *
     * @return mixed
     */
    public function getRater()
    {
        return $this->rater;
    }

    /**
     * Set the value of Rater
     *
     * @param mixed rater
     *
     * @return self
     */
    public function setRater($rater)
    {
        $this->rater = $rater;

        return $this;
    }

    /**
     * Get the value of User Rated
     *
     * @return mixed
     */
    public function getUserRated()
    {
        return $this->userRated;
    }

    /**
     * Set the value of User Rated
     *
     * @param mixed userRated
     *
     * @return self
     */
    public function setUserRated($userRated)
    {
        $this->userRated = $userRated;

        return $this;
    }

    /**
     * Get the value of Product Rated
     *
     * @return mixed
     */
    public function getProductRated()
    {
        return $this->productRated;
    }

    /**
     * Set the value of Product Rated
     *
     * @param mixed productRated
     *
     * @return self
     */
    public function setProductRated($productRated)
    {
        $this->productRated = $productRated;

        return $this;
    }

}
