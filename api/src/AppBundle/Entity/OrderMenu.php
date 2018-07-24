<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderMenu
 *
 * @ORM\Table(name="order_menu")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderMenuRepository")
 */
class OrderMenu
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
     * @ORM\ManyToOne(targetEntity="Menu")
     */
    private $menu;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Order")
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="OrderMenuProduct", mappedBy="product")
     */
    private $productsList;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="MenuSize")
     */
    private $size;


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
     * Set menu
     *
     * @param string $menu
     *
     * @return OrderMenu
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
     * Set productsList
     *
     * @param string $productsList
     *
     * @return OrderMenu
     */
    public function setProductsList($productsList)
    {
        $this->productsList = $productsList;

        return $this;
    }

    /**
     * Get productsList
     *
     * @return string
     */
    public function getProductsList()
    {
        return $this->productsList;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productsList = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set order
     *
     * @param \AppBundle\Entity\Order $order
     *
     * @return OrderMenu
     */
    public function setOrder(\AppBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \AppBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Add productsList
     *
     * @param \AppBundle\Entity\Product $productsList
     *
     * @return OrderMenu
     */
    public function addProductsList(\AppBundle\Entity\OrderMenuProduct $productsList)
    {
        $this->productsList[] = $productsList;

        return $this;
    }

    /**
     * Remove productsList
     *
     * @param \AppBundle\Entity\Product $productsList
     */
    public function removeProductsList(\AppBundle\Entity\OrderMenuProduct $productsList)
    {
        $this->productsList->removeElement($productsList);
    }

    /**
     * Set size
     *
     * @param \AppBundle\Entity\MenuSize $size
     *
     * @return OrderMenu
     */
    public function setSize(\AppBundle\Entity\MenuSize $size = null)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return \AppBundle\Entity\MenuSize
     */
    public function getSize()
    {
        return $this->size;
    }
}
