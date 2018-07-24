<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 30/06/2018
 * Time: 10:06
 */

namespace CmrSdk\Actuib;


class ActionFactory
{

    public static function load($className)
    {
        if (file_exists($chemin = 'Action'.$className . '.php') && $className != 'Factory') {
            require $chemin;
            return new $className;
        } else {
            throw new \RuntimeException('La classe <strong>' . $className . '</strong> n\'a pu être trouvée !');
        }
    }

}