<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 26/04/2018
 * Time: 15:46
 */
include "../init.php";

// dpts
$reqSelect = $bdd->query("SELECT * FROM departements");
$reqSelect->execute();
while ($dpt = $reqSelect->fetch()) {
    var_dump($dpt["code"]);
    $reqUpdate5car = $bdd->prepare("UPDATE delivery_town SET departement_id = :dpt_id WHERE Code_commune_INSEE LIKE :code;'");
    $reqUpdate5car->bindValue(":dpt_id", $dpt["id"], PDO::PARAM_INT);
    $code = $dpt["code"] . '%';
    var_dump($code);
    $reqUpdate5car->bindValue(":code", $code, PDO::PARAM_STR);
    $reqUpdate5car->execute();
    $reqUpdate5car->closeCursor();
    /*$reqUpdate4car = $bdd->prepare("UPDATE delivery_town SET departement_id = :dpt_id WHERE Code_postal LIKE :code2 AND LENGTH(Code_postal) = 4;'");
    $reqUpdate4car->bindValue(":dpt_id", $dpt["id"], PDO::PARAM_INT);
    $code2 = substr($dpt["code"], 1).'%';
    var_dump($dpt["id"]);
    var_dump($code2);
    echo "<br /><br />";
    $reqUpdate4car->bindValue(":code2", $code2, PDO::PARAM_STR);
    $reqUpdate4car->execute();
    $reqUpdate4car->closeCursor();*/
}