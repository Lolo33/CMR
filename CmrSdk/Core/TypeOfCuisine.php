<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 30/06/2018
 * Time: 13:23
 */

namespace CmrSdk\Core;

class TypeOfCuisine implements CoreClassInterface
{

    private $id;
    private $name;

    public function serializeProperties()
    {
        // TODO: Implement serializeProperties() method.
    }

    public function iterateProperties()
    {
        return get_object_vars($this);
    }

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
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}