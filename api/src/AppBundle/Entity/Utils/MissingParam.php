<?php

namespace AppBundle\Entity\Utils;

/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 14/03/2018
 * Time: 13:03
 */
class MissingParam
{

    private $code;
    private $text;
    private $params = [];

    public function __construct($reference, $text)
    {
        $this->code = $reference;
        $this->text = $text;
    }


    /**
     * Add missingParam
     *
     * @param string
     *
     * @return MissingParam
     */
    public function addParam($param)
    {
        $this->params[] = $param;

        return $this;
    }

    /**
     * Remove missingParams
     *
     * @param string
     */
    public function removeParam($param)
    {
        if (count($this->params) > 0) {
            foreach ($this->params as $k => $paramMissing) {
                if ($paramMissing == $param) {
                    unset($this->params[$k]);
                }
            }
            $this->missingParams = array_values($this->params);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }


    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

}