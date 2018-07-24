<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuRepository")
 */
class Menu
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
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Restaurant")
     */
    private $restaurant;

    /**
     * @var MenuOption[]
     *
     * @ORM\OneToMany(targetEntity="MenuOption", mappedBy="menu")
     */
    private $menuOptionsList;

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
     * @return Menu
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
     * @return Menu
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
     * Set restaurant
     *
     * @param string $restaurant
     *
     * @return Menu
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
        $this->menuOptionsList = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add menuOptionsList
     *
     * @param \AppBundle\Entity\MenuOption $menuOptionsList
     *
     * @return Menu
     */
    public function addMenuOptionsList(\AppBundle\Entity\MenuOption $menuOptionsList)
    {
        $this->menuOptionsList[] = $menuOptionsList;

        return $this;
    }

    /**
     * Remove menuOptionsList
     *
     * @param \AppBundle\Entity\MenuOption $menuOptionsList
     */
    public function removeMenuOptionsList(\AppBundle\Entity\MenuOption $menuOptionsList)
    {
        $this->menuOptionsList->removeElement($menuOptionsList);
    }

    /**
     * Get menuOptionsList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMenuOptionsList()
    {
        return $this->menuOptionsList;
    }

    public function getPriceString(){
        return Globals::formaterPrix($this->price);
    }
}
