<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderStatusHour
 *
 * @ORM\Table(name="order_status_hour")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderStatusHourRepository")
 */
class OrderStatusHour
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
     * @var OrderStatus
     *
     * @ORM\ManyToOne(targetEntity="OrderStatus")
     */
    private $status;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="statusList")
     */
    private $order;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hour", type="datetime")
     */
    private $hour;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="current", type="boolean", options={"default" : true})
     */
    private $current;



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
     * Set status
     *
     * @param string $status
     *
     * @return OrderStatusHour
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set hour
     *
     * @param \DateTime $hour
     *
     * @return OrderStatusHour
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * Get hour
     *
     * @return \DateTime
     */
    public function getHour()
    {
        return $this->hour->format("Y-m-d H:i:s");
    }

    /**
     * Set order
     *
     * @param \AppBundle\Entity\Order $order
     *
     * @return OrderStatusHour
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
     * Set current
     *
     * @param boolean $current
     *
     * @return OrderStatusHour
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * Get current
     *
     * @return boolean
     */
    public function getCurrent()
    {
        return $this->current;
    }
}
