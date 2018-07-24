<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SupplementCategory
 *
 * @ORM\Table(name="supplement_group")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SupplementGroupRepository")
 */
class SupplementCategory
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
     * @ORM\ManyToOne(targetEntity="Restaurant")
     */
    private $restaurant;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="Supplement", mappedBy="supGroup")
     */
    private $supplements;

    /**
     * @var bool
     *
     * @ORM\Column(name="sup_grp_is_active", type="boolean")
     */
    private $isActive;


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
     * @return SupplementCategory
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
     * @return SupplementCategory
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
        $this->supplements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add supplement
     *
     * @param \AppBundle\Entity\Supplement $supplement
     *
     * @return SupplementCategory
     */
    public function addSupplement(\AppBundle\Entity\Supplement $supplement)
    {
        $this->supplements[] = $supplement;

        return $this;
    }

    /**
     * Remove supplement
     *
     * @param \AppBundle\Entity\Supplement $supplement
     */
    public function removeSupplement(\AppBundle\Entity\Supplement $supplement)
    {
        $this->supplements->removeElement($supplement);
    }

    /**
     * Get supplements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSupplements()
    {
        return $this->supplements;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return SupplementCategory
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
}
