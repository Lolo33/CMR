<?php

require "init.php";
redirectIfNotConnecte();
$user = unserialize($_SESSION["user"]);

if (!isset($_POST["restau"]))
    header("Location: restaurants.php");

$chaineRestaus = "?";
$restau_nom = [];
foreach ($_POST["restau"] as $restau){
    $chaineRestaus .= "restau[]=" . htmlspecialchars(trim($restau)) . "&";
    $reqNom = $bdd->prepare("SELECT name FROM restaurant WHERE id = :id");
    $reqNom->bindValue(":id", $restau, PDO::PARAM_INT);
    $reqNom->execute();
    $restau_nom[] = $reqNom->fetchColumn();
}
?>

<html>
<head>
    <meta charset="UTF-8" />
    <title>Les restaurants</title>
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

<div class="container grand-margin-top">

    <div class="titre-page">
        <h1>Proposer un contrat à des restaurants</h1>
    </div>

    <section class="section">
        <div class="titre-sect petit-margin-top">
            <div class="row">
                <div class="col-lg-11">
                    <h3>Proposition de contrat</h3>
                </div>
                <div class="col-lg-1">
                    <h3>
                        <button class="btn-grand" data-toggle="collapse" data-target="#section-select">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                    </h3>
                </div>
            </div>
        </div>
        <div class="contenu-section in" id="section-select">

            <h4>Le contrat sélectionné sera proposé aux restaurants suivants :</h4>
            <?php foreach ($restau_nom as $nom){ ?>
                - <?php echo $nom ?>
            <?php } ?>
            <hr>

            <?php
            $reqContractPerso = $bdd->prepare("SELECT * FROM contract_virgin WHERE business_id = :busId");
            $reqContractPerso->bindValue(":busId", $user["business_id"], PDO::PARAM_INT);
            $reqContractPerso->execute(); ?>
            <form method="post" action="actions/select_contract.php<?php echo $chaineRestaus; ?>">
                <label for="select-contrat">Contrat à proposer</label>
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
                    <button type="submit" class="btn btn-success">Selectionner ce contrat</button>
                </div>
            </form>
        </div>
    </section>

</div>

</body>
</html>