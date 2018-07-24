<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 05/06/2018
 * Time: 23:52
 */

include "Autoloader.php";
include "APITests\Autoloader.php";
\APITests\Autoloader::register();
Autoloader::register();


?>

<html>
<head>
    <meta charset="UTF-8" />
    <title>Tests de l'API</title>
    <link href="css/bootstrap.css" rel="stylesheet" />
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="css/style.css" rel="stylesheet" />
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
                <a class="nav-link" href="test_api.php">
                    <span class="glyphicon glyphicon-home"></span> Tests API
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container grand-margin-top grand-margin-bot" style="background: white;">

    <img src="img.jpg" class="img-circle img-responsive" WIDTH="50" height="50" style="border-radius: 50px;"/>

    <table class="table table-hover">
        <thead>
        <tr class="panel-default">
            <th scope="col">Intitulé du test</th>
            <th scope="col">Résultat</th>
            <th scope="col">Date/Heure</th>
            <th scope="col">Temps</th>
        </tr>
        </thead>
        <tbody>
            <?php
                $writer = new TestWriter();
                $writer->write("APITests\TestRestaurantsDelivery");
                $writer->write("APITests\TestRestaurantsTakeAway");
            ?>
        </tbody>
    </table>

</div>
</body>

