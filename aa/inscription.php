<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/05/2018
 * Time: 16:18
 */

include "init.php";
redirectIfConnecte();

?>

<html>
<head>
    <meta charset="UTF-8" />
    <title>Mon compte ConnectMyResto</title>
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link rel="icon" href="favicon.ico">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
    </style>
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
                <a class="nav-link" href="dashboard.php">
                    <span class="flaticon-house-outline"></span>Mon compte
                </a>
            </li>
        </ul>
        <ul class="nav navbar-nav">
            <li class="nav-item">
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
                    Compte
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a class="dropdown-item" href="inscription.php">
                            Inscription
                        </a></li>
                    <li><a class="dropdown-item" href="index.php">
                            Connexion
                        </a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="container grand-margin-bot grand-margin-top">



</div>

</body>
</html>


