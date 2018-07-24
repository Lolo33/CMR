<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookingRepository")
 */
class Booking
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
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="clientName", type="string", length=255)
     */
    private $clientName;

    /**
     * @var string
     *
     * @ORM\Column(name="clientTel", type="string", length=255)
     */
    private $clientTel;

    /**
     * @var int
     *
     * @ORM\Column(name="nbOfPersons", type="integer")
     */
    private $nbOfPersons;

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="Table")
     */
    private $assignedTable;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hour", type="datetime")
     */
    private $hour;

    /**
     * @var \DateTime
     *
     * @ORM\ManyToOne(targetEntity="BookingStatus")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\ManyToOne(targetEntity="BookingType")
     */
    private $type;

    /**
     * @var Business
     *
     * @ORM\ManyToOne(targetEntity="Business")
     */
    private $business;

    /**
     * @var Business
     *
     * @ORM\OneToOne(targetEntity="Order")
     */
    private $order;


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
     * Set reference
     *
     * @param string $reference
     *
     * @return Booking
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set clientName
     *
     * @param string $clientName
     *
     * @return Booking
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Set clientTel
     *
     * @param string $clientTel
     *
     * @return Booking
     */
    public function setClientTel($clientTel)
    {
        $this->clientTel = $clientTel;

        return $this;
    }

    /**
     * Get clientTel
     *
     * @return string
     */
    public function getClientTel()
    {
        return $this->clientTel;
    }

    /**
     * Set nbOfPersons
     *
     * @param integer $nbOfPersons
     *
     * @return Booking
     */
    public function setNbOfPersons($nbOfPersons)
    {
        $this->nbOfPersons = $nbOfPersons;

        return $this;
    }

    /**
     * Get nbOfPersons
     *
     * @return int
     */
    public function getNbOfPersons()
    {
        return $this->nbOfPersons;
    }

    /**
     * Set assignedTable
     *
     * @param string $assignedTable
     *
     * @return Booking
     */
    public function setAssignedTable($assignedTable)
    {
        $this->assignedTable = $assignedTable;

        return $this;
    }

    /**
     * Get assignedTable
     *
     * @return string
     */
    public function getAssignedTable()
    {
        return $this->assignedTable;
    }

    /**
     * Set hour
     *
     * @param \DateTime $hour
     *
     * @return Booking
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
     * Set status
     *
     * @param \AppBundle\Entity\BookingStatus $status
     *
     * @return Booking
     */
    public function setStatus(\AppBundle\Entity\BookingStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \AppBundle\Entity\BookingStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set type
     *
     * @param \AppBundle\Entity\BookingType $type
     *
     * @return Booking
     */
    public function setType(\AppBundle\Entity\BookingType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \AppBundle\Entity\BookingType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set business
     *
     * @param \AppBundle\Entity\Business $business
     *
     * @return Booking
     */
    public function setBusiness(\AppBundle\Entity\Business $business = null)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * Get business
     *
     * @return \AppBundle\Entity\Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Set order
     *
     * @param \AppBundle\Entity\Order $order
     *
     * @return Booking
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
        $this->assignedTable = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add assignedTable
     *
     * @param \AppBundle\Entity\Table $assignedTable
     *
     * @return Booking
     */
    public function addAssignedTable(\AppBundle\Entity\Table $assignedTable)
    {
        $this->assignedTable[] = $assignedTable;

        return $this;
    }

    /**
     * Remove assignedTable
     *
     * @param \AppBundle\Entity\Table $assignedTable
     */
    public function removeAssignedTable(\AppBundle\Entity\Table $assignedTable)
    {
        $this->assignedTable->removeElement($assignedTable);
    }
}
