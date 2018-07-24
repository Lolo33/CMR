<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 30/06/2018
 * Time: 11:58
 */

namespace CmrSdk\Exceptions;


use Throwable;

class PermissionException extends \BadMethodCallException
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}