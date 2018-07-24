<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Supplement
 *
 * @ORM\Table(name="supplement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SupplementRepository")
 */
class Supplement
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="SupplementCategory", inversedBy="supplements")
     */
    private $supGroup;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var bool
     *
     * @ORM\Column(name="sup_is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var bool
     *
     * @ORM\Column(name="sup_is_sold_out", type="boolean")
     */
    private $isSoldOut;


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
     * Set name
     *
     * @param string $name
     *
     * @return Supplement
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set supGroup
     *
     * @param string $supGroup
     *
     * @return Supplement
     */
    public function setSupGroup($supGroup)
    {
        $this->supGroup = $supGroup;

        return $this;
    }

    /**
     * Get supGroup
     *
     * @return string
     */
    public function getSupGroup()
    {
        return $this->supGroup;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Supplement
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Supplement
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isSoldOut
     *
     * @param boolean $isSoldOut
     *
     * @return Supplement
     */
    public function setIsSoldOut($isSoldOut)
    {
        $this->isSoldOut = $isSoldOut;

        return $this;
    }

    /**
     * Get isSoldOut
     *
     * @return boolean
     */
    public function getIsSoldOut()
    {
        return $this->isSoldOut;
    }
}
