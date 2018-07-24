<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 30/06/2018
 * Time: 11:04
 */

namespace CmrSdk\Core;


class Town implements CoreClassInterface
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
    private $codeINSEE;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $countryCode;

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
    public function getCodeINSEE()
    {
        return $this->codeINSEE;
    }
    /**
     * @param string $codeINSEE
     */
    public function setCodeINSEE($codeINSEE)
    {
        $this->codeINSEE = $codeINSEE;
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
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
    /**
     * @param string $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }




}