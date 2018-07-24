<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="services")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServicesRepository")
 */
class Service
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
     * @ORM\Column(name="hourStart", type="time")
     */
    private $hourStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hourEnd", type="time")
     */
    private $hourEnd;

    /**
     * @var bool
     *
     * @ORM\Column(name="fixedTime", type="boolean")
     */
    private $fixedTime;


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
     * Set hourStart
     *
     * @param \DateTime $hourStart
     *
     * @return Service
     */
    public function setHourStart($hourStart)
    {
        $this->hourStart = $hourStart;

        return $this;
    }

    /**
     * Get hourStart
     *
     * @return \DateTime
     */
    public function getHourStart()
    {
        return $this->hourStart;
    }

    /**
     * Set hourEnd
     *
     * @param \DateTime $hourEnd
     *
     * @return Service
     */
    public function setHourEnd($hourEnd)
    {
        $this->hourEnd = $hourEnd;

        return $this;
    }

    /**
     * Get hourEnd
     *
     * @return \DateTime
     */
    public function getHourEnd()
    {
        return $this->hourEnd;
    }

    /**
     * Set fixedTime
     *
     * @param boolean $fixedTime
     *
     * @return Service
     */
    public function setFixedTime($fixedTime)
    {
        $this->fixedTime = $fixedTime;

        return $this;
    }

    /**
     * Get fixedTime
     *
     * @return boolean
     */
    public function getFixedTime()
    {
        return $this->fixedTime;
    }
}
