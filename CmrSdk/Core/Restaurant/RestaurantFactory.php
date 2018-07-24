<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 30/06/2018
 * Time: 10:03
 */

namespace CmrSdk\Core\Restaurant;


class RestaurantFactory
{

    public static function getRestaurant($class_name){
        return new $class_name();
    }



}