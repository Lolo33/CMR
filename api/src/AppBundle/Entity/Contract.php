<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contract
 *
 * @ORM\Table(name="contract")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ComissionsRepository")
 */
class Contract
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
     * @var Restaurant
     *
     * @ORM\ManyToOne(targetEntity="Restaurant")
     */
    private $restaurant;

    /**
     * @var ContractStatus
     *
     * @ORM\ManyToOne(targetEntity="ContractStatus")
     */
    private $status;

    /**
     * @var ContractVirgin
     *
     * @ORM\ManyToOne(targetEntity="ContractVirgin", inversedBy="realContracts")
     */
    private $contract;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_validity", type="datetime")
     */
    private $startValidity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_validity", type="datetime")
     */
    private $endValidity;

    /**
     * @var ContractPeriod[]
     *
     * @ORM\OneToMany(targetEntity="ContractPeriod", mappedBy="contract")
     */
    private $periods;


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
     * @param \AppBundle\Entity\Restaurant $restaurant
     *
     * @return Contract
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

    /**
     * Set status
     *
     * @param \AppBundle\Entity\ContractStatus $status
     *
     * @return Contract
     */
    public function setStatus(\AppBundle\Entity\ContractStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \AppBundle\Entity\ContractStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set startValidity
     *
     * @param \DateTime $startValidity
     *
     * @return Contract
     */
    public function setStartValidity($startValidity)
    {
        $this->startValidity = $startValidity;

        return $this;
    }

    /**
     * Get startValidity
     *
     * @return \DateTime
     */
    public function getStartValidity()
    {
        return $this->startValidity;
    }

    /**
     * Set endValidity
     *
     * @param \DateTime $endValidity
     *
     * @return Contract
     */
    public function setEndValidity($endValidity)
    {
        $this->endValidity = $endValidity;

        return $this;
    }

    /**
     * Get endValidity
     *
     * @return \DateTime
     */
    public function getEndValidity()
    {
        return $this->endValidity;
    }

    /**
     * Set contract
     *
     * @param \AppBundle\Entity\ContractVirgin $contract
     *
     * @return Contract
     */
    public function setContract(\AppBundle\Entity\ContractVirgin $contract = null)
    {
        $this->contract = $contract;

        return $this;
    }

    /**
     * Get contract
     *
     * @return \AppBundle\Entity\ContractVirgin
     */
    public function getContract()
    {
        return $this->contract;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->periods = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add period
     *
     * @param \AppBundle\Entity\ContractPeriod $period
     *
     * @return Contract
     */
    public function addPeriod(\AppBundle\Entity\ContractPeriod $period)
    {
        $this->periods[] = $period;

        return $this;
    }

    /**
     * Remove period
     *
     * @param \AppBundle\Entity\ContractPeriod $period
     */
    public function removePeriod(\AppBundle\Entity\ContractPeriod $period)
    {
        $this->periods->removeElement($period);
    }

    /**
     * Get periods
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriods()
    {
        return $this->periods;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Contract
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
}
