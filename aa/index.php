<?php

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

<div class="container grand-margin-bot">

    <div class="col-lg-4">

    </div>
    <section class="col-lg-4" style="padding: 2%;">

        <h1 class="titre-page text-center" style="border-bottom: 0.5px double #E2BC74; padding: 10px; margin-bottom: 40px;">ConnectMyResto</h1>


        <div class="row">
            <div class="col-lg-12">
                <h4>Connexion à l'espace API</h4>
            </div>
        </div>

        <div class="grand-margin-bot" id="section-conn">
            <div>
                <div>
                    <form id="form-connexion" method="post" action="actions/connect.php">
                        <div id="err-login"></div>
                        <div class="form-group">
                            <input id="conn-login" class="form-control login-form" type="text" name="login" placeholder="Votre nom d'utilisateur / e-mail">
                        </div>
                        <div class="form-group">
                            <input id="conn-password" class="form-control login-form" type="password" name="password" placeholder="Votre mot de passe">
                        </div>
                        <div class="form-group zone-btn">
                            <button class="btn btn-success btn-grand login-form">
                                <span class="glyphicon glyphicon-globe"></span>
                                S'identifier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class=" petit-margin-top">
            <div class="row">
                <div class="col-lg-12">
                    <h3>Je n'ai pas encore de compte</h3>
                </div>
            </div>
        </div>
        <div class="" id="section-inscription">
            <div>
                <form id="form-inscription" method="post">
                    <div id="err-inscription"></div>
                    <div class="row form-group">
                        <div class="col-lg-12">
                            <input id="business_name" class="form-control" placeholder="Nom de ma société" name="business_name"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-6">
                            <input id="username" class="form-control" placeholder="Nom d'utilisateur" name="username"/>
                        </div>
                        <div class="col-lg-6">
                            <input id="mail" class="form-control" placeholder="Adresse de courriel" name="mail"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-6">
                            <input id="pass" class="form-control" type="password" placeholder="Mot de passe" name="pass"/>
                        </div>
                        <div class="col-lg-6">
                            <input id="pass-confirm" class="form-control" type="password" placeholder="Confirmation du mot de passe" name="pass-confirm"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-6">
                            <input id="adresse-1" class="form-control" type="text" placeholder="Adresse (n°, rue/avenue/place...)" name="adresse-1"/>
                        </div>
                        <div class="col-lg-6">
                            <input id="adresse-2" class="form-control" type="text" placeholder="Complément d'adresse" name="adresse-2"/>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-6">
                            <input id="cp" class="form-control" type="text" placeholder="Code postal" name="cp"/>
                        </div>
                        <div class="col-lg-6">
                            <input id="ville" class="form-control" type="text" placeholder="Ville" name="ville"/>
                        </div>
                    </div>
                    <div class="form-group zone-btn">
                        <button class="btn btn-success btn-grand" type="submit">
                            <span class="glyphicon glyphicon-forward"></span>
                            S'enregistrer et obtenir une clé d'API
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <div class="col-lg-4">

    </div>

</div>


<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
    $("#form-connexion").submit(function (e) {
        e.preventDefault();
        var login = $("#conn-login").val();
        var password = $("#conn-password").val();
        $.post("actions/connect.php", {login:login, password:password}, function (data) {
            if (data == "ok")
                document.location.reload();
            else
                $("#err-login").html(data).slideDown();
        });
    });

    $("#form-inscription").submit(function (e) {
       e.preventDefault();
       var business_name = $("#business_name").val();
       var username = $("#username").val();
       var mail = $("#mail").val();
       var pass = $("#pass").val();
       var pass_confirm = $("#pass-confirm").val();
       var adresse_1 = $("#adresse-1").val();
        var adresse_2 = $("#adresse-2").val();
       var cp = $("#cp").val();
       var ville = $("#ville").val();
        $.post("actions/create_user.php", {business_name:business_name, username:username, mail:mail, pass:pass, pass_confirm:pass_confirm, adresse_1:adresse_1, adresse_2:adresse_2, cp:cp, ville:ville}, function (data) {
            if (data == "ok")
                document.location.reload();
            else
                $("#err-inscription").html(data).slideDown();
        });
    });
</script>

</body>
</html>

