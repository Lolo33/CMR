<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Property
 *
 * @ORM\Table(name="options")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OptionRepository")
 */
class Property
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
     * @ORM\Column(name="opt_name", type="string", length=255)
     */
    private $name;

    /**
     * @var PropertyCategory
     *
     * @ORM\ManyToOne(targetEntity="PropertyCategory")
     */
    private $optionGroup;

    /**
     * @var float
     *
     * @ORM\Column(name="opt_price", type="float", length=255)
     */
    private $price;

    /**
     * @var bool
     *
     * @ORM\Column(name="opt_is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var bool
     *
     * @ORM\Column(name="opt_is_sold_out", type="boolean")
     */
    private $isSoldOut;

    /**
     * @var string
     *
     * @ORM\Column(name="opt_tiller_id", type="string", nullable=true)
     */
    private $tillerId;

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
     * @return Property
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
     * Set price
     *
     * @param float $price
     *
     * @return Property
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
     * Set optionGroup
     *
     * @param \AppBundle\Entity\PropertyCategory $optionGroup
     *
     * @return Property
     */
    public function setOptionGroup(\AppBundle\Entity\PropertyCategory $optionGroup = null)
    {
        $this->optionGroup = $optionGroup;

        return $this;
    }

    /**
     * Get optionGroup
     *
     * @return \AppBundle\Entity\PropertyCategory
     */
    public function getOptionGroup()
    {
        return $this->optionGroup;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Property
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
     * @return Property
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

    public function priceString() {
        return Globals::formaterPrix($this->price);
    }

    /**
     * Set tillerId
     *
     * @param string $tillerId
     *
     * @return Property
     */
    public function setTillerId($tillerId)
    {
        $this->tillerId = $tillerId;

        return $this;
    }

    /**
     * Get tillerId
     *
     * @return string
     */
    public function getTillerId()
    {
        return $this->tillerId;
    }
}
