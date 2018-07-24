<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 06/06/2018
 * Time: 15:07
 */

include "../init.php";

$reqSelect = $bdd->query("SELECT * FROM restaurant");
$reqSelect->execute();
while ($restos = $reqSelect->fetch()){
    $reqInsert = $bdd->prepare("INSERT INTO restaurant_order_type (restaurant_id, order_type_id) VALUES (:id, 1)");
    $reqInsert->bindValue(":id", $restos["id"], PDO::PARAM_INT);
    if ($reqInsert->execute()){
        echo "bien<br />";
    }else{
        echo "false<br />";
    }
}