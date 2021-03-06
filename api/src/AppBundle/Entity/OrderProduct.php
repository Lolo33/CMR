<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Utils\Globals;
use Doctrine\ORM\Mapping as ORM;

/**
 * OrderProduct
 *
 * @ORM\Table(name="order_product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderProductRepository")
 */
class OrderProduct
{


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $referenceKey;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Order")
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productList")
     */
    private $product;


    /**
     * @var string
     *
     * @ORM\Column(name="precisions", type="string", length=255, nullable=true)
     */
    private $precisions;

    /**
     * @var int
     *
     * @ORM\ManyToMany(targetEntity="Supplement")
     */
    private $supplementsList;

    /**
     * @var int
     *
     * @ORM\ManyToMany(targetEntity="Property")
     */
    private $propertiesList;

    /**
     * @var string
     *
     * @ORM\Column(name="is_ready", type="boolean")
     */
    private $isReady;



    public function totalPrice(){
        return $this->getPriceTTC();
    }

    public function getPriceTTC(){
        $price = $this->product->getPrice();
        foreach ($this->propertiesList as $opt) {
            $price += $opt->getPrice();
        }
        foreach ($this->supplementsList as $sup)
            $price += $sup->getPrice();
        return Globals::formaterPrix(round($price, 2));
    }

    public function getPriceHT(){
        $price = $this->product->getPrice()  / (1 + ($this->product->getVat()->getRate() / 100));
        foreach ($this->propertiesList as $opt)
            $price += $opt->getPrice();
        foreach ($this->supplementsList as $sup)
            $price += $sup->getPrice();
        return Globals::formaterPrix(round($price, 2));
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getReferenceKey()
    {
        return $this->referenceKey;
    }


    /**
     * Set product
     *
     * @param string $product
     *
     * @return OrderProduct
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
     * Set order
     *
     * @param \AppBundle\Entity\Order $order
     *
     * @return OrderProduct
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
     * Constructor
     */
    public function __construct()
    {
        $this->supplementsList = new \Doctrine\Common\Collections\ArrayCollection();
        $this->propertiesList = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add supplementsList
     *
     * @param \AppBundle\Entity\Supplement $supplementsList
     *
     * @return OrderProduct
     */
    public function addSupplementsList(\AppBundle\Entity\Supplement $supplementsList)
    {
        $this->supplementsList[] = $supplementsList;

        return $this;
    }

    /**
     * Remove supplementsList
     *
     * @param \AppBundle\Entity\Supplement $supplementsList
     */
    public function removeSupplementsList(\AppBundle\Entity\Supplement $supplementsList)
    {
        $this->supplementsList->removeElement($supplementsList);
    }

    /**
     * Get supplementsList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSupplementsList()
    {
        return $this->supplementsList;
    }




    /**
     * Add propertiesList
     *
     * @param \AppBundle\Entity\Property $propertiesList
     *
     * @return OrderProduct
     */
    public function addPropertiesList(\AppBundle\Entity\Property $propertiesList)
    {
        $this->propertiesList[] = $propertiesList;

        return $this;
    }

    /**
     * Remove propertiesList
     *
     * @param \AppBundle\Entity\Property $propertiesList
     */
    public function removePropertiesList(\AppBundle\Entity\Property $propertiesList)
    {
        $this->propertiesList->removeElement($propertiesList);
    }

    /**
     * Get propertiesList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPropertiesList()
    {
        return $this->propertiesList;
    }

    /**
     * Set precisions
     *
     * @param string $precisions
     *
     * @return OrderProduct
     */
    public function setPrecisions($precisions)
    {
        $this->precisions = $precisions;

        return $this;
    }

    /**
     * Get precisions
     *
     * @return string
     */
    public function getPrecisions()
    {
        return $this->precisions;
    }

    /**
     * Set isReady
     *
     * @param boolean $isReady
     *
     * @return OrderProduct
     */
    public function setIsReady($isReady)
    {
        $this->isReady = $isReady;

        return $this;
    }

    /**
     * Get isReady
     *
     * @return boolean
     */
    public function getIsReady()
    {
        return $this->isReady;
    }
}
