<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 17/10/2017
 * Time: 13:33
 */

namespace AppBundle\Entity\Utils;


use Symfony\Component\HttpFoundation\Response;

class Globals
{
    public static $URL_SRC_IMG = "http://resto.connectmyresto.com/assets/img";

    public static function errNotFound($message = "Ressource not found"){
        return \FOS\RestBundle\View\View::create(['message' => $message], Response::HTTP_NOT_FOUND);
    }

    public static function errForbidden($message = "Ressource not found"){
        return \FOS\RestBundle\View\View::create(['message' => $message], Response::HTTP_FORBIDDEN);
    }

    public static function formaterPrix($prix){
        $prix_tab = explode(".", $prix);
        if (strlen($prix_tab[0]) == 1)
            $prix = "0".$prix;
        if (isset($prix_tab[1])) {
            if (strlen($prix_tab[1]) == 1)
                $prix .= "0";
        }else{
            $prix .= ".00";
        }
        return $prix;
    }

}