<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 25/04/2018
 * Time: 14:12
 */

include "../init.php";

redirectIfNotConnecte();
$user = unserialize($_SESSION["user"]);

function upload($index,$destination,$extensions=FALSE)
{
    //Test1: fichier correctement uploadé
    if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return FALSE;
    //Test3: extension
    $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
    if ($extensions !== FALSE AND !in_array($ext,$extensions)) return FALSE;
    //Déplacement
    return move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
}

if (isset($_POST) && !empty($_POST)){

    $name = htmlspecialchars(trim($_POST["name"]));
    $comission = htmlspecialchars(trim($_POST["comission"]));
    $type = htmlspecialchars(trim($_POST["order-type"]));
    $description = htmlspecialchars(trim($_POST["description"]));
    $contrat_file = $_FILES["file"];

    $fileName = time() . "_" . $contrat_file["name"];
    if (upload("file", "../img/" . $fileName, array("PDF", "pdf"))){

        $reqAjout = $bdd->prepare("INSERT INTO contract_virgin (business_id, order_type_id, value, description, contract_url, name) VALUES
        (:busId, :type, :com, :desc, :url, :name);");
        $reqAjout->bindValue(":busId", $user["business_id"], PDO::PARAM_INT);
        $reqAjout->bindValue(":type", $type, PDO::PARAM_INT);
        $reqAjout->bindValue(":com", $comission, PDO::PARAM_STR);
        $reqAjout->bindValue(":desc", $description, PDO::PARAM_STR);
        $reqAjout->bindValue(":url", $fileName, PDO::PARAM_STR);
        $reqAjout->bindValue(":name", $name, PDO::PARAM_STR);
        if ($reqAjout->execute())
            header("Location: ../contrats.php");
        else
            echo "Une erreur s'est produite";

    }else{
        echo "Le fichier n'a pas pu etre transféré";
    }
}