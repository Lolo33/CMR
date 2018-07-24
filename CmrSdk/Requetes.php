<?php

/**
 * Created by PhpStorm.
 * User: Loïc Sicaire
 * Date: 01/08/2017
 * Time: 14:06
 */

namespace CmrSdk;

use CmrSdk\Exceptions\ResponseException;

class Requetes
{
    protected $url;
    protected $header;

    private function getInitConfig(){
        $infos = json_decode(file_get_contents("init.json"));
        return $infos;
    }

    public function __construct()
    {
        $infos = $this->getInitConfig();
        var_dump($infos);
        if ($infos->mode == "prod")
            $this->url = $infos->url_prod;
        elseif ($infos->mode == "test")
            $this->url = $infos->url_test;
        else
            $this->url = $infos->url_local;
        $this->header = array("Authorisation: ".$infos->api_key);
    }

    /**
     * Envoie une requête de type POST à l'url de l'instance, puis retourne le résultat au format JSON
     * @param array $content
     * @return mixed
     * @throws \Exception                levée si la requête produit une erreur (réultat FALSE)
     */
    protected function sendPostRequest($content)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $array_user_json = json_decode($result);

        if (FALSE === $result  || ($httpCode != 201 && $httpCode != 200) || isset($array_user_json->message)){
            if (isset($array_user_json->message))
                $message = $array_user_json->message;
            else
                $message = "Erreur de réponse HTTP";
            throw new ResponseException($message, $httpCode);
        }

        curl_close($curl);
        return $result;
    }

    protected function sendGetRequest(){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $array_user_json = json_decode($result);
        if (FALSE === $result  || ($httpCode != 200 && $httpCode != 201) || isset($array_user_json->message)){
            if (isset($array_user_json->message))
                $message = $array_user_json->message;
            else
                $message = "Erreur de réponse HTTP";
            throw new ResponseException($message, $httpCode);
        }

        curl_close($curl);
        return $result;
    }

    protected function sendDeleteRequest(){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $array_user_json = json_decode($result);
        if (FALSE === $result  || ($httpCode != 200 && $httpCode != 201 && $httpCode != 204) || isset($array_user_json->message)){
            if (isset($array_user_json->message))
                $message = $array_user_json->message;
            else
                $message = "Erreur de réponse HTTP";
            throw new ResponseException($message, $httpCode);
        }

        curl_close($curl);

        return $httpCode;
    }

}



