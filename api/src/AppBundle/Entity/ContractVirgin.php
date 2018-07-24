<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContractVirgin
 *
 * @ORM\Table(name="contract_virgin")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VirginContractRepository")
 */
class ContractVirgin
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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var Business
     *
     * @ORM\ManyToOne(targetEntity="Business")
     */
    private $business;

    /**
     * @var OrderType
     *
     * @ORM\ManyToOne(targetEntity="OrderType")
     */
    private $orderType;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="contract_url", type="string", length=255)
     */
    private $contractUrl;

    /**
     * @var Contract[]
     *
     * @ORM\OneToMany(targetEntity="Contract", mappedBy="contract")
     */
    private $realContracts;

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
     * Set value
     *
     * @param string $value
     *
     * @return ContractVirgin
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ContractVirgin
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set contractUrl
     *
     * @param string $contractUrl
     *
     * @return ContractVirgin
     */
    public function setContractUrl($contractUrl)
    {
        $this->contractUrl = $contractUrl;

        return $this;
    }

    /**
     * Get contractUrl
     *
     * @return string
     */
    public function getContractUrl()
    {
        return $this->contractUrl;
    }

    /**
     * Set business
     *
     * @param \AppBundle\Entity\Business $business
     *
     * @return ContractVirgin
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
     * Set orderType
     *
     * @param \AppBundle\Entity\OrderType $orderType
     *
     * @return ContractVirgin
     */
    public function setOrderType(\AppBundle\Entity\OrderType $orderType = null)
    {
        $this->orderType = $orderType;

        return $this;
    }

    /**
     * Get orderType
     *
     * @return \AppBundle\Entity\OrderType
     */
    public function getOrderType()
    {
        return $this->orderType;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->realContracts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add realContract
     *
     * @param \AppBundle\Entity\Contract $realContract
     *
     * @return ContractVirgin
     */
    public function addRealContract(\AppBundle\Entity\Contract $realContract)
    {
        $this->realContracts[] = $realContract;

        return $this;
    }

    /**
     * Remove realContract
     *
     * @param \AppBundle\Entity\Contract $realContract
     */
    public function removeRealContract(\AppBundle\Entity\Contract $realContract)
    {
        $this->realContracts->removeElement($realContract);
    }

    /**
     * Get realContracts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRealContracts()
    {
        return $this->realContracts;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ContractVirgin
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
