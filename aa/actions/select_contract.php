<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 24/04/2018
 * Time: 11:18
 */

include "../init.php";
redirectIfNotConnecte();
$user = unserialize($_SESSION["user"]);

if (isset($_POST["idContrat"]) && !empty($_POST["idContrat"])) {

    $idContrat = htmlspecialchars(trim($_POST["idContrat"]));
    $dateDebut = htmlspecialchars(trim($_POST["dateDebut"]));
    $dateFin = htmlspecialchars(trim($_POST["dateFin"]));

    foreach ($_GET["restau"] as $resto) {
        $resto = htmlspecialchars(trim($resto));
        $reqAjoutContrat = $bdd->prepare("INSERT INTO contract (status_id, restaurant_id, contract_id, start_validity, end_validity) VALUES (1, :idRes, :idContrat, :dateDebut, :dateFin)");
        $reqAjoutContrat->bindValue(":idRes", $resto, PDO::PARAM_INT);
        $reqAjoutContrat->bindValue(":idContrat", $idContrat, PDO::PARAM_INT);
        $reqAjoutContrat->bindValue(":dateDebut", $dateDebut, PDO::PARAM_STR);
        $reqAjoutContrat->bindValue(":dateFin", $dateFin, PDO::PARAM_STR);
        $reqAjoutContrat->execute();
    }

    header("Location: ../restaurants.php" . $restau);

}