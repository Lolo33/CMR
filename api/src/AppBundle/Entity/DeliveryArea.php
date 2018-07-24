<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeliveryArea
 *
 * @ORM\Table(name="delivery_area")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeliveryAreaRepository")
 */
class DeliveryArea
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
     * @ORM\ManyToOne(targetEntity="Restaurant")
     */
    private $restaurant;

    /**
     * @var int
     *
     * @ORM\Column(name="distance_min", type="integer")
     */
    private $distanceMin;

    /**
     * @var int
     *
     * @ORM\Column(name="distance_max", type="integer")
     */
    private $distanceMax;


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
     * @return DeliveryArea
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
     * Set distanceMin
     *
     * @param integer $distanceMin
     *
     * @return DeliveryArea
     */
    public function setDistanceMin($distanceMin)
    {
        $this->distanceMin = $distanceMin;

        return $this;
    }

    /**
     * Get distanceMin
     *
     * @return int
     */
    public function getDistanceMin()
    {
        return $this->distanceMin;
    }

    /**
     * Set distanceMax
     *
     * @param integer $distanceMax
     *
     * @return DeliveryArea
     */
    public function setDistanceMax($distanceMax)
    {
        $this->distanceMax = $distanceMax;

        return $this;
    }

    /**
     * Get distanceMax
     *
     * @return int
     */
    public function getDistanceMax()
    {
        return $this->distanceMax;
    }
}

