<?php
/**
 * Created by PhpStorm.
 * User: Loïc Sicaire
 * Date: 29/08/2017
 * Time: 12:02
 */

use CmbSdk\ClassesMetiers\UtilisateurApi;
use CmbSdk\Autoloader;

include '../includes/init.php';
require "../CmbSdk/Autoloader.php";

$userApi = new UtilisateurApi();

if (empty($_SESSION)) {
    if (!empty($_POST)) {
        if (isset($_POST["client_id"]) && isset($_POST["pass"])) {
            $client_id = htmlspecialchars(trim($_POST["client_id"]));
            $pass = htmlspecialchars(trim($_POST["pass"]));
            if (!empty($client_id) && !empty($pass)) {
                $req = $db->prepare("SELECT * FROM utilisateur_api INNER JOIN coordonnee ON coordonnee_id = coordonnee.id WHERE user_client_id = :client_id");
                $req->bindValue(":client_id", $client_id, PDO::PARAM_STR);
                $req->execute();
                $client = $req->fetch();
                $userApi->setUserClientId($client["user_client_id"]);
                $userApi->setUserPlainPassword($client["user_password"]);
                if (!empty($client)){
                    if (password_verify($pass, $userApi->getUserPlainPassword())){
                        messageForm("Vous êtes à présent connecté! Heureux de vous revoir, " . $userApi->getUserClientId(), true);

                        $token = new \CmbSdk\ClassesMetiers\UtilisateurToken();
                        $userApi->setNomSociete($client["user_client_id"]);
                        $userApi->setId($client[0]);
                        $coordonnees = new \CmbSdk\ClassesMetiers\Coordonnee();
                        $coordonnees->setId($client["id"]);
                        $coordonnees->setAdresseLigne1($client["adresse_ligne1"]);
                        $coordonnees->setAdresseLigne2($client["adresse_ligne2"]);
                        $coordonnees->setVille($client["ville"]);
                        $coordonnees->setCodePostal($client["code_postal"]);
                        $coordonnees->setMail($client["mail"]);
                        $coordonnees->setTelephone($client["telephone"]);
                        $userApi->setCoordonnee($coordonnees);

                        $req = $db->prepare("SELECT * FROM utilisateur_token INNER JOIN utilisateur_api ON api_user_id = utilisateur_api.id WHERE user_client_id = :client_id LIMIT 1");
                        $req->bindValue(":client_id", $client_id, PDO::PARAM_STR);
                        $req->execute();
                        $token_db = $req->fetch();

                        $token->setId($token_db[0]);
                        $token->setValue($token_db["value"]);
                        $token->setCreatedAt($token_db["createdAt"]);
                        $token->setApiUser($userApi);

                        $_SESSION["token"] = serialize($token);
                        echo "<script>document.location.reload();</script>";
                    }else{
                        messageForm("Vos identifiant/mot de passe ne correspondent pas.");
                    }
                }else{
                    messageForm("Cet identifiant client n'existe pas.");
                }
            } else {
                messageForm("Vous devez remplir tous les champs.");
            }
        }
    }
}