<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RestaurantPaymentMode
 *
 * @ORM\Table(name="restaurant_payment_mode")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RestaurantPaymentModeRepository")
 */
class RestaurantPaymentMode
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
     * @ORM\ManyToOne(targetEntity="Restaurant", inversedBy="paymentModes")
     */
    private $restaurant;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="PaymentMode")
     */
    private $paymentMode;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="OrderType")
     */
    private $orderType;


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
     * Set restaurant
     *
     * @param string $restaurant
     *
     * @return RestaurantPaymentMode
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
     * Set paymentMode
     *
     * @param string $paymentMode
     *
     * @return RestaurantPaymentMode
     */
    public function setPaymentMode($paymentMode)
    {
        $this->paymentMode = $paymentMode;

        return $this;
    }

    /**
     * Get paymentMode
     *
     * @return string
     */
    public function getPaymentMode()
    {
        return $this->paymentMode;
    }

    /**
     * Set orderType
     *
     * @param string $orderType
     *
     * @return RestaurantPaymentMode
     */
    public function setOrderType($orderType)
    {
        $this->orderType = $orderType;

        return $this;
    }

    /**
     * Get orderType
     *
     * @return string
     */
    public function getOrderType()
    {
        return $this->orderType;
    }
}

