<?php

require "init.php";
redirectIfNotConnecte();
$user = unserialize($_SESSION["user"]);

$reqAllType = $bdd->query("SELECT * FROM order_type;");
$reqAllType->execute();
$types = $reqAllType->fetchAll();

?>

<html>
<head>
    <meta charset="UTF-8" />
    <title>Mes contrats</title>
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
            <li class="nav-item ">
                <a class="nav-link" href="restaurants.php">
                    <span class="flaticon-house-outline"></span>Les restaurants de CMR
                </a>
            </li>
        </ul>
        <ul class="nav navbar-nav">
            <li class="nav-item active">
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
                    <li><a class="dropdown-item" href="actions/deconnexion.php">Se déconnecter</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="container grand-margin-bot grand-margin-top">

    <div class="titre-page">
        <h1>Gestion des contrats</h1>
    </div>

    <section class="section">
        <div class="titre-sect petit-margin-top">
            <div class="row">
                <div class="col-lg-11">
                    <h3>Ajouter un contrat</h3>
                </div>
                <div class="col-lg-1">
                    <h3>
                        <button class="btn-grand" data-toggle="collapse" data-target="#section-ajout">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                    </h3>
                </div>
            </div>
        </div>
        <div class="contenu-section in" id="section-ajout">
            <form method="post" action="actions/add_contract.php" enctype="multipart/form-data">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="form-group">
                            <input class="form-control" name="name" placeholder="Nom du contrat">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <input class="form-control" name="comission" placeholder="Montant de la comission">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <select class="form-control" name="order-type">
                                <?php foreach ($types as $type){ ?>
                                    <option value="<?php echo $type['id']; ?>"><?php echo $type['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group">
                            <textarea name="description" placeholder="Description du contrat" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="contrat-file">Fichier pdf du contrat</label>
                            <input id="contract-file" type="file" name="file">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group zone-btn">
                            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Ajouter ce contrat</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="section">
        <div class="titre-sect petit-margin-top">
            <div class="row">
                <div class="col-lg-11">
                    <h3>Mes contrats par type</h3>
                </div>
                <div class="col-lg-1">
                    <h3>
                        <button class="btn-grand" data-toggle="collapse" data-target="#section-voir">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                    </h3>
                </div>
            </div>
        </div>
        <div class="contenu-section in" id="section-voir">
            <?php $i=0; foreach ($types as $type){
                $reqContractPerso = $bdd->prepare("SELECT * FROM contract_virgin WHERE order_type_id = :id AND business_id = :busId");
                $reqContractPerso->bindValue(":busId", $user["business_id"], PDO::PARAM_INT);
                $reqContractPerso->bindValue(":id", $type["id"], PDO::PARAM_INT);
                $reqContractPerso->execute();
                if ($i % 2 != 0){ echo '<div class="row">'; }
                ?>
                <div class="col-lg-6 petit-margin-top">
                    <h4  class="titre-color petit-padding"><?php echo $type["name"]; ?> (<?php echo $reqContractPerso->rowCount(); ?>)</h4>
                    <?php if ($reqContractPerso->rowCount() > 0) {
                        while ($contract = $reqContractPerso->fetch()) {
                            $reqCount = $bdd->prepare("SELECT COUNT(DISTINCT(restaurant_id)) FROM contract INNER JOIN contract_virgin ON contract.contract_id = contract_virgin.id WHERE contract_id = :id AND status_id != 3 AND business_id = :busId;");
                            $reqCount->bindValue(":busId", $user["business_id"], PDO::PARAM_INT);
                            $reqCount->bindValue(":id", $contract[0], PDO::PARAM_INT);
                            $reqCount->execute();
                            $count = $reqCount->fetchColumn();?>
                            <div class="cont-restau">
                                <h5><?php echo $contract["name"] ?></h5>
                                Comission : <?php echo $contract['value']; ?> %<br />
                                <strong><?php echo $count; ?></strong> restaurant(s) concerné(s)
                            </div>
                        <?php }
                    }else{?>
                        Vous n'avez pas encore de contrat pour ce type
                    <?php } ?>
                </div>
                <?php if ($i % 2 != 0){ echo '</div>'; } ?>
            <?php $i++; } ?>
        </div>
    </section>

</div>

</body>
</html>