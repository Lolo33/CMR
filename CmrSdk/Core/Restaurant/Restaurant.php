<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 30/06/2018
 * Time: 09:50
 */

namespace CmrSdk\Core\Restaurant;

use CmrSdk\Core\CoreClassInterface;
use CmrSdk\Core\Currency;
use CmrSdk\Core\DeliveryFee;
use CmrSdk\Core\PaymentMode;
use CmrSdk\Core\Town;
use CmrSdk\Core\TypeOfCuisine;

class Restaurant implements CoreClassInterface
{

    public function serializeProperties()
    {
        // TODO: Implement serializeProperties() method.
    }

    public function iterateProperties()
    {
        return get_object_vars($this);
    }

    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var boolean
     */
    private $isOpen;
    /**
     * @var string
     */
    private $openStateString;
    /**
     * @var string
     */
    private $logoUrl;
    /**
     * @var string
     */
    private $chiefImgUrl;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $addressLine1;
    /**
     * @var string
     */
    private $addressLine2;
    /**
     * @var Town
     */
    private $town;
    /**
     * @var TypeOfCuisine
     */
    private $type;
    /**
     * @var Currency
     */
    private $currency;
    /**
     * @var PaymentMode[]
     */
    private $paymentModes;


    function __construct()
    {
        $this->town = new Town();
        $this->type = array(new TypeOfCuisine());
        $this->currency = new Currency();
        $this->paymentModes = array(new PaymentMode());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isOpen()
    {
        return $this->isOpen;
    }
    /**
     * @param bool $isOpen
     */
    public function setIsOpen($isOpen)
    {
        $this->isOpen = $isOpen;
    }

    /**
     * @return string
     */
    public function getOpenStateString()
    {
        return $this->openStateString;
    }
    /**
     * @param string $openStateString
     */
    public function setOpenStateString($openStateString)
    {
        $this->openStateString = $openStateString;
    }

    /**
     * @return string
     */
    public function getLogoUrl()
    {
        return $this->logoUrl;
    }
    /**
     * @param string $logoUrl
     */
    public function setLogoUrl($logoUrl)
    {
        $this->logoUrl = $logoUrl;
    }

    /**
     * @return string
     */
    public function getChiefImgUrl()
    {
        return $this->chiefImgUrl;
    }
    /**
     * @param string $chiefImgUrl
     */
    public function setChiefImgUrl($chiefImgUrl)
    {
        $this->chiefImgUrl = $chiefImgUrl;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }
    /**
     * @param string $addressLine1
     */
    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;
    }

    /**
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }
    /**
     * @param string $addressLine2
     */
    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;
    }

    /**
     * @return Town
     */
    public function getTown()
    {
        return $this->town;
    }
    /**
     * @param Town $town
     */
    public function setTown(Town $town)
    {
        $this->town = $town;
    }

    /**
     * @return TypeOfCuisine
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @param TypeOfCuisine $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    /**
     *  @param TypeOfCuisine
     */
    public function addType($type){
        $this->type[] = $type;
    }
    /**
     * Fonction réinitialisant le tableau de type
     */
    public function initType(){
        $this->type = [];
    }

    /**
     * @return PaymentMode[]
     */
    public function getPaymentModes()
    {
        return $this->paymentModes;
    }
    /**
     * @param PaymentMode[] $paymentModes
     */
    public function setPaymentModes($paymentModes)
    {
        $this->paymentModes = $paymentModes;
    }
    /**
     * @param PaymentMode $paymentModes
     */
    public function addPaymentModes($paymentModes){
        $this->paymentModes[] = $paymentModes;
    }
    /**
     * Fonction réinitialisant le tableau de modes de paiement
     */
    public function initPaymentModes(){
        $this->paymentModes = [];
    }


    /**
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }
    /**
     * @param Currency $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }



}