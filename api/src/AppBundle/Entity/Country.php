<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountryRepository")
 */
class Country
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=5)
     */
    private $code;

    /**
     * @var Restaurant[]
     *
     * @ORM\OneToMany(targetEntity="Departement", mappedBy="country")
     */
    private $departementsList;

    private $nbRestaurants;

    private $nbRestaurantsAccepted;

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
     * Set name
     *
     * @param string $name
     *
     * @return Country
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

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Country
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->restaurantsList = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add departementsList
     *
     * @param \AppBundle\Entity\Departement $departementsList
     *
     * @return Country
     */
    public function addDepartementsList(\AppBundle\Entity\Departement $departementsList)
    {
        $this->departementsList[] = $departementsList;

        return $this;
    }

    /**
     * Remove departementsList
     *
     * @param \AppBundle\Entity\Departement $departementsList
     */
    public function removeDepartementsList(\AppBundle\Entity\Departement $departementsList)
    {
        $this->departementsList->removeElement($departementsList);
    }

    /**
     * Get departementsList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDepartementsList()
    {
        return $this->departementsList;
    }

    /**
     * Get departementsList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setDepartementsList($dptList)
    {
        $this->departementsList = $dptList;
        return $this;
    }

    /**
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     *
     * @return Departement
     */
    public function setNbRestaurants($nbRestaurants)
    {
        $this->nbRestaurants = $nbRestaurants;

        return $this;
    }

    /**
     * Get country
     *
     * @return \AppBundle\Entity\Country
     */
    public function getNbRestaurants()
    {
        return $this->nbRestaurants;
    }

    /**
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     *
     * @return Departement
     */
    public function setNbRestaurantsAccepted($nbRestaurants)
    {
        $this->nbRestaurantsAccepted = $nbRestaurants;

        return $this;
    }

    /**
     * Get country
     *
     * @return \AppBundle\Entity\Country
     */
    public function getNbRestaurantsAccepted()
    {
        return $this->nbRestaurantsAccepted;
    }

}
