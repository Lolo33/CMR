<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Departement
 *
 * @ORM\Table(name="departements")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DepartementRepository")
 */
class Departement
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $name;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="departementsList")
     */
    private $country;

    private $nbRestaurants;

    private $nbRestaurantsAccepted;

    /**
     * @var Country
     *
     * @ORM\OneToMany(targetEntity="Town", mappedBy="departement")
     */
    private $townsList;


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
     * Set code
     *
     * @param string $code
     *
     * @return Departement
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
     * Set name
     *
     * @param string $name
     *
     * @return Departement
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
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     *
     * @return Departement
     */
    public function setCountry(\AppBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \AppBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->townsList = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add townsList
     *
     * @param \AppBundle\Entity\Town $townsList
     *
     * @return Departement
     */
    public function addTownsList(\AppBundle\Entity\Town $townsList)
    {
        $this->townsList[] = $townsList;

        return $this;
    }

    /**
     * Remove townsList
     *
     * @param \AppBundle\Entity\Town $townsList
     */
    public function removeTownsList(\AppBundle\Entity\Town $townsList)
    {
        $this->townsList->removeElement($townsList);
    }



    /**
     * Get townsList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTownsList()
    {
        return $this->townsList;
    }

    /**
     * Get townsList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setTownsList($townsList)
    {
        $this->townsList = $townsList;
        return $this;
    }
}
