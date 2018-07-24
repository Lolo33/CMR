<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductCategory
 *
 * @ORM\Table(name="product_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductCategoryRepository")
 */
class ProductCategory
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
     * @ORM\Column(name="cat_name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="cat_description", type="string", length=255)
     */
    private $description;

    /**
     * @var Restaurant
     *
     * @ORM\ManyToOne(targetEntity="Restaurant", inversedBy="categoriesOfProducts")
     */
    private $restaurant;

    /**
     * @var int
     *
     * @ORM\Column(name="cat_position", type="integer")
     */
    private $position;

    /**
     * @var Product[]
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    private $productsList;

    /**
     * @var bool
     *
     * @ORM\Column(name="cat_is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="cat_tactill_id", type="string", nullable=true)
     */
    private $tactillId;

    /**
     * @var string
     *
     * @ORM\Column(name="cat_tiller_id", type="string", nullable=true)
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
     * @return ProductCategory
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
     * Set description
     *
     * @param string $description
     *
     * @return ProductCategory
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
     * Set restaurant
     *
     * @param Restaurant $restaurant
     *
     * @return ProductCategory
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    /**
     * Get restaurant
     *
     * @return Restaurant
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return ProductCategory
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
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
     * @param \AppBundle\Entity\Product $productsList
     *
     * @return ProductCategory
     */
    public function addProductsList(\AppBundle\Entity\Product $productsList)
    {
        $this->productsList[] = $productsList;

        return $this;
    }

    /**
     * Remove productsList
     *
     * @param \AppBundle\Entity\Product $productsList
     */
    public function removeProductsList(\AppBundle\Entity\Product $productsList)
    {
        $this->productsList->removeElement($productsList);
    }

    /**
     * Remove productsList
     *
     * @param \AppBundle\Entity\Product $productsList
     */
    public function setProductsList( $productsList)
    {
        $this->productsList = $productsList;
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

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return ProductCategory
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
     * Set tactillId
     *
     * @param string $tactillId
     *
     * @return ProductCategory
     */
    public function setTactillId($tactillId)
    {
        $this->tactillId = $tactillId;

        return $this;
    }

    /**
     * Get tactillId
     *
     * @return string
     */
    public function getTactillId()
    {
        return $this->tactillId;
    }
}
