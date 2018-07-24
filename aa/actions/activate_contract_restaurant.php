<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 25/04/2018
 * Time: 13:06
 */

include "../init.php";

redirectIfNotConnecte();
$user = unserialize($_SESSION["user"]);

if (isset($_GET["id"]) && !empty($_GET["id"]) && isset($_GET["restau"]) && !empty($_GET["restau"])){
    $idRestau = htmlspecialchars(trim($_GET["restau"]));
    $idContrat = htmlspecialchars(trim($_GET["id"]));

    $reqDelete = $bdd->prepare("UPDATE contract SET status_id = 1 WHERE id = :idCont");
    $reqDelete->bindValue(":idCont", $idContrat, PDO::PARAM_INT);
    if ($reqDelete->execute()){
        header("Location: ../restaurant.php?id=" . $idRestau);
    }else{
        echo "Ce contrat n'existe pas ou vous n'avez pas les droits pour le supprimer.";
    }
}