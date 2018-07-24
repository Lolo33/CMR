<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeliveryFee
 *
 * @ORM\Table(name="delivery_fee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeliveryFeeRepository")
 */
class DeliveryFee
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
     * @ORM\ManyToOne(targetEntity="Town", inversedBy="deliveryFees")
     */
    private $deliveryTown;

    /**
     * @var float
     *
     * @ORM\Column(name="min_order", type="float")
     */
    private $minOrder;

    /**
     * @var Restaurant
     *
     * @ORM\ManyToOne(targetEntity="Restaurant", inversedBy="townsDeliveredList")
     */
    private $restaurant;

    /**
     * @var float
     *
     * @ORM\Column(name="delivery_fee", type="float")
     */
    private $deliveryFee;


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
     * Set minOrder
     *
     * @param float $minOrder
     *
     * @return DeliveryFee
     */
    public function setMinOrder($minOrder)
    {
        $this->minOrder = $minOrder;

        return $this;
    }

    /**
     * Get minOrder
     *
     * @return float
     */
    public function getMinOrder()
    {
        return $this->minOrder;
    }

    /**
     * Set deliveryFee
     *
     * @param float $deliveryFee
     *
     * @return DeliveryFee
     */
    public function setDeliveryFee($deliveryFee)
    {
        $this->deliveryFee = $deliveryFee;

        return $this;
    }

    /**
     * Get deliveryFee
     *
     * @return float
     */
    public function getDeliveryFee()
    {
        return $this->deliveryFee;
    }

    /**
     * Set deliveryTown
     *
     * @param \AppBundle\Entity\Town $deliveryTown
     *
     * @return DeliveryFee
     */
    public function setDeliveryTown(\AppBundle\Entity\Town $deliveryTown = null)
    {
        $this->deliveryTown = $deliveryTown;

        return $this;
    }

    /**
     * Get deliveryTown
     *
     * @return \AppBundle\Entity\Town
     */
    public function getDeliveryTown()
    {
        return $this->deliveryTown;
    }

    /**
     * Set restaurant
     *
     * @param \AppBundle\Entity\Restaurant $restaurant
     *
     * @return DeliveryFee
     */
    public function setRestaurant(\AppBundle\Entity\Restaurant $restaurant = null)
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    /**
     * Get restaurant
     *
     * @return \AppBundle\Entity\Restaurant
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }
}
