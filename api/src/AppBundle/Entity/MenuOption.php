<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenuOption
 *
 * @ORM\Table(name="menu_option")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuOptionRepository")
 */
class MenuOption
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
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="menuOptionsList")
     */
    private $menu;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="MenuOptionProduct", mappedBy="menuOption")
     */
    private $productsList;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;


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
     * @return MenuOption
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
     * Set menu
     *
     * @param string $menu
     *
     * @return MenuOption
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return string
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return MenuOption
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productsList = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add productsList
     *
     * @param \AppBundle\Entity\MenuOptionProduct $productsList
     *
     * @return MenuOption
     */
    public function addProductsList(\AppBundle\Entity\MenuOptionProduct $productsList)
    {
        $this->productsList[] = $productsList;

        return $this;
    }

    /**
     * Remove productsList
     *
     * @param \AppBundle\Entity\MenuOptionProduct $productsList
     */
    public function removeProductsList(\AppBundle\Entity\MenuOptionProduct $productsList)
    {
        $this->productsList->removeElement($productsList);
    }

    /**
     * Get productsList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductsList()
    {
        return $this->productsList;
    }
}
