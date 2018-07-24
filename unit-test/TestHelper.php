<?php

/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 06/06/2018
 * Time: 01:23
 */
class TestHelper
{

    static function testErreur($code, $url){
        try {
            $requete = new Requetes($url, TestConst::API_KEY_LRQL);
            $rep = json_decode($requete->sendGetRequest());
        }catch (ReponseException $ex){
            if ($ex->getCode() == $code)
                return true;
        }
        return false;
    }

}