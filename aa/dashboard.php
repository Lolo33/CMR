<?php

include "init.php";
include "stripe.php";

redirectIfNotConnecte();
$user = unserialize($_SESSION["user"]);

$req_user = $bdd->prepare("SELECT * FROM user_token WHERE api_user_id = :id");
$req_user->bindValue(":id", $user["id"], PDO::PARAM_INT);
$req_user->execute();
$key = $req_user->fetch();
$req_bus = $bdd->prepare("SELECT * FROM business WHERE id = :busId");
$req_bus->bindValue(":busId", $user["business_id"], PDO::PARAM_INT);
$req_bus->execute();
$bus = $req_bus->fetch();

$req_user_stripe = $bdd->prepare("SELECT * FROM user_stripe WHERE api_user_id = :id");
$req_user_stripe->bindValue(":id", $user["id"], PDO::PARAM_INT);
$req_user_stripe->execute();
$stripe = $req_user_stripe->fetch();

$reqCountParts = $bdd->prepare("SELECT COUNT(contract.id) FROM contract INNER JOIN contract_virgin ON contract.contract_id = contract_virgin.id INNER JOIN contract_period ON contract.id = contract_period.contract_id WHERE status_id = 4 AND start_validity < NOW() AND end_validity > NOW() AND activationDate < NOW() AND endDate > NOW() AND contract_virgin.business_id = :idBus ");
$reqCountParts->bindValue(":idBus", $bus["id"], PDO::PARAM_INT);
$reqCountParts->execute();
$countParts = $reqCountParts->fetchColumn();

$reqCountContracts = $bdd->prepare("SELECT COUNT(id) FROM contract_virgin WHERE business_id = :idBus");
$reqCountContracts->bindValue(":idBus", $bus["id"], PDO::PARAM_INT);
$reqCountContracts->execute();
$countContracts = $reqCountContracts->fetchColumn();

$reqCountCountratActifs = $bdd->prepare("SELECT COUNT(contract.id) FROM contract INNER JOIN contract_virgin ON contract.contract_id = contract_virgin.id WHERE status_id != 3 AND business_id = :busId");
$reqCountCountratActifs->bindValue(":busId", $user["business_id"], PDO::PARAM_INT);
$reqCountCountratActifs->execute();

$nbContratActifs = $reqCountCountratActifs->fetchColumn();
$reqRequests = $bdd->prepare("SELECT * FROM history_request WHERE user_id = :id");
$reqRequests->bindValue(":id", $user["id"], PDO::PARAM_INT);
$reqRequests->execute();

$reqRequestsToday = $bdd->prepare("SELECT * FROM history_request WHERE user_id = :id AND DATE(date) = DATE (NOW())");
$reqRequestsToday->bindValue(":id", $user["id"], PDO::PARAM_INT);
$reqRequestsToday->execute();

$cards = list_sources_stripe($bus["stripe_id"]);


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
        .titre-card {
            padding: 10px;
            font-size: 15px;
        }
    </style>
</head>
<body>
<script src="https://js.stripe.com/v3/"></script>


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

    <div class="titre-page">
        <h1>Mon compte ConnectMyResto</h1>
    </div>

    <section class="section">
        <div class="titre-sect petit-margin-top">
            <div class="row">
                <div class="col-lg-11">
                    <h3>Mes informations</h3>
                </div>
                <div class="col-lg-1">
                    <h3>
                        <button class="btn-grand" data-toggle="collapse" data-target="#section-infos">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                    </h3>
                </div>
            </div>
        </div>
        <div class="contenu-section in" id="section-infos">
            <div class="cont-restau">
                <div class="padding-form">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3>
                                        <span class="glyphicon glyphicon-briefcase"></span>
                                        <strong><?php echo $bus["name"]; ?></strong>
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <h3><span class="glyphicon glyphicon-credit-card"></span> Mon Solde</h3>
                                </div>
                                <div class="col-lg-6">
                                    <h3><?php echo $bus["solde"]; ?> € <span style="font-size: 10px">(soit ~= <?php echo round($bus["solde"] * 3, 0); ?> commandes à 30€)</span> </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <h3><span class="glyphicon glyphicon-lock"></span> Clé d'API</h3>
                                </div>
                                <div class="col-lg-6">
                                    <input readonly class="form-control" value="<?php echo $key['value']; ?>">
                                </div>
                            </div>
                            <div class="row petit-margin-top">
                                <div class="col-lg-12">
                                    <button class="btn btn-primary btn-grand">Changer ma clé d'API</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6" style="border-left: 1px solid lightslategrey;">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3>
                                        <span class="glyphicon glyphicon-user"></span>
                                        <strong><?php echo $user["user_client_id"]; ?></strong>
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php echo $bus["mail"]; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php echo $bus["adress_line1"] . " " . $bus["adress_line2"] . ", " . $bus["state"] . " " . $bus["region"]; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <strong><?php echo $countContracts; ?></strong> contrat(s) disponible(s)
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <strong><?php echo $countParts; ?></strong> restaurant(s) partenaire(s) / <strong><?php echo $nbContratActifs; ?></strong> contrat(s) proposés
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <strong><?php echo $reqRequests->rowCount(); ?></strong> requêtes passées (<?php echo $reqRequestsToday->rowCount(); ?> aujourd'hui)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="titre-sect petit-margin-top">
            <div class="row">
                <div class="col-lg-11">
                    <h3>Créditer mon compte</h3>
                </div>
                <div class="col-lg-1">
                    <h3>
                        <button class="btn-grand" data-toggle="collapse" data-target="#section-card">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                    </h3>
                </div>
            </div>
        </div>
        <div class="contenu-section in" id="section-card">
            <?php if (!empty($cards->data)){ ?>
                <div class="titre-card titre-color petit-margin-top">
                    <label for="add-existant">
                        <input class="opt-card" name="card" id="add-existant" type="radio" checked/>
                        Créditer avec une carte existante
                    </label>
                </div>
                <div id="show-add-existant" class="cont-restau">
                    <form class="padding-form" action="actions/add_credit.php" method="post">
                        <label for="amount">Montant à créditer</label>
                        <input name="amount" id="amount" class="form-control" type="number" step="0.1" placeholder="30.0" min="0">
                        <label for="card-select">Carte sélectionnée</label>
                        <select id="card-select" class="form-control" name="selected-card">
                            <?php foreach ($cards->data as $card){
                                var_dump(1);
                                echo "<option value='".$card->id."'>XXXX XXXX XXXX " . $card->last4 . " - ".$card->exp_month." / ".$card->exp_year."</option>";
                            } ?>
                        </select>
                        <div class="zone-btn petit-margin-top">
                            <button class="btn btn-success">Valider et payer</button>
                        </div>
                    </form>
                </div>
            <?php } ?>
            <div class="titre-card titre-color petit-margin-top">
                <label for="add-new-card">
                    <input  <?php if (empty($cards->data)){ ?> checked <?php } ?> class="opt-card" name="card" id="add-new-card" type="radio" />
                    Créditer avec une nouvelle carte
                </label>
            </div>
            <div <?php if (!empty($cards->data)){ ?> style="display: none;" <?php } ?> id="show-add-new-card" class="cont-restau">
                <form class="padding-form" action="actions/add_credit.php" method="post" id="payment_form_new_card">
                    <label for="amount">Montant à créditer</label>
                    <input name="amount" id="amount" class="form-control" type="number" step="0.1" placeholder="30.0" min="0">
                    <label for="card-element">
                        Credit or debit card
                    </label>
                    <div id="card-element">
                        <!-- a Stripe Element will be inserted here. -->
                    </div>
                    <!-- Used to display Element errors -->
                    <div id="card-errors" role="alert"></div>
                    <div class="zone-btn petit-margin-top">
                        <button class="btn btn-success">Valider et payer</button>
                    </div>
                </form>
            </div>
        </div>
    </section>


</div>


<!-- Stripe -->
<script type="text/javascript">
    var stripe = Stripe('pk_test_RYUONkH3SSrBGd9vw1VJZHfb');
    var elements = stripe.elements();
    // Custom styling can be passed to options when creating an Element.
    var style = {
        base: {
            // Add your base input styles here. For example:
            fontSize: '16px',
            color: "#32325d",
        }
    };

    // Create an instance of the card Element
    var card = elements.create('card', {style: style});

    // Add an instance of the card Element into the `card-element` <div>
    card.mount('#card-element');
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    // Create a token or display an error when the form is submitted.
    var form = document.getElementById('payment_form_new_card');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Inform the customer that there was an error
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Send the token to your server
                stripeTokenHandler(result.token);
            }
        });
    });
    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment_form_new_card');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }

    $(".opt-card").change(function () {
        var id = $(this).attr("id");
        console.log(id);
        if (id == "add-new-card"){
            $("#show-add-existant").hide();
            $("#show-add-new-card").show();
        }else{
            $("#show-add-new-card").hide();
            $("#show-add-existant").show();
        }
    })
</script>

</body>
</html>