<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 30/06/2018
 * Time: 10:22
 */

namespace CmrSdk\Actions;

use CmrSdk\Core\Restaurant\Restaurant;
use CmrSdk\Routes;

class ActionsRestaurantDelivery extends Actions
{

    public function __construct()
    {
        parent::__construct();
        $this->objet = new Restaurant();
        $this->url .= Routes::URL_RESTAURANT;
    }

    public function Get($id)
    {
        $url_base = $this->url;
        $this->url .= "/" . $id . "/delivery";
        $rep = $this->convertJsonToPHP($this->objet, $this->sendGetRequest());
        $this->url = $url_base;
        return $rep;
    }

    public function GetRestaurantsDeliveringTOWN($countryCode, $ville){
        $url_base = $this->url;
        $ville = str_replace(" ", "+", $ville);
        $this->url .= "/delivery/ville=" . $countryCode . "-" . $ville;
        $restaurants = $this->getAllRestaurants();
        $this->url = $url_base;
        return $restaurants;
    }

    public function GetRestaurantsDeliveringINSEE($insee)
    {
        $url_base = $this->url;
        $this->url .= "/delivery/insee=" . $insee;
        $restaurants = $this->getAllRestaurants();
        $this->url = $url_base;
        return $restaurants;
    }

    private function getAllRestaurants(){
        $array_obj_restaurant = [];
        $rep = json_decode($this->sendGetRequest());
        if (is_array($rep)){
            foreach ($rep as $item){
                $array_obj_restaurant[] = $this->convertJsonToPHP($this->objet, json_encode($item));
            }
        }
        return $array_obj_restaurant;
    }

}