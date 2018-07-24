<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 01/07/2018
 * Time: 10:14
 */

namespace CmrSdk\Core;


class DeliveryFee implements CoreClassInterface
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
     * @var float
     */
    private $minOrder;
    /**
     * @var Town
     */
    private $deliveryTown;
    /**
     * @var integer
     */
    private $deliveryFee;

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
     * @return float
     */
    public function getMinOrder()
    {
        return $this->minOrder;
    }
    /**
     * @param float $minOrder
     */
    public function setMinOrder($minOrder)
    {
        $this->minOrder = $minOrder;
    }

    /**
     * @return Town
     */
    public function getDeliveryTown()
    {
        return $this->deliveryTown;
    }
    /**
     * @param Town $deliveryTown
     */
    public function setDeliveryTown($deliveryTown)
    {
        $this->deliveryTown = $deliveryTown;
    }

    /**
     * @return int
     */
    public function getDeliveryFee()
    {
        return $this->deliveryFee;
    }
    /**
     * @param int $deliveryFee
     */
    public function setDeliveryFee($deliveryFee)
    {
        $this->deliveryFee = $deliveryFee;
    }




}