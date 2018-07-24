<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ScheduleException
 *
 * @ORM\Table(name="schedule_exception")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ScheduleExceptionRepository")
 */
class ScheduleException
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Restaurant")
     */
    private $restaurant;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="ScheduleExceptionType")
     */
    private $typeException;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return ScheduleException
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set restaurant
     *
     * @param string $restaurant
     *
     * @return ScheduleException
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
     * Set typeException
     *
     * @param \AppBundle\Entity\ScheduleExceptionType $typeException
     *
     * @return ScheduleException
     */
    public function setTypeException(\AppBundle\Entity\ScheduleExceptionType $typeException = null)
    {
        $this->typeException = $typeException;

        return $this;
    }

    /**
     * Get typeException
     *
     * @return \AppBundle\Entity\ScheduleExceptionType
     */
    public function getTypeException()
    {
        return $this->typeException;
    }
}
