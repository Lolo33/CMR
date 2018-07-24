<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 06/06/2018
 * Time: 09:52
 */

namespace APITests;


class TestRestaurantsTakeAway extends TestClass
{

    function __construct()
    {
        parent::__construct();
        $this->url .= "/restaurants/take-away";
        $this->nom = "Liste des restaurants à emporter : /restaurants/take-away/{params}";
    }

    function QuandOnSaisisUniquementUneLatitudeErreur400(){
        $url = $this->url . "/lat=44.9529207";
        return \TestHelper::testErreur(400, $url);
    }
    function QuandOnSaisisUniquementUneLongitudeErreur400(){
        $url = $this->url . "/long=0.547558799999933";
        return \TestHelper::testErreur(400, $url);
    }


}