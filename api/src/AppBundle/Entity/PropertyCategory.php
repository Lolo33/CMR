<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PropertyCategory
 *
 * @ORM\Table(name="option_group")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OptionGroupRepository")
 */
class PropertyCategory
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
     * @ORM\Column(name="opt_grp_name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Restaurant")
     */
    private $restaurant;

    /**
     * @var int
     *
     * @ORM\OneToMany(targetEntity="Property", mappedBy="optionGroup")
     */
    private $optionsList;

    /**
     * @var int
     *
     * @ORM\Column(name="is_unique", type="boolean")
     */
    private $isUnique;

    /**
     * @var bool
     *
     * @ORM\Column(name="opt_grp_is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="opt_grp_tiller_id", type="string", nullable=true)
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
     * @return PropertyCategory
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
     * Set restaurant
     *
     * @param string $restaurant
     *
     * @return PropertyCategory
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    /**
     * Get restaurant
     *
     * @return string
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->optionsList = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add optionsList
     *
     * @param \AppBundle\Entity\Property $optionsList
     *
     * @return PropertyCategory
     */
    public function addOptionsList(\AppBundle\Entity\Property $optionsList)
    {
        $this->optionsList[] = $optionsList;

        return $this;
    }

    /**
     * Remove optionsList
     *
     * @param \AppBundle\Entity\Property $optionsList
     */
    public function removeOptionsList(\AppBundle\Entity\Property $optionsList)
    {
        $this->optionsList->removeElement($optionsList);
    }

    /**
     * Get optionsList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptionsList()
    {
        return $this->optionsList;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return PropertyCategory
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
     * Set isUnique
     *
     * @param boolean $isUnique
     *
     * @return PropertyCategory
     */
    public function setIsUnique($isUnique)
    {
        $this->isUnique = $isUnique;

        return $this;
    }

    /**
     * Get isUnique
     *
     * @return boolean
     */
    public function getIsUnique()
    {
        return $this->isUnique;
    }

    /**
     * Set tillerId
     *
     * @param string $tillerId
     *
     * @return PropertyCategory
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
