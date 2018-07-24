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
                        $api_key = RandomString(20);
                        $password = password_hash($pass, PASSWORD_BCRYPT);
                        $req_user = $bdd->prepare("INSERT INTO api_user (id, user_client_id, user_password, business_id) VALUES (:id, :username, :pass, :busId)");
                        $req_user->bindValue(":id", $idUser, PDO::PARAM_INT);
                        $req_user->bindValue(":busId", $idBus, PDO::PARAM_INT);
                        $req_user->bindValue(":username", $username, PDO::PARAM_STR);
                        $req_user->bindValue(":pass", $password, PDO::PARAM_STR);
                        if ($req_user->execute()){
                            $req_token = $bdd->prepare("INSERT INTO user_token (api_user_id, value, createdAt) VALUES (:idUser, :val, NOW())");
                            $req_token->bindValue(":idUser", $idUser, PDO::PARAM_INT);
                            $req_token->bindValue(":val", $api_key, PDO::PARAM_STR);
                            if ($req_token->execute()){
                                $req_conn = $bdd->prepare("SELECT * FROM api_user WHERE id = :idUser");
                                $req_conn->bindValue(":idUser", $idUser, PDO::PARAM_INT);
                                $req_conn->execute();
                                $user = $req_conn->fetch();


                                // CREATION RESTO TEST
                                $bdd->beginTransaction();
                                $reqLastIdResto = $bdd->query("SELECT MAX(id) FROM restaurant");
                                $reqLastIdResto->execute();
                                $maxIdResto = $reqLastIdResto->fetchColumn();
                                $idResto = $maxIdResto + 1;
                                $reqLastIdResto->closeCursor();
                                $reqRestaurant = $bdd->exec("INSERT INTO restaurant (
                                                                    id,
                                                                    status_id, 
                                                                    currency_id, 
                                                                    name, 
                                                                    adress_line1, 
                                                                    active, 
                                                                    latitude, 
                                                                    longitude, 
                                                                    phone, 
                                                                    mail, 
                                                                    description,
                                                                    is_logged,
                                                                    is_seen,
                                                                    cost_estimed,
                                                                    town_id
                                                                ) VALUES (
                                                                    ".$idResto.",
                                                                    1, 
                                                                    1, 
                                                                    'resto-" . $idResto . "', 
                                                                    '33 rue des mirabelles', 
                                                                    1, 
                                                                    44.952920700000000, 
                                                                    -0.547558799999933, 
                                                                    '0781463808',
                                                                    'damien@connectmyresto.com',
                                                                    'Un joli restaurant bien situé. Très calme, ce lieu est un endroit idéal pour les amoureux de la nature.',
                                                                    0,
                                                                    1,
                                                                    2,
                                                                    9011
                                                                 )");

                                $reqIdUser = $bdd->query("SELECT MAX(id) FROM restaurant_user");
                                $reqIdUser->execute();
                                $idUser = $reqIdUser->fetchColumn() + 1;
                                $reqRestauUser = $bdd->exec("INSERT INTO restaurant_user 
                                  (id, rank_id, restaurant_id, username, username_canonical, email, email_canonical, enabled, password, roles, is_card_valid, default_redirection_id, is_info_valid, is_schedule_valid, is_partner_valid, is_payment_valid, is_order_valid, is_town_valid)
                                  VALUES 
                                  (".$idUser.", 2, ".$idResto.", '".$username."', '".$username."', '".$mail."', '".$mail."', 1, '".$password."', 'a:0:{}', 1,1,1,1,1,1,1,1)");

                                // Ajout types de cuisine
                                $reqTypeCuisine = $bdd->exec("INSERT INTO `restaurant_type_of_cuisine` 
                                    (`restaurant_id`, `type_of_cuisine_id`) 
                                    VALUES
                                    (".$idResto.",1),
                                    (".$idResto.",2),
                                    (".$idResto.",3);");

                                $reqPaymentModes = $bdd->exec("INSERT INTO `restaurant_payment_mode` 
                                    (`restaurant_id`, `payment_mode_id`, `order_type_id`) 
                                      VALUES
                                    (".$idResto.", 1, 3),
                                    (".$idResto.", 2, 3),
                                    (".$idResto.", 3, 3),
                                    (".$idResto.", 5, 3);");

                                // Ajout
                                $reqOrderType = $bdd->exec("INSERT INTO restaurant_order_type 
                                (restaurant_id, order_type_id)
                                VALUES 
                                (".$idResto.", 3)");

                                // Ajout horaires
                                $reqSchedules = $bdd->exec("INSERT INTO `schedule_delivery` 
                                  (`restaurant_id`, `day_opening`, `hour_opening`, `day_closing`, `hour_closing`, `hour_status`) 
                                  VALUES
                                    (".$idResto.", 2, '10:30:00', 2, '15:00:00', NULL),
                                    (".$idResto.", 1, '10:30:00', 1, '17:55:00', NULL),
                                    (".$idResto.", 1, '18:00:00', 1, '23:00:00', NULL),
                                    (".$idResto.", 4, '18:00:00', 4, '23:00:00', NULL),
                                    (".$idResto.", 3, '11:30:00', 3, '14:30:00', NULL);");

                                // Ajout contrat virgin
                                $reqIdContractVirg = $bdd->query("SELECT MAX(id) FROM contract_virgin;");
                                $reqIdContractVirg->execute();
                                $idContratVirg = $reqIdContractVirg->fetchColumn() + 1;
                                $reqVirginContract = $bdd->exec("INSERT INTO contract_virgin 
                                  (id, business_id, order_type_id, value, description, contract_url, name) 
                                    VALUES
                                  (".$idContratVirg.", ".$idBus.", 3, \"14%\", \"contrat d'exemple pour la livraison par une société\", '1529580071_2018020182917.pdf', 'Contrat exemple type livraison par un tiers');");

                                // Ajout contract
                                $reqIdContract = $bdd->query("SELECT MAX(id) FROM contract_virgin;");
                                $reqIdContract->execute();
                                $idContrat = $reqIdContract->fetchColumn() + 1;
                                $reqContract = $bdd->exec("INSERT INTO contract 
                                  (id, status_id, restaurant_id, contract_id, start_validity, end_validity) 
                                    VALUES 
                                  (".$idContrat.", 4, ". $idResto . ", ".$idContratVirg.", NOW(), '2079-06-06')");

                                // Ajout periode
                                $reqContractPeriod = $bdd->exec("INSERT INTO `contract_period` 
                                    (`contract_id`, `activationDate`, `endDate`) 
                                      VALUES
                                    (".$idContrat.", NOW(), '2079-06-06 00:00:00');");

                                // Carte depuis Tiller
                                $reqGetCard = new Requetes("https://developers.tillersystems.com/api/inventory?restaurant_token=cc2d47ef-7f96-11e8-9cbd-068e95e7820a&provider_token=cc2d27e7-7f96-11e8-9cbd-068e95e7820a", array("Authorization : "));
                                try {
                                    $repGetCard = json_decode($reqGetCard->sendGetRequest());
                                    foreach ($repGetCard->extraCategories as $extraCategory) {
                                        // Ajout categories d'options
                                        $reqIdPropCat = $bdd->query("SELECT MAX(id) FROM option_group;");
                                        $reqIdPropCat->execute();
                                        $idPropCat = $reqIdPropCat->fetchColumn() + 1;
                                        $reqPropCat = $bdd->exec("INSERT INTO `option_group` 
                                        (`id`, `restaurant_id`, `opt_grp_name`, `opt_grp_is_active`, `is_unique`, `opt_grp_tiller_id`) 
                                        VALUES
                                        (" . $idPropCat . ", " . $idResto . ", '" . $extraCategory->name . "', 1, 0, '" . $extraCategory->id . "');");
                                        // ajout options
                                        foreach ($extraCategory->products as $product) {
                                            $price = $product->price / 100;
                                            $reqProperty = $bdd->exec("INSERT INTO `options` 
                                            (`option_group_id`, `opt_name`, `opt_price`, `opt_is_active`, `opt_is_sold_out`, `opt_tiller_id`) 
                                            VALUES
                                            (" . $idPropCat . ", '" . $product->name . "', " . $price . ", 0, 0, '" . $product->id . "');");
                                        }
                                    }
                                    foreach ($repGetCard->categories as $category) {
                                        // Ajout catégories
                                        if ($category->terminalDescription != null)
                                            $desc = $category->terminalDescription;
                                        else
                                            $desc = $category->name;
                                        $reqIdCat = $bdd->query("SELECT MAX(id) FROM product_category");
                                        $reqIdCat->execute();
                                        $idCat = $reqIdCat->fetchColumn() + 1;
                                        $reqCateg = $bdd->exec("INSERT INTO `product_category` 
                                        (`id`,`restaurant_id`, `cat_position`, `cat_name`, `cat_description`, `cat_is_active`, `cat_tactill_id`, `cat_tiller_id`)
                                        VALUES
                                        (" . $idCat . ", " . $idResto . ", 1, '" . $category->name . "', '" . $desc . "', 1, NULL, '" . $category->id . "');");
                                        foreach ($category->products as $product) {
                                            // Ajout produits
                                            $reqIdProd = $bdd->query("SELECT MAX(id) FROM product");
                                            $reqIdProd->execute();
                                            $idProd = $reqIdProd->fetchColumn() + 1;
                                            $priceProd = $product->price / 100;
                                            $reqProd = $bdd->exec("INSERT INTO `product` 
                                        (`id`, `category_id`, `prod_name`, `prod_description`, `price`, `prod_position`, `vat_id`, `prod_is_take_away_authorized`, `prod_is_active`, `prod_is_sold_out`, `prod_tactill_id`, `prod_tiller_id`) 
                                        VALUES
                                        (" . $idProd . ", " . $idCat . ", '" . $product->name . "', '" . $product->description . "', " . $priceProd . ", 1, 1, 1, 1, 0, NULL, '" . $product->id . "');");
                                            foreach ($product->productExtras as $extra) {
                                                // Ajout ProdProperty
                                                $req_id_prod_prop = $bdd->query("SELECT id FROM option_group WHERE opt_grp_tiller_id = " . $extra->extraCategoryId . " LIMIT 1");
                                                $req_id_prod_prop->execute();
                                                $idProdProp = $req_id_prod_prop->fetchColumn();
                                                $reqPropProd = $bdd->exec("INSERT INTO product_option_group 
                                            (option_group_id, product_id, opt_grp_position)
                                            VALUES
                                            (" . $idProdProp . ", " . $idProd . ", 1)");
                                            }
                                        }
                                    }
                                    $bdd->commit();

                                    /// envoi mail
                                    $to      = $mail;
                                    $subject = 'CONNECT MY RESTO - Mes identifiants';
                                    $message = '<h1>CONNECTMYRESTO</h1><hr>
                                        Bienvenue sur ConnectMyResto, voici les identifiants g&eacute;n&eacute;r&eacute;s pour votre test de notre solution :
                                        <br /><br /><hr>
                                        <strong><h5>Votre compte API de test:</h5></strong><br />
                                        <a href="http://marketplace.test.connectmyresto.com">Acc&eacute;der a l\'espace marketplace</a><br />
                                        Identifiant de connexion : '.$username.',<br />
                                        Mot de passe : le mot de passe saisi lors de la cr&eacute;ation du compte API.<br />
                                        URL de l\'API : http://api.test.connectmyresto.com<br />
                                        Cl&eacute; d\'API : '.$api_key.'<br />
                                        <a href="http://marketplace.test.connectmyresto.com">Acc&eacute;der a la documentation API</a>
                                        <br /><br /><hr>
                                        <strong><h5>Votre restaurant de test:</h5></strong><br />
                                        <a href="http://resto.test.connectmyresto.com">Acc&eacute;der a l\'espace restaurateur</a><br />
                                        Identifiant du restaurant : ' . $idResto . '<br />
                                        Identifiant de connexion : '.$username.',<br />
                                        Mot de passe : le meme que celui que vous avez saisi pour le compte.';
                                    $headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
                                        'From: ConnectMyResto <damien@connectmyresto.com>' . "\r\n" .
                                        'Reply-To: ConnectMyResto <damien@connectmyresto.com>' . "\r\n" .
                                        'X-Mailer: PHP/' . phpversion();
                                    mail($to, $subject, $message, $headers);
                                }catch(ReponseException $ex){
                                    msg("erreur http: " . $ex->getMessage() . "<br />" . $ex->getReponse());
                                }



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
    }else {
        msg("Certains champs obligatoires sont vides");
    }

}else{
    msg("formulaire non soumis");
}