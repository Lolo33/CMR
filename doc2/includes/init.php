<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 28/08/2017
 * Time: 23:53
 */

require "database.php";

session_start();

//$db = getConnexion();

function estConnecte() {
    if (!empty($_SESSION))
        return true;
    else
        return false;
}

function activeMenuIfContain($chaine){
    $url = $_SERVER["REQUEST_URI"];
    if (stripos($url, $chaine))
        echo 'class="active"';
    else
        echo "";
}

function messageForm($message, $success = false){
    if ($success) {
        $class = "alert-success";
        $intro = "Bien joué";
    }else {
        $class = "alert-danger";
        $intro = "Erreur";
    }
    echo '<div class="alert alert-dismissible '.$class.'">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>'.$intro.'! </strong> '.$message.'
    </div>';
}

?>