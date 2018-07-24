<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 30/06/2018
 * Time: 09:43
 */

use \CmrSdk\Actions\ActionsRestaurantDelivery;
require "Autoloader.php";

\CmrSdk\Autoloader::registerRacine();
\CmrSdk\Autoloader::registerExceptions();

$resto_actions = new ActionsRestaurantDelivery();

echo "<h1>Liste restaurants livraison begles:</h1>";
try{
    $restos = $resto_actions->GetRestaurantsDeliveringINSEE(33039);
    var_dump($restos);
}catch (\CmrSdk\Exceptions\ResponseException $ex){
    echo $ex->getReponse() . ": <br />" . $ex->getMessage();
}

echo "<h1>Restaurant 1 en livraison</h1>";
echo "<br /><br />";
try {
    $resto = $resto_actions->Get(1);
    var_dump($resto);
}catch (\CmrSdk\Exceptions\ResponseException $ex){
    echo $ex->getReponse() . ": <br />" . $ex->getMessage();
}

var_dump(\CmrSdk\Core\Restaurant\Restaurant::class);



