<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Count;

/**
 * Town
 *
 * @ORM\Table(name="delivery_town")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeliveryTownRepository")
 */
class Town
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
     * @ORM\Column(name="Code_commune_INSEE", type="string", length=5)
     */
    private $codeINSEE;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom_Commune", type="string", length=38)
     */
    private $name;

    /**
     * @var Count
     *
     * @ORM\ManyToOne(targetEntity="Country")
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="Code_postal", type="integer", length=5)
     */
    private $countryCode;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="DeliveryFee", mappedBy="deliveryTown")
     */
    private $deliveryFees;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Departement", inversedBy="townsList")
     */
    private $departement;

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
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return Town
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->restaurants = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add restaurant
     *
     * @param \AppBundle\Entity\Restaurant $restaurant
     *
     * @return Town
     */
    public function addRestaurant(\AppBundle\Entity\Restaurant $restaurant)
    {
        $this->restaurants[] = $restaurant;

        return $this;
    }

    /**
     * Remove restaurant
     *
     * @param \AppBundle\Entity\Restaurant $restaurant
     */
    public function removeRestaurant(\AppBundle\Entity\Restaurant $restaurant)
    {
        $this->restaurants->removeElement($restaurant);
    }

    /**
     * Get restaurants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRestaurants()
    {
        return $this->restaurants;
    }

    /**
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     *
     * @return Town
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
     * Set codeINSEE
     *
     * @param string $codeINSEE
     *
     * @return Town
     */
    public function setCodeINSEE($codeINSEE)
    {
        $this->codeINSEE = $codeINSEE;

        return $this;
    }

    /**
     * Get codeINSEE
     *
     * @return string
     */
    public function getCodeINSEE()
    {
        return $this->codeINSEE;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Town
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
     * Add deliveryFee
     *
     * @param \AppBundle\Entity\DeliveryFee $deliveryFee
     *
     * @return Town
     */
    public function addDeliveryFee(\AppBundle\Entity\DeliveryFee $deliveryFee)
    {
        $this->deliveryFees[] = $deliveryFee;

        return $this;
    }

    /**
     * Remove deliveryFee
     *
     * @param \AppBundle\Entity\DeliveryFee $deliveryFee
     */
    public function removeDeliveryFee(\AppBundle\Entity\DeliveryFee $deliveryFee)
    {
        $this->deliveryFees->removeElement($deliveryFee);
    }

    /**
     * Get deliveryFees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeliveryFees()
    {
        return $this->deliveryFees;
    }

    /**
     * Set departement
     *
     * @param \AppBundle\Entity\Departement $departement
     *
     * @return Town
     */
    public function setDepartement(\AppBundle\Entity\Departement $departement = null)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return \AppBundle\Entity\Departement
     */
    public function getDepartement()
    {
        return $this->departement;
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
