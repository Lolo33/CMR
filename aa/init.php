<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/04/2018
 * Time: 13:15
 */
session_start();
require "Autoloader.php";

$bdd = Config::getBddInstance();

function getStatusLabel($status){
    if ($status == null)
        return '<span class="label label-danger">Pas de contrat</span>';
    else if ($status == 1)
        return '<span class="label label-danger">Refusé</span>';
    else if ($status == 2)
        return '<span class="label label-primary">En attente</span>';
    else if ($status == 3)
        return '<span class="label label-success">Accepté</span>';
}

function redirectIfConnecte(){
    if (isset($_SESSION["user"]))
        header("Location: dashboard.php");
}

function msg($message, $status = 0){
    if ($status == 1) {
        $class = "success";
        $pre = "Bravo";
    }else {
        $class = "danger";
        $pre = "Erreur";
    }
    echo "<div class=\"alert alert-dismissible alert-".$class."\">
      <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
      <strong>".$pre." :</strong> ".$message."
    </div>";
}

function RandomString($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString . "=";
}

function redirectIfNotConnecte(){
    if (!isset($_SESSION["user"]))
        header("Location: /index.php");
}