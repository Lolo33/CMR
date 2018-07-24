<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/04/2018
 * Time: 12:44
 */

include "../init.php";

if (isset($_POST) && !empty($_POST)){

    $login = htmlspecialchars(trim($_POST["login"]));
    $password = htmlspecialchars(trim($_POST["password"]));

    $req = $bdd->prepare("SELECT * FROM api_user WHERE user_client_id = :login ");
    $req->bindValue(":login", $login, PDO::PARAM_STR);
    $req->execute();

    if ($req->rowCount() == 1){
        $user = $req->fetch();
        if (password_verify($password, $user["user_password"])) {
            $_SESSION["user"] = serialize($user);
            echo 'ok';
        }else{
            msg('Ces informations sont incorrectes');
        }
    }else{
        msg('Ces informations sont incorrectes');
    }

}