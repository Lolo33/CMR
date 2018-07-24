<?php

require "init.php";
redirectIfNotConnecte();
$user = unserialize($_SESSION["user"]);

if (!isset($_GET["id"]) || empty($_GET["id"]))
    header("Location: restaurants.php");

$id = htmlspecialchars(trim($_GET["id"]));
$req = $bdd->prepare("SELECT * FROM restaurant LEFT OUTER JOIN contract ON contract.restaurant_id = restaurant.id WHERE restaurant.id = :id");
$req->bindValue(":id", $id, PDO::PARAM_INT);
$req->execute();

$restau = $req->fetch();

$reqAllType = $bdd->query("SELECT * FROM order_type;");
$reqAllType->execute();
$types = $reqAllType->fetchAll();

$reqAllStat = $bdd->query("SELECT * FROM order_status");
$reqAllStat->execute();

?>

<html>
<head>
    <meta charset="UTF-8" />
    <title><?php echo $restau["name"]; ?></title>
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link rel="icon" href="favicon.ico">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-default" style="margin-bottom: 0;">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">ConnectMyResto</a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <span class="flaticon-house-outline"></span>Mon compte
                </a>
            </li>
        </ul>
        <ul class="nav navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="restaurants.php">
                    <span class="flaticon-house-outline"></span>Les restaurants de CMR
                </a>
            </li>
        </ul>
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="contrats.php">
                    <span class="flaticon-house-outline"></span>Mes contrats
                </a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    <span class="flaticon-businessman-outline"></span>
                    <?php echo $user["user_client_id"]; ?>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a class="dropdown-item" href="actions/deconnexion.php">
                            Se déconnecter
                        </a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="container grand-margin-top grand-margin-bot">

    <?php
    $reqOrder = $bdd->prepare("SELECT * FROM `order` WHERE order.business_id = :idBusi AND order.restaurant_id = :id");
    $reqOrder->bindValue(":id", $id, PDO::PARAM_INT);
    $reqOrder->bindValue(":idBusi", $user["business_id"], PDO::PARAM_INT);
    $reqOrder->execute(); ?>
    <div class="titre-page">
        <div class="row">
            <div class="col-lg-6">
                <h1><?php echo $restau["name"]; ?></h1>
            </div>
            <div class="col-lg-2">

            </div>
            <div class="col-lg-4 text-right">
                <h1><?php echo $reqOrder->rowCount(); ?> Commande(s)</h1>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="titre-sect petit-margin-top">
            <div class="row">
                <div class="col-lg-11">
                    <h3>Les contrats de ce restaurant</h3>
                </div>
                <div class="col-lg-1">
                    <h3>
                        <button class="btn-grand" data-toggle="collapse" data-target="#section-contrats">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                    </h3>
                </div>
            </div>
        </div>
        <div class="contenu-section in" id="section-contrats">
            <?php foreach ($types as $type){ ?>
                <div class="row">
                    <div class="col-lg-12 petit-margin-top">
                        <h4 class="titre-color petit-padding"><?php echo $type["name"]; ?></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <?php
                        $reqContrat = $bdd->prepare("SELECT * FROM contract INNER JOIN contract_virgin ON contract.contract_id = contract_virgin.id WHERE order_type_id = :typeId AND restaurant_id = :idRestau AND business_id = :busId");
                        $reqContrat->bindValue(":typeId", $type["id"], PDO::PARAM_INT);
                        $reqContrat->bindValue(":idRestau", $id, PDO::PARAM_INT);
                        $reqContrat->bindValue(":busId", $user["business_id"], PDO::PARAM_INT);
                        $reqContrat->execute();
                        if ($reqContrat->rowCount() > 0) {
                            while ($contrat = $reqContrat->fetch()) {
                                $periodes = $bdd->prepare("SELECT * FROM contract_period WHERE contract_id = :id AND activationDate <= NOW() AND endDate >= NOW()");
                                $periodes->bindValue(":id", $contrat[0], PDO::PARAM_INT);
                                $periodes->execute(); ?>
                                <div class="cont-restau border" <?php if ($contrat['status_id'] == 3) {
                                    echo 'style="background: #f0dfd8;"';
                                } elseif ($periodes->rowCount() > 0) {
                                    echo 'style="background: #daf2da;"';
                                } else {
                                    echo 'style="background: white;"';
                                } ?>>
                                    <div class="row">
                                        <div class="col-lg-11">
                                            <h4><?php echo $contrat["name"]; ?></h4>
                                        </div>
                                        <div class="col-lg-1">
                                            <?php if ($contrat['status_id'] != 3) { ?>
                                                <span onclick="document.location.replace('actions/delete_contract_restaurant?id=<?php echo $contrat[0]; ?>&restau=<?php echo $id; ?>');"
                                                      class="clickable glyphicon glyphicon-ban-circle text-danger"
                                                      title="Désactiver ce contrat"></span>
                                            <?php } else { ?>
                                                <span onclick="document.location.replace('actions/activate_contract_restaurant?id=<?php echo $contrat[0]; ?>&restau=<?php echo $id; ?>');"
                                                      class="clickable glyphicon glyphicon-ok-circle text-success"
                                                      title="Réactiver ce contrat"></span>
                                            <?php } ?>
                                        </div>

                                        <div class="col-lg-12">
                                            Valide
                                            du <?php echo (new DateTime($contrat["start_validity"]))->format("d-m-Y"); ?>
                                            au <?php echo (new DateTime($contrat["end_validity"]))->format("d-m-Y"); ?>
                                        </div>
                                    </div>
                                    <?php
                                    $allSign = $bdd->prepare("SELECT * FROM contract_period WHERE contract_id = :id;");
                                    $allSign->bindValue(":id", $contrat[0], PDO::PARAM_INT);
                                    $allSign->execute();
                                    $k = 0;
                                    while ($sign = $allSign->fetch()) {
                                        if ($k % 2 == 0) { ?><dir class="row"> <?php } ?>
                                        <div class="col-lg-6">
                                            Actif du
                                            : <?php echo (new DateTime($sign["activationDate"]))->format("d-m-Y"); ?>
                                        </div>
                                        <div class="col-lg-6">
                                            <?php if ($sign["endDate"] == null) { ?>
                                                à maintenant
                                            <?php } else { ?>
                                                au : <?php echo (new DateTime($sign["endDate"]))->format("d-m-Y");
                                            } ?>
                                        </div>
                                        <?php if ($k % 2 != 0) { ?></dir> <?php } ?>
                                        <?php $k++;
                                    } ?>
                                </div>
                            <?php }
                        }else{ ?>
                            Aucun contrat actuellement proposé pour ce type
                        <?php } ?>
                    </div>
                    <div class="col-lg-6" style="border-left: 1px solid lightslategrey;">
                        <?php $reqContractPerso = $bdd->prepare("SELECT * FROM contract_virgin WHERE order_type_id = :id AND business_id = :busId");
                        $reqContractPerso->bindValue(":busId", $user["business_id"], PDO::PARAM_INT);
                        $reqContractPerso->bindValue(":id", $type["id"], PDO::PARAM_INT);
                        $reqContractPerso->execute();
                        if ($reqContractPerso->rowCount() > 0) { ?>
                            <form method="post" action="actions/select_contract.php?type=<?php echo $type['id']; ?>&restau[]=<?php echo $id; ?>">
                                <label for="select-contrat">Les contrats disponibles pour ce type</label>
                                <select class="form-control" id="select-contrat" name="idContrat">
                            <?php while ($contratPers = $reqContractPerso->fetch()) { ?>
                                    <option value="<?php echo $contratPers['id']; ?>"><?php echo $contratPers["name"]; ?></option>
                            <?php } ?>
                                </select>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="dateDebut">Valide du</label>
                                        <input type="date" class="form-control" name="dateDebut" id="dateDebut" placeholder="date de début de validité">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="dateFin">au</label>
                                        <input type="date" class="form-control" name="dateFin" id="dateFin" placeholder="date de fin de validité">
                                    </div>
                                </div>

                                <div class="zone-btn petit-margin-top">
                                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> Selectionner ce contrat</button>
                                </div>
                            </form>
                        <?php }else{ ?>
                            Pas de contrat à proposer pour ce restaurant
                            <div class="zone-btn petit-margin-top">
                                <a href="contrats.php"><button class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Ajouter des contrats</button></a>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            <?php } ?>
        </div>
    </section>




    <section class="section">
        <div class="titre-sect petit-margin-top">
            <div class="row">
                <div class="col-lg-11">
                    <h3>Les commandes du restaurant</h3>
                </div>
                <div class="col-lg-1">
                    <h3>
                        <button class="btn-grand" data-toggle="collapse" data-target="#section-commandes">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                    </h3>
                </div>
            </div>
        </div>
        <div class="contenu-section in" id="section-commandes">
            <h4 class="titre-color petit-margin-top petit-padding" style="margin-bottom: 0;">Tri des commandes</h4>
            <div class="cont-restau">
                <div class="row padding-form">
                    <div class="col-lg-6">
                        <label for="select-stat">Trier par statut :</label>
                        <select class="form-control" id="select-stat">
                            <option value="all">Toutes les commandes</option>
                            <?php while ($stat = $reqAllStat->fetch()){?>
                                <option value="<?php echo $stat['name']; ?>"><?php echo $stat['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <hr>

            <div id="commandes" class="petit-margin-top">
                <?php if ($reqOrder->rowCount() > 0) {
                    while ($order = $reqOrder->fetch()) {
                        $reqStat = $bdd->prepare("SELECT * FROM order_status_hour INNER JOIN order_status ON status_id = order_status.id WHERE order_id = :idOrder AND current = 1;");
                        $reqStat->bindValue(":idOrder", $order[0], PDO::PARAM_INT);
                        $reqStat->execute();
                        $statut = $reqStat->fetch(); ?>
                        <div class="order cont-restau" statut="<?php echo $statut['name']; ?>">
                            <h3>Commande <?php echo $order["reference"]; ?></h3>
                            <div class="row">
                                <div class="col-lg-3">
                                    Comission : <?php echo $order["amount_taken_by_buisness"]; ?> €
                                </div>
                                <div class="col-lg-3">
                                    Date : <?php echo $order["hourToBeReady"]; ?>
                                </div>
                                <div class="col-lg-3">
                                    <?php echo $statut["name"]; ?>
                                </div>
                            </div>
                        </div>
                    <?php }
                }else{ ?>
                    Ce restaurant n'a pas passé de commandes sur votre canal pour le moment.
                <?php } ?>
            </div>
        </div>
    </section>


    <script>
        $("#select-stat").change(function () {
            var stat = $(this).val();
            var isEmpty = true;
            if (stat == "all")
                $(".order").show();
            else {
                $(".order").hide();
                $("[statut=" + stat + "]").show();
            }
            if ($("[statut=" + stat + "]").length == 0){
                console.log("lol");
                $("#commandes").text("Ce restaurant n'a pas passé de commandes sur votre canal pour ce statut.");
            }
        })

    </script>

</div>



</body>
</html>