<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Utils\Globals;
use Doctrine\ORM\Mapping as ORM;

/**
 * Restaurant
 *
 * @ORM\Table(name="restaurant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RestaurantRepository")
 */
class Restaurant extends Corporate
{

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="TypeOfCuisine")
     */
    private $type;

    /**
     * @var OrderType
     *
     * @ORM\ManyToMany(targetEntity="OrderType")
     */
    private $orderTypesList;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_logged", type="boolean")
     */
    private $isLogged;

    /**
     * @var bool
     *
     * @ORM\OneToMany(targetEntity="ProductCategory", mappedBy="restaurant")
     */
    private $categoriesOfProducts;

    /**
     * @var bool
     *
     * @ORM\OneToMany(targetEntity="ScheduleBooking", mappedBy="restaurant")
     */
    private $scheduleList;

    /**
     * @var bool
     *
     * @ORM\OneToMany(targetEntity="ScheduleDelivery", mappedBy="restaurant")
     */
    private $scheduleDeliveryList;

    /**
     * @var bool
     *
     * @ORM\OneToMany(targetEntity="ScheduleOrder", mappedBy="restaurant")
     */
    private $scheduleOrderList;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_before_preparation", type="time", options={"default" : "00:15:00"})
     */
    private $timeBeforePreparation;

    /**
     * @var DeliveryFee[]
     *
     * @ORM\OneToMany(targetEntity="DeliveryFee", mappedBy="restaurant")
     */
    private $townsDeliveredList;

    /**
     * @var Town[]
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="restaurant")
     */
    private $ordersList;

    /**
     * @var SupplementCategory[]
     *
     * @ORM\OneToMany(targetEntity="SupplementCategory", mappedBy="restaurant")
     */
    private $supplementGroups;

    /**
     * @var Menu[]
     *
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="restaurant")
     */
    private $menus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="booking_duration", type="time", options={"default" : "01:30:00"})
     */
    private $bookingDuration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_to_delivery", type="time", options={"default" : "01:00:00"})
     */
    private $timeToDelivery;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="margin_delivery", type="time", options={"default" : "00:10:00"})
     */
    private $marginDelivery;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cut_line_duration", type="time", options={"default" : "01:00:00"})
     */
    private $cutLineDuration;

    /**
     * @var double
     *
     * @ORM\Column(name="latitude", type="decimal", precision=19, scale=15, nullable=true)
     */
    private $latitude;

    /**
     * @var double
     *
     * @ORM\Column(name="longitude", type="decimal", precision=19, scale=15, nullable=true)
     */
    private $longitude;

    /**
     * @var Table[]
     *
     * @ORM\OneToMany(targetEntity="Table", mappedBy="restaurant")
     */
    private $tables;

    /**
     * @var double
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @var double
     *
     * @ORM\Column(name="is_seen", type="boolean", options={"default" : false})
     */
    private $firstVisit;

    /**
     * @var RestaurantImage
     *
     * @ORM\ManyToOne(targetEntity="RestaurantImage")
     */
    private $logoUrl;

    /**
     * @var RestaurantImage
     *
     * @ORM\ManyToOne(targetEntity="RestaurantImage")
     */
    private $chiefImg;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Contract[]
     *
     * @ORM\OneToMany(targetEntity="Contract", mappedBy="restaurant")
     */
    private $contractsList;

    /**
     * @var string
     *
     * @ORM\Column(name="iban", type="string", length=30, nullable=true)
     */
    private $iban;

    /**
     * @var string
     *
     * @ORM\Column(name="deliver_himself", type="boolean", nullable=true)
     */
    private $deliverHimself;

    /** @var string
    *
    * @ORM\Column(name="take_away", type="boolean", nullable=true)
    */
    private $takeAway;

    /**
     * @var string
     *
     * @ORM\Column(name="bookable", type="boolean", nullable=true)
     */
    private $bookable;

    /**
     * @var string
     *
     * @ORM\Column(name="cost_estimed", type="integer", options={"default" : 1})
     */
    private $costEstimed;

    /**
     * @var RestaurantPaymentMode[]
     *
     * @ORM\OneToMany(targetEntity="RestaurantPaymentMode", mappedBy="restaurant")
     */
    private $paymentModes;

    private $distanceFromPoint;

    /**
     * @var RestaurantImage
     *
     * @ORM\ManyToMany(targetEntity="RestaurantImage")
     */
    private $imagesList;

    public function paymentModesAccepted(){

    }

    /**
     * @var Town
     *
     * @ORM\ManyToOne(targetEntity="Town")
     */
    private $town;

    /**
     * @var
     *
     * @ORM\Column(type="float", name="solde")
     */
    private $sold;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="stripe_id")
     */
    private $stripeId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="website", nullable=true)
     */
    private $website;

    private $deliveryFees;

    private $realHours;

    private $openStateString;

    private $openState;

    private $weeklyHours;


    public function isOpen() {
        return $this->openState;
    }


    /**
     * Set cutLineDuration
     *
     * @param \DateTime $cutLineDuration
     *
     * @return Restaurant
     */
    public function setDistanceFromPoint($distanceFromPoint)
    {
        $this->distanceFromPoint = $distanceFromPoint;

        return $this;
    }

    /**
     * Get cutLineDuration
     *
     * @return \DateTime
     */
    public function getDistanceFromPoint()
    {
        return $this->distanceFromPoint;
    }

    /**
     * @return mixed
     */
    public function getOpenStateString()
    {
        return $this->openStateString;
    }

    /**
     * @param mixed $openStateString
     */
    public function setOpenStateString($openStateString)
    {
        $this->openStateString = $openStateString;
    }


    /**
     * @return mixed
     */
    public function getRealHours()
    {
        return $this->realHours;
    }

    /**
     * @param mixed $realHours
     */
    public function setRealHours($realHours)
    {
        $this->realHours = $realHours;
    }

    /**
     * @return mixed
     */
    public function getWeeklyHours()
    {
        return $this->weeklyHours;
    }

    /**
     * @param mixed $weeklyHours
     */
    public function setWeeklyHours($weeklyHours)
    {
        $this->weeklyHours = $weeklyHours;
    }


    /**
     * Set type
     *
     * @param string $type
     *
     * @return Restaurant
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Restaurant
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categoriesOfProducts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add categoriesOfProduct
     *
     * @param \AppBundle\Entity\ProductCategory $categoriesOfProduct
     *
     * @return Restaurant
     */
    public function addCategoriesOfProduct(\AppBundle\Entity\ProductCategory $categoriesOfProduct)
    {
        $this->categoriesOfProducts[] = $categoriesOfProduct;

        return $this;
    }

    /**
     * Remove categoriesOfProduct
     *
     * @param \AppBundle\Entity\ProductCategory $categoriesOfProduct
     */
    public function removeCategoriesOfProduct(\AppBundle\Entity\ProductCategory $categoriesOfProduct)
    {
        $this->categoriesOfProducts->removeElement($categoriesOfProduct);
    }

    /**
     * Set categoriesOfProduct
     *
     * @param  $categoriesOfProduct
     */
    public function setCategoriesOfProduct(\Doctrine\Common\Collections\ArrayCollection $categoriesOfProduct)
    {
        $this->categoriesOfProducts = $categoriesOfProduct;
    }
    /**
     * Get categoriesOfProducts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoriesOfProducts()
    {
        return $this->categoriesOfProducts;
    }

    /**
     * Add scheduleList
     *
     * @param \AppBundle\Entity\ScheduleDelivery $scheduleList
     *
     * @return Restaurant
     */
    public function addScheduleList(\AppBundle\Entity\ScheduleDelivery $scheduleList)
    {
        $this->scheduleList[] = $scheduleList;

        return $this;
    }

    /**
     * Remove scheduleList
     *
     * @param \AppBundle\Entity\ScheduleDelivery $scheduleList
     */
    public function removeScheduleList(\AppBundle\Entity\ScheduleDelivery $scheduleList)
    {
        $this->scheduleList->removeElement($scheduleList);
    }

    /**
     * Get scheduleList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScheduleList()
    {
        return $this->scheduleList;
    }

    /**
     * Set timeBeforePreparation
     *
     * @param \DateTime $timeBeforePreparation
     *
     * @return Restaurant
     */
    public function setTimeBeforePreparation($timeBeforePreparation)
    {
        $this->timeBeforePreparation = $timeBeforePreparation;

        return $this;
    }

    /**
     * Get timeBeforePreparation
     *
     * @return \DateTime
     */
    public function getTimeBeforePreparation()
    {
        return $this->timeBeforePreparation->format("H:i:s");
    }

    /**
     * Add townsDeliveredList
     *
     * @param \AppBundle\Entity\Town $townsDeliveredList
     *
     * @return Restaurant
     */
    public function addTownsDeliveredList(\AppBundle\Entity\Town $townsDeliveredList)
    {
        $this->townsDeliveredList[] = $townsDeliveredList;

        return $this;
    }

    /**
     * Remove townsDeliveredList
     *
     * @param \AppBundle\Entity\Town $townsDeliveredList
     */
    public function removeTownsDeliveredList(\AppBundle\Entity\Town $townsDeliveredList)
    {
        $this->townsDeliveredList->removeElement($townsDeliveredList);
    }

    /**
     * Get townsDeliveredList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTownsDeliveredList()
    {
        return $this->townsDeliveredList;
    }

    /**
     * Add ordersList
     *
     * @param \AppBundle\Entity\Order $ordersList
     *
     * @return Restaurant
     */
    public function addOrdersList(\AppBundle\Entity\Order $ordersList)
    {
        $this->ordersList[] = $ordersList;

        return $this;
    }

    /**
     * Remove ordersList
     *
     * @param \AppBundle\Entity\Order $ordersList
     */
    public function removeOrdersList(\AppBundle\Entity\Order $ordersList)
    {
        $this->ordersList->removeElement($ordersList);
    }

    /**
     * Get ordersList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrdersList()
    {
        return $this->ordersList;
    }

    /**
     * Add supplementGroup
     *
     * @param \AppBundle\Entity\SupplementCategory $supplementGroup
     *
     * @return Restaurant
     */
    public function addSupplementGroup(\AppBundle\Entity\SupplementCategory $supplementGroup)
    {
        $this->supplementGroups[] = $supplementGroup;

        return $this;
    }

    /**
     * Remove supplementGroup
     *
     * @param \AppBundle\Entity\SupplementCategory $supplementGroup
     */
    public function removeSupplementGroup(\AppBundle\Entity\SupplementCategory $supplementGroup)
    {
        $this->supplementGroups->removeElement($supplementGroup);
    }

    /**
     * Get supplementGroups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSupplementGroups()
    {
        return $this->supplementGroups;
    }

    /**
     * Add menu
     *
     * @param \AppBundle\Entity\Menu $menu
     *
     * @return Restaurant
     */
    public function addMenu(\AppBundle\Entity\Menu $menu)
    {
        $this->menus[] = $menu;

        return $this;
    }

    /**
     * Remove menu
     *
     * @param \AppBundle\Entity\Menu $menu
     */
    public function removeMenu(\AppBundle\Entity\Menu $menu)
    {
        $this->menus->removeElement($menu);
    }

    /**
     * Get menus
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * Set bookingDuration
     *
     * @param \DateTime $bookingDuration
     *
     * @return Restaurant
     */
    public function setBookingDuration($bookingDuration)
    {
        $this->bookingDuration = $bookingDuration;

        return $this;
    }

    /**
     * Get bookingDuration
     *
     * @return \DateTime
     */
    public function getBookingDuration()
    {
        return $this->bookingDuration->format("H:i:s");
    }

    /**
     * Set cutLineDuration
     *
     * @param \DateTime $cutLineDuration
     *
     * @return Restaurant
     */
    public function setCutLineDuration($cutLineDuration)
    {
        $this->cutLineDuration = $cutLineDuration;

        return $this;
    }

    /**
     * Get cutLineDuration
     *
     * @return \DateTime
     */
    public function getCutLineDuration()
    {
        return $this->cutLineDuration;
    }

    /**
     * Set latitude
     *
     * @param \double $latitude
     *
     * @return Restaurant
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return \double
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param \double $longitude
     *
     * @return Restaurant
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return \double
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Add table
     *
     * @param \AppBundle\Entity\Table $table
     *
     * @return Restaurant
     */
    public function addTable(\AppBundle\Entity\Table $table)
    {
        $this->tables[] = $table;

        return $this;
    }

    /**
     * Remove table
     *
     * @param \AppBundle\Entity\Table $table
     */
    public function removeTable(\AppBundle\Entity\Table $table)
    {
        $this->tables->removeElement($table);
    }

    /**
     * Get tables
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     * Add scheduleDeliveryList
     *
     * @param \AppBundle\Entity\ScheduleDelivery $scheduleDeliveryList
     *
     * @return Restaurant
     */
    public function addScheduleDeliveryList(\AppBundle\Entity\ScheduleDelivery $scheduleDeliveryList)
    {
        $this->scheduleDeliveryList[] = $scheduleDeliveryList;

        return $this;
    }

    /**
     * Remove scheduleDeliveryList
     *
     * @param \AppBundle\Entity\ScheduleDelivery $scheduleDeliveryList
     */
    public function removeScheduleDeliveryList(\AppBundle\Entity\ScheduleDelivery $scheduleDeliveryList)
    {
        $this->scheduleDeliveryList->removeElement($scheduleDeliveryList);
    }

    /**
     * Get scheduleDeliveryList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScheduleDeliveryList()
    {
        return $this->scheduleDeliveryList;
    }

    /**
     * Add scheduleOrderList
     *
     * @param \AppBundle\Entity\ScheduleOrder $scheduleOrderList
     *
     * @return Restaurant
     */
    public function addScheduleOrderList(\AppBundle\Entity\ScheduleOrder $scheduleOrderList)
    {
        $this->scheduleOrderList[] = $scheduleOrderList;

        return $this;
    }

    /**
     * Remove scheduleOrderList
     *
     * @param \AppBundle\Entity\ScheduleOrder $scheduleOrderList
     */
    public function removeScheduleOrderList(\AppBundle\Entity\ScheduleOrder $scheduleOrderList)
    {
        $this->scheduleOrderList->removeElement($scheduleOrderList);
    }

    /**
     * Get scheduleOrderList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScheduleOrderList()
    {
        return $this->scheduleOrderList;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Restaurant
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set timeToDelivery
     *
     * @param \DateTime $timeToDelivery
     *
     * @return Restaurant
     */
    public function setTimeToDelivery($timeToDelivery)
    {
        $this->timeToDelivery = $timeToDelivery;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getDeliveryFees()
    {
        return $this->deliveryFees;
    }

    /**
     * Set timeToDelivery
     *
     * @param \DateTime $timeToDelivery
     *
     * @return Restaurant
     */
    public function setDeliveryFees($deliveryFees)
    {
        $this->deliveryFees = $deliveryFees;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param Town $town
     */
    public function setTown($town)
    {
        $this->town = $town;
    }

    /**
     * Get timeToDelivery
     *
     * @return \DateTime
     */
    public function getTimeToDelivery()
    {
        return $this->timeToDelivery->format("H:i");
    }

    /**
     * Set marginDelivery
     *
     * @param \DateTime $marginDelivery
     *
     * @return Restaurant
     */
    public function setMarginDelivery($marginDelivery)
    {
        $this->marginDelivery = $marginDelivery;

        return $this;
    }

    /**
     * Get marginDelivery
     *
     * @return \DateTime
     */
    public function getMarginDelivery()
    {
        return $this->marginDelivery->format("H:i");
    }

    /**
     * Set logoUrl
     *
     * @param string $logoUrl
     *
     * @return Restaurant
     */
    public function setLogoUrl($logoUrl)
    {
        $this->logoUrl = $logoUrl;

        return $this;
    }

    /**
     * Get logoUrl
     *
     * @return string
     */
    public function getLogoUrl()
    {
        if ($this->logoUrl != null)
            return Globals::$URL_SRC_IMG . "/restaurants/" . $this->getId() . "/" . $this->logoUrl->getUrl();
        else
            return null;
    }

    /**
     * Set logoUrl
     *
     * @param string $logoUrl
     *
     * @return Restaurant
     */
    public function setOpenState($openState)
    {
        $this->openState = $openState;

        return $this;
    }

    /**
     * Get logoUrl
     *
     * @return string
     */
    public function getOpenState()
    {
        return $this->openState;
    }



    /**
     * Add type
     *
     * @param \AppBundle\Entity\TypeOfCuisine $type
     *
     * @return Restaurant
     */
    public function addType(\AppBundle\Entity\TypeOfCuisine $type)
    {
        $this->type[] = $type;

        return $this;
    }

    /**
     * Remove type
     *
     * @param \AppBundle\Entity\TypeOfCuisine $type
     */
    public function removeType(\AppBundle\Entity\TypeOfCuisine $type)
    {
        $this->type->removeElement($type);
    }

    /**
     * Set isLogged
     *
     * @param boolean $isLogged
     *
     * @return Restaurant
     */
    public function setIsLogged($isLogged)
    {
        $this->isLogged = $isLogged;

        return $this;
    }

    /**
     * Get isLogged
     *
     * @return boolean
     */
    public function getIsLogged()
    {
        return $this->isLogged;
    }

    /**
     * Add contractsList
     *
     * @param \AppBundle\Entity\Contract $contractsList
     *
     * @return Restaurant
     */
    public function addContractsList(\AppBundle\Entity\Contract $contractsList)
    {
        $this->contractsList[] = $contractsList;

        return $this;
    }

    /**
     * Remove contractsList
     *
     * @param \AppBundle\Entity\Contract $contractsList
     */
    public function removeContractsList(\AppBundle\Entity\Contract $contractsList)
    {
        $this->contractsList->removeElement($contractsList);
    }

    /**
     * Get contractsList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContractsList()
    {
        return $this->contractsList;
    }

    /**
     * Set chiefImg
     *
     * @param string $chiefImg
     *
     * @return Restaurant
     */
    public function setChiefImg($chiefImg)
    {
        $this->chiefImg = $chiefImg;

        return $this;
    }

    /**
     * Get chiefImg
     *
     * @return string
     */
    public function getChiefImg()
    {
        if ($this->chiefImg != null)
            return Globals::$URL_SRC_IMG . "/restaurants/" . $this->getId() . "/" . $this->chiefImg->getUrl();
        else
            return null;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Restaurant
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
     * Set iban
     *
     * @param string $iban
     *
     * @return Restaurant
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban
     *
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set firstVisit
     *
     * @param boolean $firstVisit
     *
     * @return Restaurant
     */
    public function setFirstVisit($firstVisit)
    {
        $this->firstVisit = $firstVisit;

        return $this;
    }

    /**
     * Get firstVisit
     *
     * @return boolean
     */
    public function getFirstVisit()
    {
        return $this->firstVisit;
    }

    /**
     * Set deliverHimself
     *
     * @param boolean $deliverHimself
     *
     * @return Restaurant
     */
    public function setDeliverHimself($deliverHimself)
    {
        $this->deliverHimself = $deliverHimself;

        return $this;
    }

    /**
     * Get deliverHimself
     *
     * @return boolean
     */
    public function getDeliverHimself()
    {
        return $this->deliverHimself;
    }

    /**
     * Set costEstimed
     *
     * @param integer $costEstimed
     *
     * @return Restaurant
     */
    public function setCostEstimed($costEstimed)
    {
        $this->costEstimed = $costEstimed;

        return $this;
    }

    /**
     * Get costEstimed
     *
     * @return integer
     */
    public function getCostEstimed()
    {
        return $this->costEstimed;
    }

    /**
     * Set takeAway
     *
     * @param boolean $takeAway
     *
     * @return Restaurant
     */
    public function setTakeAway($takeAway)
    {
        $this->takeAway = $takeAway;

        return $this;
    }

    /**
     * Get takeAway
     *
     * @return boolean
     */
    public function getTakeAway()
    {
        return $this->takeAway;
    }

    /**
     * Set bookable
     *
     * @param boolean $bookable
     *
     * @return Restaurant
     */
    public function setBookable($bookable)
    {
        $this->bookable = $bookable;

        return $this;
    }

    /**
     * Get bookable
     *
     * @return boolean
     */
    public function getBookable()
    {
        return $this->bookable;
    }

    /**
     * Set paymentMode
     *
     * @param \AppBundle\Entity\RestaurantPaymentMode $paymentMode
     *
     * @return Restaurant
     */
    public function setPaymentModes($paymentMode)
    {
        $this->paymentModes = $paymentMode;

        return $this;
    }

    /**
     * Add paymentMode
     *
     * @param \AppBundle\Entity\RestaurantPaymentMode $paymentMode
     *
     * @return Restaurant
     */
    public function addPaymentMode($paymentMode)
    {
        $this->paymentModes[] = $paymentMode;

        return $this;
    }

    /**
     * Remove paymentMode
     *
     * @param \AppBundle\Entity\RestaurantPaymentMode $paymentMode
     */
    public function removePaymentMode(\AppBundle\Entity\RestaurantPaymentMode $paymentMode)
    {
        $this->paymentModes->removeElement($paymentMode);
    }

    /**
     * Get paymentModes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPaymentModes()
    {
        return $this->paymentModes;
    }

    /**
     * Add orderTypesList
     *
     * @param \AppBundle\Entity\OrderType $orderTypesList
     *
     * @return Restaurant
     */
    public function addOrderTypesList(\AppBundle\Entity\OrderType $orderTypesList)
    {
        $this->orderTypesList[] = $orderTypesList;

        return $this;
    }

    /**
     * Remove orderTypesList
     *
     * @param \AppBundle\Entity\OrderType $orderTypesList
     */
    public function removeOrderTypesList(\AppBundle\Entity\OrderType $orderTypesList)
    {
        $this->orderTypesList->removeElement($orderTypesList);
    }

    /**
     * Get orderTypesList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderTypesList()
    {
        return $this->orderTypesList;
    }

    /**
     * Add imagesList
     *
     * @param \AppBundle\Entity\RestaurantImage $imagesList
     *
     * @return Restaurant
     */
    public function addImagesList(\AppBundle\Entity\RestaurantImage $imagesList)
    {
        $this->imagesList[] = $imagesList;

        return $this;
    }

    /**
     * Remove imagesList
     *
     * @param \AppBundle\Entity\RestaurantImage $imagesList
     */
    public function removeImagesList(\AppBundle\Entity\RestaurantImage $imagesList)
    {
        $this->imagesList->removeElement($imagesList);
    }

    /**
     * Get imagesList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImagesList()
    {
        $listImg = [];
        foreach ($this->imagesList as $img){
            if ($img != null)
                $listImg[] = Globals::$URL_SRC_IMG . "/restaurants/" . $this->getId() . "/" . $img->getUrl();
        }
        return $listImg;
    }

    /**
     * Set sold
     *
     * @param float $sold
     *
     * @return Restaurant
     */
    public function setSold($sold)
    {
        $this->sold = $sold;

        return $this;
    }

    /**
     * Get sold
     *
     * @return float
     */
    public function getSold()
    {
        return $this->sold;
    }

    /**
     * Set stripeId
     *
     * @param string $stripeId
     *
     * @return Restaurant
     */
    public function setStripeId($stripeId)
    {
        $this->stripeId = $stripeId;

        return $this;
    }

    /**
     * Get stripeId
     *
     * @return string
     */
    public function getStripeId()
    {
        return $this->stripeId;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Restaurant
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }
}
