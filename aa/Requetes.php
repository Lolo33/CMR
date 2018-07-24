<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 05/12/2017
 * Time: 13:12
 */

class ReponseException extends Exception {

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
                return "Ressource introuvable";
                break;
            case 403:
                return "Ressource interdite";
                break;
            case 400:
                return "Informations invalides";
                break;
            case 500:
                return "Erreur interne.";
                break;
            default:
                return "Réponse Inconnue, consultez la méthode getMessage() de cette exception pour en savoir plus";
        }
    }
}

class Requetes
{
    private $url;
    private $header;

    const API_KEY = "CW3tbH8v/SLvFG8/y0UPIOnenuw=";
    const API_URL = "http://connectmyresto.com/api";
    const API_URL_LOCAL = "http://localhost/cmr/api/web/app_dev.php";

    public function __construct($url, $api_key)
    {
        $this->url = $url;
        $this->header = array("Authorisation: fez");
    }

    /**
     * Envoie une requête de type POST à l'url de l'instance, puis retourne le résultat au format JSON
     * @param array $content
     * @return mixed
     * @throws \Exception                levée si la requête produit une erreur (réultat FALSE)
     */
    public function sendPostRequest($content)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$content);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $array_user_json = json_decode($result);
        if (FALSE === $result || ($httpCode != 201 && $httpCode != 200) || isset($array_user_json->message)) {
            if (isset($array_user_json->message))
                $message = $array_user_json->message;
            else
                $message = "Erreur de réponse HTTP";
            throw new ReponseException($message, $httpCode);
        }

        curl_close($curl);
        return $result;
    }

    public function sendPostRequestJSON($content)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $array_user_json = json_decode($result);
        if (FALSE === $result || ($httpCode != 201 && $httpCode != 200) || isset($array_user_json->message)) {
            if (isset($array_user_json->message))
                $message = $array_user_json->message;
            else
                $message = "Erreur de réponse HTTP";
            throw new ReponseException($message, $httpCode);
        }
        curl_close($curl);
        return $result;
    }

    public function sendGetRequest()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $array_user_json = $result;
        if (FALSE === $result || ($httpCode != 200 && $httpCode != 201) || isset($array_user_json->message)) {
            if (isset($array_user_json->message))
                $message = $array_user_json->message;
            else
                $message = "Erreur de réponse HTTP";
            throw new ReponseException($message, $httpCode);
        }

        curl_close($curl);
        return $result;
    }

    public function sendDeleteRequest()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $array_user_json = json_decode($result);
        if (FALSE === $result || ($httpCode != 200 && $httpCode != 201 && $httpCode != 204) || isset($array_user_json->message)) {
            if (isset($array_user_json->message))
                $message = $array_user_json->message;
            else
                $message = "Erreur de réponse HTTP";
            throw new ReponseException($message, $httpCode);
        }

        curl_close($curl);

        return $httpCode;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param array $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }


}

