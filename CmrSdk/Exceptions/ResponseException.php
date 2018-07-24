<?php
/**
 * Created by PhpStorm.
 * User: Loïc Sicaire
 * Date: 30/08/2017
 * Time: 03:07
 */

namespace CmrSdk\Exceptions;


use Throwable;

class ResponseException extends \Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->code = $code;
    }

    public function getReponse()
    {
        switch ($this->code)
        {
            case 404:
                return "Resource not found";
                break;
            case 403:
                return "Ressource forbidden";
                break;
            case 400:
                return "Invalid Request";
                break;
            case 430:
                return "";
            case 500:
                return "Internal Error";
                break;
            default:
                return "Unrecognized response, see getMessage() method to have more informations.";
        }
    }

}