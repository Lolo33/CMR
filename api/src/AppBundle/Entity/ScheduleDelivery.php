<?php

namespace AppBundle\Entity;

use Doctrine\Common\Comparable;
use Doctrine\ORM\Mapping as ORM;

/**
 * ScheduleDelivery
 *
 * @ORM\Table(name="schedule_delivery")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ScheduleRepository")
 */
class ScheduleDelivery
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
     * @ORM\ManyToOne(targetEntity="Restaurant", inversedBy="scheduleList")
     */
    private $restaurant;

    /**
     * @var int
     *
     * @ORM\Column(name="day_opening", type="integer")
     */
    private $dayOpening;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hour_opening", type="time")
     */
    private $hourOpening;

    /**
     * @var int
     *
     * @ORM\Column(name="day_closing", type="integer")
     */
    private $dayClosing;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hour_closing", type="time")
     */
    private $hourClosing;

    /**
     * @var string
     *
     * @ORM\Column(name="hour_status", type="string", length=255, nullable=true)
     */
    private $hourStatus;

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
     * @return ScheduleDelivery
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
     * Set dayOpening
     *
     * @param integer $dayOpening
     *
     * @return ScheduleDelivery
     */
    public function setDayOpening($dayOpening)
    {
        $this->dayOpening = $dayOpening;

        return $this;
    }

    /**
     * Get dayOpening
     *
     * @return int
     */
    public function getDayOpening()
    {
        return $this->dayOpening;
    }

    /**
     * Set hourOpening
     *
     * @param \DateTime $hourOpening
     *
     * @return ScheduleDelivery
     */
    public function setHourOpening($hourOpening)
    {
        $this->hourOpening = $hourOpening;

        return $this;
    }

    /**
     * Get hourOpening
     *
     * @return \DateTime
     */
    public function getHourOpening()
    {
        return $this->hourOpening->format("Y-m-d H:i:s");
    }

    /**
     * Set dayClosing
     *
     * @param integer $dayClosing
     *
     * @return ScheduleDelivery
     */
    public function setDayClosing($dayClosing)
    {
        $this->dayClosing = $dayClosing;

        return $this;
    }

    /**
     * Get dayClosing
     *
     * @return int
     */
    public function getDayClosing()
    {
        return $this->dayClosing;
    }

    /**
     * Set hourClosing
     *
     * @param \DateTime $hourClosing
     *
     * @return ScheduleDelivery
     */
    public function setHourClosing($hourClosing)
    {
        $this->hourClosing = $hourClosing;

        return $this;
    }

    /**
     * Get hourClosing
     *
     * @return \DateTime
     */
    public function getHourClosing()
    {
        return $this->hourClosing->format("Y-m-d H:i:s");
    }

    /**
     * Set hourStatus
     *
     * @param string $hourStatus
     *
     * @return ScheduleDelivery
     */
    public function setHourStatus($hourStatus)
    {
        $this->hourStatus = $hourStatus;

        return $this;
    }

    /**
     * Get hourStatus
     *
     * @return string
     */
    public function getHourStatus()
    {
        return $this->hourStatus;
    }
}

