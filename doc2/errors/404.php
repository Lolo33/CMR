<?php
include "../includes/init.php";
include "../CmbSdk/Autoloader.php";

\CmbSdk\Autoloader::registerRacine();
$token = new \CmbSdk\ClassesMetiers\UtilisateurToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="../css/bootstrap.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="../img/favicon.png" />
    <title>Erreur 404 [Cmb-Api] : Cette page n'existe pas ou plus</title>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-default" style="margin: 0 0 0 20%;">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../index">Cmb-Api</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="../debuter">Débuter</a></li>
                <li><a href="../complexes">Documentation </a></li>
            </ul>
            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Saisir un mot clé...">
                </div>
                <button type="submit" class="btn btn-default">Rechercher</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <?php if (estConnecte()){
                    $token = unserialize($_SESSION["token"]);
                ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <span class="glyphicon glyphicon-user"></span> <?php echo $token->getApiUser()->getUserClientId(); ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="../api_gestion">Mon Compte</a></li>
                        <li class="divider"></li>
                        <li><a href="../api_deconnexion">Déconnexion</a></li>
                    </ul>
                </li>
                <?php }else{ ?>
                <li><a href="../api_connexion"><span class="glyphicon glyphicon-user"></span>  S'identifier</a> </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <div class="col-lg-3 nav-tool">
            <h2 class="titre-menu">Navigation</h2>
            <?php $url_site = "/api/"; ?>
            <h4 class="nav-titre">Général</h4>
            <ul class="nav nav-pills nav-stacked">
                <li <?php
                if ($url_site == $_SERVER["REQUEST_URI"])
                    echo 'class="active"';
                else
                    activeMenuIfContain("index"); ?>
                ><a href="../index.php">Accueil</a></li>

                <?php if (estConnecte()) { ?>
                    <li <?php activeMenuIfContain("api_gestion"); ?>><a href="../api_gestion">Tableau de bord</a></li>
                <?php }else{ ?>
                    <li <?php activeMenuIfContain("api_inscription"); ?>><a href="../api_inscription">Obtenir une Clé d'API</a></li>
                    <li <?php activeMenuIfContain("api_connexion"); ?>><a href="../api_connexion">S'identifier</a></li>
                <?php } ?>

                <li><a href="../api_conditions">Conditions d'utilisation</a></li>
            </ul>


            <h4 class="nav-titre">Documentation</h4>
            <ul class="nav nav-pills nav-stacked">
                <li><a href="../debuter">Débuter avec CMB-API</a></li>
                <li><a href="../complexes">Complexes</a></li>
                <li><a href="../coordonnees">Coordonnees</a></li>
                <li><a href="../plages-horaires">PlagesHoraires</a></li>
                <li><a href="../plages-horaires-statut">PlagesHorairesStatuts</a></li>
                <li><a href="../planning-tarifs">PlanningTarifs</a></li>
                <li><a href="../planning-comissions">PlanningComissions</a></li>
                <li><a href="../reservations">Reservations</a></li>
                <li><a href="../terrains">Terrains</a></li>
                <li><a href="../terrains-type">TerrainTypes</a></li>
            </ul>
        </div>

        <!-- Contenu de la page -->
        <div class="col-lg-9 cont-tool">
            <div id="contenu-doc">

                <h1 class="titre-section espace-bot">Erreur 404 : Not Found</h1>

                <div class="espace-top" style="margin: 10px 50px; font-size: 20px;">
                    La page à laquelle vous tentez d'accéder n'existe pas ou plus. Vérifiez l'url saisie dans le navigateur.<br />
                    Si le problème persiste, contactez notre support : support@connectmybooking.com
                </div>


            </div>
        </div>


    </div>
</div>
</body>
</html>
