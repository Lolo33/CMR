<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 24/04/2018
 * Time: 11:08
 */

include "../init.php";

redirectIfNotConnecte();
$user = unserialize($_SESSION["user"]);

if (isset($_GET["id"]) && !empty($_GET["id"]) && isset($_GET["restau"]) && !empty($_GET["restau"])){

    $idRestau = htmlspecialchars(trim($_GET["restau"]));
    $idContrat = htmlspecialchars(trim($_GET["id"]));
    $reqDelete = $bdd->prepare("UPDATE contract SET status_id = 3 WHERE id = :idCont");
    $reqDeletePeriode = $bdd->prepare("UPDATE contract_period SET endDate = NOW() WHERE contract_id = :idCont AND DATE(endDate) = DATE ((SELECT end_validity FROM contract WHERE id = :idCont))");
    $reqDeletePeriode->bindValue(":idCont", $idContrat, PDO::PARAM_INT);
    $reqDelete->bindValue(":idCont", $idContrat, PDO::PARAM_INT);
    if ($reqDelete->execute() && $reqDeletePeriode->execute()){
        header("Location: ../restaurant.php?id=" . $idRestau);
    }else{
        echo "Ce contrat n'existe pas ou vous n'avez pas les droits pour le supprimer.";
    }

}