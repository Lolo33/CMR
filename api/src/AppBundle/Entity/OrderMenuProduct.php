<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderMenuProduct
 *
 * @ORM\Table(name="order_menu_product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderMenuProductRepository")
 */
class OrderMenuProduct
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
     * @ORM\ManyToOne(targetEntity="OrderMenu")
     */
    private $orderMenu;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productsList")
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="Property")
     */
    private $properties;

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="Supplement")
     */
    private $supplements;


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
     * Set orderMenu
     *
     * @param string $orderMenu
     *
     * @return OrderMenuProduct
     */
    public function setOrderMenu($orderMenu)
    {
        $this->orderMenu = $orderMenu;

        return $this;
    }

    /**
     * Get orderMenu
     *
     * @return string
     */
    public function getOrderMenu()
    {
        return $this->orderMenu;
    }

    /**
     * Set product
     *
     * @param string $product
     *
     * @return OrderMenuProduct
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set properties
     *
     * @param string $properties
     *
     * @return OrderMenuProduct
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * Get properties
     *
     * @return string
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Set supplements
     *
     * @param string $supplements
     *
     * @return OrderMenuProduct
     */
    public function setSupplements($supplements)
    {
        $this->supplements = $supplements;

        return $this;
    }

    /**
     * Get supplements
     *
     * @return string
     */
    public function getSupplements()
    {
        return $this->supplements;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->properties = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add property
     *
     * @param \AppBundle\Entity\Property $property
     *
     * @return OrderMenuProduct
     */
    public function addProperty(\AppBundle\Entity\Property $property)
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * Remove property
     *
     * @param \AppBundle\Entity\Property $property
     */
    public function removeProperty(\AppBundle\Entity\Property $property)
    {
        $this->properties->removeElement($property);
    }

    /**
     * Add supplement
     *
     * @param \AppBundle\Entity\Supplement $supplement
     *
     * @return OrderMenuProduct
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
}
