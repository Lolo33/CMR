<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/05/2018
 * Time: 16:31
 */

include "../init.php";
include "../stripe.php";

if (isset($_POST) && !empty($_POST)){

    $busName = htmlspecialchars(trim($_POST["business_name"]));
    $username = htmlspecialchars(trim($_POST["username"]));
    $mail = htmlspecialchars(trim($_POST["mail"]));
    $pass = htmlspecialchars(trim($_POST["pass"]));
    $confirm = htmlspecialchars(trim($_POST["pass_confirm"]));
    $adr1 = htmlspecialchars(trim($_POST["adresse_1"]));
    $adr2 = htmlspecialchars(trim($_POST["adresse_2"]));
    $cp = htmlspecialchars(trim($_POST["cp"]));
    $ville = htmlspecialchars(trim($_POST["ville"]));

    if (!empty($busName) && !empty($username) && !empty($mail) && !empty($pass) && !empty($confirm) && !empty($adr1) && !empty($cp) && !empty($ville)){
        $req_double = $bdd->prepare("SELECT * FROM business WHERE mail = :mail OR name = :name");
        $req_double->bindValue(":mail", $mail, PDO::PARAM_STR);
        $req_double->bindValue(":name", $busName, PDO::PARAM_STR);
        $req_double->execute();
        if ($req_double->rowCount() == 0){
            if (strlen($pass) >= 6) {
                if ($pass == $confirm) {

                    $req_id_bus = $bdd->query("SELECT MAX(id) FROM business");
                    $req_id_bus->execute();
                    $idBus = $req_id_bus->fetchColumn() + 1;

                    $req_bus = $bdd->prepare("INSERT INTO business (id, status_id, currency_id, name, adress_line1, adress_line2, state, region, mail, solde, stripe_id) VALUES (:id, 1,1,:name, :adr1, :adr2, :cp, :ville, :mail, 0, '')");
                    $req_bus->bindValue(":id", $idBus, PDO::PARAM_STR);
                    $req_bus->bindValue(":name", $busName, PDO::PARAM_STR);
                    $req_bus->bindValue(":adr1", $adr1, PDO::PARAM_STR);
                    $req_bus->bindValue(":adr2", $adr2, PDO::PARAM_STR);
                    $req_bus->bindValue(":cp", $cp, PDO::PARAM_STR);
                    $req_bus->bindValue(":ville", $ville, PDO::PARAM_STR);
                    $req_bus->bindValue(":mail", $mail, PDO::PARAM_STR);
                    if ($req_bus->execute()){
                        $user_stripe = create_stripe_profil($idBus, $mail);

                        $req_id_user = $bdd->query("SELECT MAX(id) FROM api_user");
                        $req_id_user->execute();
                        $idUser = $req_id_user->fetchColumn() +1;

                        $req_user = $bdd->prepare("INSERT INTO api_user (id, user_client_id, user_password, business_id) VALUES (:id, :username, :pass, :busId)");
                        $req_user->bindValue(":id", $idUser, PDO::PARAM_INT);
                        $req_user->bindValue(":busId", $idBus, PDO::PARAM_INT);
                        $req_user->bindValue(":username", $username, PDO::PARAM_STR);
                        $req_user->bindValue(":pass", password_hash($pass, PASSWORD_BCRYPT), PDO::PARAM_STR);
                        if ($req_user->execute()){
                            $req_token = $bdd->prepare("INSERT INTO user_token (api_user_id, value, createdAt) VALUES (:idUser, :val, NOW())");
                            $req_token->bindValue(":idUser", $idUser, PDO::PARAM_INT);
                            $req_token->bindValue(":val", "ziogjeozijizeojf", PDO::PARAM_STR);
                            if ($req_token->execute()){
                                $req_conn = $bdd->prepare("SELECT * FROM api_user WHERE id = :idUser");
                                $req_conn->bindValue(":idUser", $idUser, PDO::PARAM_INT);
                                $req_conn->execute();
                                $user = $req_conn->fetch();
                                $_SESSION["user"] = serialize($user);
                                echo "ok";
                            }
                        }else{
                            msg("erreur lors de la création du compte API");
                        }
                    }else{
                        msg('erreur lors de la création du compte entreprise');
                    }
                }else{
                    msg("Les mots de passes saisis ne correspondent pas");
                }
            }else{
                msg("Votre mot de passe doit contenir au minimum 6 caractères");
            }
        }else{
            msg("Cet identifiant, cette adresse mail ou ce nom de société existe déja");
        }
    }else{
        msg("Certains champs obligatoires sont vides");
    }

}else{
    msg("formulaire non soumis");
}