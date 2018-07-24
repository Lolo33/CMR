<?php

/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 06/06/2018
 * Time: 01:09
 */

namespace APITests;

class TestRestaurantsDelivery extends TestClass
{

    public function __construct()
    {
        parent::__construct();
        $this->url .= "/restaurants/delivery";
        $this->nom = "Liste restaurants en livraison : /restaurants/delivery/{params}";
    }

    public function QuandOnSaisisPartenariatInvalideErreur400(){
        $url = $this->url . "/insee=33039&partnership=testechoue";
        return \TestHelper::testErreur(400, $url);
    }
    public function QuandOnSaisisModeDePaiementInvalideErreur400(){
        $url = $this->url . "/insee=33039&paymentmode=testechoue";
        return \TestHelper::testErreur(400, $url);
    }
    public function QuandOnSaisisTypeCuisineInvalideErreur400(){
        $url = $this->url . "/insee=33039&type=testechoue";
        return \TestHelper::testErreur(400, $url);
    }
    function QuandOnSaisisCodePostalEtVilleIncorrectesErreur400() {
        $url = $this->url . "/ville=145222-CENN";
        return \TestHelper::testErreur(400, $url);
    }
    function QuandOnSaisisCodeInseeEtVilleErreur400(){
        $url = $this->url . "/insee=33039&ville=33150-cenon";
        return \TestHelper::testErreur(400, $url);
    }
    function QuandOnSaisisNiInseeNiVilleErreur400(){
        $url = $this->url . "/partnership=all";
        return \TestHelper::testErreur(400, $url);
    }
    function QuandOnSaisisUniquementCodePostalErreur400() {
        $url = $this->url . "/ville=33150";
        return \TestHelper::testErreur(400, $url);
    }
    function QuandOnSaisisUniquementVilleErreur400() {
        $url = $this->url . "/ville=CENON";
        return \TestHelper::testErreur(400, $url);
    }

    function QuandOnSaisisCodePostalCommencantPar0LesRestaurantsLivrentBienCetteVille(){
        $url = $this->url . "/ville=1400-L+ABERGEMENT+CLEMENCIAT";
        try {
            $requete = new \Requetes($url, \TestConst::API_KEY_LRQL);
            $rep = json_decode($requete->sendGetRequest());
            $nbResto = 0;
            $nbRestoValids = 0;
            foreach ($rep as $rest){
                if ($rest->deliveryFees->deliveryTown->id == "111")
                    $nbRestoValids++;
                $nbResto++;
            }
            if ($nbResto == $nbRestoValids)
                return true;
            return false;
        }catch (\ReponseException $ex){
            return false;
        }
    }
    function QuandOnSaisisCodePostalA4ChiffresValideLesRestaurantsLivrentBienCetteVille(){
        $url = $this->url . "/ville=1400-L+ABERGEMENT+CLEMENCIAT";
        try {
            $requete = new \Requetes($url, \TestConst::API_KEY_LRQL);
            $rep = json_decode($requete->sendGetRequest());
            $nbResto = 0;
            $nbRestoValids = 0;
            foreach ($rep as $rest){
                if ($rest->deliveryFees->deliveryTown->id == "111")
                    $nbRestoValids++;
                $nbResto++;
            }
            if ($nbResto == $nbRestoValids)
                return true;
            return false;
        }catch (\ReponseException $ex){
            return false;
        }
    }
    function QuandOnSaisisVilleAvecEspaceSepareParDesPlusLesRestaurantsLivrentBienCetteVille(){
        $url = $this->url . "/ville=10130-MAROLLES+SOUS+LIGNIERES";
        try {
            $requete = new \Requetes($url, \TestConst::API_KEY_LRQL);
            $rep = json_decode($requete->sendGetRequest());
            $nbResto = 0;
            $nbRestoValids = 0;
            if (count($rep) == 0)
                return false;
            foreach ($rep as $rest){
                if ($rest->deliveryFees->deliveryTown->id == "2")
                    $nbRestoValids++;
                $nbResto++;
            }
            if ($nbResto == $nbRestoValids)
                return true;
            return false;
        }catch (\ReponseException $ex){
            return false;
        }
    }
    function QuandOnSaisisCodePostalEtVilleCorrecteLesRestaurantsLivrentBienCetteVille(){
        try {
            $url = $this->url . "/ville=33150-CENON";
            $requete = new \Requetes($url, \TestConst::API_KEY_LRQL);
            $rep = json_decode($requete->sendGetRequest());
            $nbResto = 0;
            $nbRestoValids = 0;
            foreach ($rep as $rest){
                if ($rest->deliveryFees->deliveryTown->id == "3682")
                    $nbRestoValids++;
                $nbResto++;
            }
            if ($nbResto == $nbRestoValids)
                return true;
            return false;
        }catch (\ReponseException $ex){
            return false;
        }
    }
    public function QuandOnSaisisUnCodeInseeValideOnTrouveTousLesRestaurantsLivrantLaZone(){
        $idVille = "3682";
        $url = $this->url . "/ville=33150-CENON";
        $bdd = $this->getBddInstance();
        try {
            $requete = new \Requetes($url, \TestConst::API_KEY_LRQL);
            $rep = json_decode($requete->sendGetRequest());
            $reqCountRestaurantDeliveryTown = $bdd->query("SELECT COUNT(restaurant.id) FROM restaurant INNER JOIN delivery_fee ON delivery_fee.restaurant_id = restaurant.id INNER JOIN delivery_town ON delivery_fee.delivery_town_id = delivery_town.id WHERE delivery_town.id = ".$idVille."");
            $reqCountRestaurantDeliveryTown->execute();
            $nbResto = $reqCountRestaurantDeliveryTown->fetchColumn();
            $nbRestoValids = 0;
            foreach ($rep as $rest){
                if ($rest->deliveryFees->deliveryTown->id == $idVille)
                    $nbRestoValids++;
            }
            if ($nbResto == $nbRestoValids)
                return true;
            return false;
        }catch (\ReponseException $ex){
            return false;
        }
    }

}