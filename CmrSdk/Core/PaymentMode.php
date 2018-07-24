<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 01/07/2018
 * Time: 00:05
 */

namespace CmrSdk\Core;


class PaymentMode implements CoreClassInterface
{

    public function iterateProperties()
    {
        return get_object_vars($this);
    }

    public function serializeProperties()
    {
        // TODO: Implement serializeProperties() method.
    }

    private $id;
    private $modeName;
    private $modeCode;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getModeName()
    {
        return $this->modeName;
    }

    /**
     * @param mixed $modeName
     */
    public function setModeName($modeName)
    {
        $this->modeName = $modeName;
    }

    /**
     * @return mixed
     */
    public function getModeCode()
    {
        return $this->modeCode;
    }

    /**
     * @param mixed $modeCode
     */
    public function setModeCode($modeCode)
    {
        $this->modeCode = $modeCode;
    }



}