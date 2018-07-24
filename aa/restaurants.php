<?php

require "init.php";
redirectIfNotConnecte();
$user = unserialize($_SESSION["user"]);
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

    <div class="titre-page">
        <h1>Les restaurants partenaires</h1>
    </div>

    <section class="section">
        <div class="titre-sect petit-margin-top">
            <div class="row">
                <div class="col-lg-11">
                    <h3>Les restaurants de CMR</h3>
                </div>
                <div class="col-lg-1">
                    <h3>
                        <button class="btn-grand" data-toggle="collapse" data-target="#section-restaurants">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                    </h3>
                </div>
            </div>
        </div>
        <div class="contenu-section in" id="section-restaurants">
            <h4>Filtres</h4>
            <div class="row">
                <div class="col-lg-4">
                    <label for="select-dpt">Par département</label>
                    <select class="form-control" id="select-dpt" name="dep">
                        <option value="all">Tous les dpts.</option>
                        <option value="01">&#40;01&#41; Ain </option>
                        <option value="02">&#40;02&#41; Aisne </option>
                        <option value="03">&#40;03&#41; Allier </option>
                        <option value="04">&#40;04&#41; Alpes de Haute Provence </option>
                        <option value="05">&#40;05&#41; Hautes Alpes </option>
                        <option value="06">&#40;06&#41; Alpes Maritimes </option>
                        <option value="07">&#40;07&#41; Ardèche </option>
                        <option value="08">&#40;08&#41; Ardennes </option>
                        <option value="09">&#40;09&#41; Ariège </option>
                        <option value="10">&#40;10&#41; Aube </option>
                        <option value="11">&#40;11&#41; Aude </option>
                        <option value="12">&#40;12&#41; Aveyron </option>
                        <option value="13">&#40;13&#41; Bouches du Rhône </option>
                        <option value="14">&#40;14&#41; Calvados </option>
                        <option value="15">&#40;15&#41; Cantal </option>
                        <option value="16">&#40;16&#41; Charente </option>
                        <option value="17">&#40;17&#41; Charente Maritime </option>
                        <option value="18">&#40;18&#41; Cher </option>
                        <option value="19">&#40;19&#41; Corrèze </option>
                        <option value="2A">&#40;2A&#41; Corse du Sud </option>
                        <option value="2B">&#40;2B&#41; Haute-Corse</option>
                        <option value="21">&#40;21&#41; Côte d'Or </option>
                        <option value="22">&#40;22&#41; Côtes d'Armor </option>
                        <option value="23">&#40;23&#41; Creuse </option>
                        <option value="24">&#40;24&#41; Dordogne </option>
                        <option value="25">&#40;25&#41; Doubs </option>
                        <option value="26">&#40;26&#41; Drôme </option>
                        <option value="27">&#40;27&#41; Eure </option>
                        <option value="28">&#40;28&#41; Eure et Loir </option>
                        <option value="29">&#40;29&#41; Finistère </option>
                        <option value="2B">&#40;2B&#41; Haute-Corse </option>
                        <option value="30">&#40;30&#41; Gard </option>
                        <option value="31">&#40;31&#41; Haute Garonne </option>
                        <option value="32">&#40;32&#41; Gers </option>
                        <option value="33">&#40;33&#41; Gironde </option>
                        <option value="34">&#40;34&#41; Hérault </option>
                        <option value="35">&#40;35&#41; Ille et Vilaine </option>
                        <option value="36">&#40;36&#41; Indre </option>
                        <option value="37">&#40;37&#41; Indre et Loire </option>
                        <option value="38">&#40;38&#41; Isère </option>
                        <option value="39">&#40;39&#41; Jura </option>
                        <option value="40">&#40;40&#41; Landes </option>
                        <option value="41">&#40;41&#41; Loir et Cher </option>
                        <option value="42">&#40;42&#41; Loire </option>
                        <option value="43">&#40;43&#41; Haute Loire </option>
                        <option value="44">&#40;44&#41; Loire Atlantique </option>
                        <option value="45">&#40;45&#41; Loiret </option>
                        <option value="46">&#40;46&#41; Lot </option>
                        <option value="47">&#40;47&#41; Lot et Garonne </option>
                        <option value="48">&#40;48&#41; Lozère </option>
                        <option value="49">&#40;49&#41; Maine et Loire </option>
                        <option value="50">&#40;50&#41; Manche </option>
                        <option value="51">&#40;51&#41; Marne </option>
                        <option value="52">&#40;52&#41; Haute Marne </option>
                        <option value="53">&#40;53&#41; Mayenne </option>
                        <option value="54">&#40;54&#41; Meurthe et Moselle </option>
                        <option value="55">&#40;55&#41; Meuse </option>
                        <option value="56">&#40;56&#41; Morbihan </option>
                        <option value="57">&#40;57&#41; Moselle </option>
                        <option value="58">&#40;58&#41; Nièvre </option>
                        <option value="59">&#40;59&#41; Nord </option>
                        <option value="60">&#40;60&#41; Oise </option>
                        <option value="61">&#40;61&#41; Orne </option>
                        <option value="62">&#40;62&#41; Pas de Calais </option>
                        <option value="63">&#40;63&#41; Puy de Dôme </option>
                        <option value="64">&#40;64&#41; Pyrénées Atlantiques </option>
                        <option value="65">&#40;65&#41; Hautes Pyrénées </option>
                        <option value="66">&#40;66&#41; Pyrénées Orientales </option>
                        <option value="67">&#40;67&#41; Bas Rhin </option>
                        <option value="68">&#40;68&#41; Haut Rhin </option>
                        <option value="69">&#40;69&#41; Rhône </option>
                        <option value="70">&#40;70&#41; Haute Saône </option>
                        <option value="71">&#40;71&#41; Saône et Loire </option>
                        <option value="72">&#40;72&#41; Sarthe </option>
                        <option value="73">&#40;73&#41; Savoie </option>
                        <option value="74">&#40;74&#41; Haute Savoie </option>
                        <option value="75">&#40;75&#41; Paris </option>
                        <option value="76">&#40;76&#41; Seine Maritime </option>
                        <option value="77">&#40;77&#41; Seine et Marne </option>
                        <option value="78">&#40;78&#41; Yvelines </option>
                        <option value="79">&#40;79&#41; Deux Sèvres </option>
                        <option value="80">&#40;80&#41; Somme </option>
                        <option value="81">&#40;81&#41; Tarn </option>
                        <option value="82">&#40;82&#41; Tarn et Garonne </option>
                        <option value="83">&#40;83&#41; Var </option>
                        <option value="84">&#40;84&#41; Vaucluse </option>
                        <option value="85">&#40;85&#41; Vendée </option>
                        <option value="86">&#40;86&#41; Vienne </option>
                        <option value="87">&#40;87&#41; Haute Vienne </option>
                        <option value="88">&#40;88&#41; Vosges </option>
                        <option value="89">&#40;89&#41; Yonne </option>
                        <option value="90">&#40;90&#41; Territoire de Belfort </option>
                        <option value="91">&#40;91&#41; Essonne </option>
                        <option value="92">&#40;92&#41; Hauts de Seine </option>
                        <option value="93">&#40;93&#41; Seine Saint Denis </option>
                        <option value="94">&#40;94&#41; Val de Marne </option>
                        <option value="95">&#40;95&#41; Val d'Oise </option>
                        <option value="971">&#40;971&#41; Guadeloupe </option>
                        <option value="972">&#40;972&#41; Martinique </option>
                        <option value="973">&#40;973&#41; Guyane </option>
                        <option value="974">&#40;974&#41; Réunion </option>
                        <option value="975">&#40;975&#41; Saint Pierre et Miquelon </option>
                        <option value="976">&#40;976&#41; Mayotte </option>
                    </select>
                </div>
                <div class="col-lg-4">

                    <strong>Par types de cuisine</strong><br />
                    <label>
                        <input id="select-all-type-cuisine" class="chk-type-cuisine" type="checkbox" checked value="all"> Tous
                    </label>
                        <?php
                        $reqTypeCuisine = $bdd->query("SELECT * FROM type_of_cuisine;");
                        $reqTypeCuisine->execute();
                        while ($typeOfCuisine = $reqTypeCuisine->fetch()){ ?>
                            <label>
                                <input class="chk-type-cuisine chk-type-cuisine-na" type="checkbox" value="<?php echo $typeOfCuisine['id']; ?>"> <?php echo $typeOfCuisine["name"]; ?>
                            </label>
                        <?php } ?>

                </div>
                <div class="col-lg-4">

                    <strong>Par types de commandes</strong><br />
                    <label>
                    <input id="select-all-order-type" class="chk-order-type" type="checkbox" checked value="all"> Tous
                    </label>
                    <?php
                    $reqTypeOrder = $bdd->query("SELECT * FROM order_type;");
                    $reqTypeOrder->execute();
                    while ($typeOrder = $reqTypeOrder->fetch()){ ?>
                        <label>
                            <input class="chk-order-type chk-order-type-na" type="checkbox" value="<?php echo $typeOrder['id']; ?>"> <?php echo $typeOrder["name"]; ?>
                        </label>
                    <?php } ?>

                </div>
            </div>

            <hr>

            <div id="cont-restau-dpt">
                <div class="checkbox">
                    <label>
                        <input id="select-all-resto" type="checkbox" name="contract" value="" /> Tout sélectionner
                    </label>
                </div>

                <form action="add_contracts_restaurants.php" method="post">
                    <?php
                    $req = $bdd->query("SELECT * FROM restaurant INNER JOIN delivery_town ON restaurant.town_id = delivery_town.id INNER JOIN departements ON delivery_town.departement_id = departements.id;");
                    $req->execute();
                    while ($res = $req->fetch()){
                        $reqCountCountratActifs = $bdd->prepare("SELECT COUNT(contract.id) FROM contract INNER JOIN contract_virgin ON contract.contract_id = contract_virgin.id WHERE restaurant_id = :id AND status_id != 3 AND business_id = :busId");
                        $reqCountCountratActifs->bindValue(":busId", $user["business_id"], PDO::PARAM_INT);
                        $reqCountCountratActifs->bindValue(":id", $res[0], PDO::PARAM_INT);
                        $reqCountCountratActifs->execute();
                        $nbContratActifs = $reqCountCountratActifs->fetchColumn();
                        $reqRestauType = $bdd->prepare("SELECT * FROM type_of_cuisine INNER JOIN restaurant_type_of_cuisine ON restaurant_type_of_cuisine.type_of_cuisine_id = type_of_cuisine.id WHERE restaurant_type_of_cuisine.restaurant_id = :restoId");
                        $reqRestauType->bindValue("restoId", $res[0], PDO::PARAM_INT);
                        $reqRestauType->execute();
                        $reqRestauOrderType = $bdd->prepare("SELECT * FROM order_type INNER JOIN restaurant_order_type ON restaurant_order_type.order_type_id = order_type.id WHERE restaurant_order_type.restaurant_id = :restoId");
                        $reqRestauOrderType->bindValue("restoId", $res[0], PDO::PARAM_INT);
                        $reqRestauOrderType->execute();
                        if ($nbContratActifs == null) $nbContratActifs = 0;?>
                        <div class="resto cont-restau <?php while ($typesRestau = $reqRestauType->fetch()){echo "type-" . $typesRestau[0] . " ";} ?><?php while ($orderTypesRestau = $reqRestauOrderType->fetch()){echo "order-type-" . $orderTypesRestau[0] . " ";} ?>" dpt="<?php echo $res['code']; ?>">
                            <div class="row">
                                <div class="col-lg-3">
                                    <h5>
                                        <label>
                                            <input type="checkbox" class="chk-resto" name="restau[]" value="<?php echo $res[0]; ?>" /> <?php echo $res["name"]; ?>
                                        </label>
                                    </h5>
                                </div>
                                <div class="col-lg-3">
                                    <?php echo $nbContratActifs; ?> contrat(s) proposés(s)
                                </div>
                                <div class="col-lg-3">
                                    <a target="_blank" href="restaurant.php?id=<?php echo $res[0]; ?>">
                                        <button type="button" class="btn btn-info">
                                            <span class="glyphicon glyphicon-zoom-in"></span> Voir le restaurant
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <button type="submit" class="btn btn-success btn-grand">Proposer un contrat aux restaurant sélectionnés</button>
                </form>
            </div>

        </div>
    </section>

    </div>

    <script>
        // Retourne vrai si le restaurant passé en paramètre contient au moins 1 type de cuisine parmis ceux checkés
        function filterTypeOfCuisine(div_resto) {
            is_affichable = false;
            var chkbox_type = $(".chk-type-cuisine:checked");
            if (chkbox_type.length == 0 || (chkbox_type.length == 1 && chkbox_type.val() == "all")){
                return true;
            }
            chkbox_type.each(function () {
                var id = $(this).val();
                if (div_resto.hasClass("type-" + id)) {
                    is_affichable = true;
                }
            });
            return is_affichable;
        }
        // Retourne vrai si le restaurant passé en paramètre contient au moins 1 type de commande parmis ceux checkés
        function filterOrderType(div_resto) {
            is_affichable = false;
            var chkbox_type = $(".chk-order-type:checked");
            if (chkbox_type.length == 0 || (chkbox_type.length == 1 && chkbox_type.val() == "all")){
                return true;
            }
            chkbox_type.each(function () {
                var id = $(this).val();
                if (div_resto.hasClass("order-type-" + id)) {
                    is_affichable = true;
                }
            });
            return is_affichable;
        }
        // Retourne vrai si le restaurant passé en paramètre est dans le département sélectionné
        function filterDepartement(div_resto) {
            var dpt = $("#select-dpt").val();
            if (dpt == "all")
                return true;
            else
            if (div_resto.attr("dpt") == dpt)
                return true;
            return false;
        }
        // Parcours les restaurants et affiche ceux correspondant aux filtres
        function verifyResto() {
            var div_resto = $(".resto");
            div_resto.hide();
            div_resto.each(function () {
                if (filterTypeOfCuisine($(this)) == true && filterOrderType($(this)) == true && filterDepartement($(this)) == true){
                    $(this).show();
                }
            });
        }

        $("#select-all-type-cuisine").change(function () {
           if ($(this).prop("checked") == true){
               $(".chk-type-cuisine-na").prop("checked", false);
               verifyResto();
           }
        });
        $("#select-all-order-type").change(function () {
            if ($(this).prop("checked") == true){
                $(".chk-order-type-na").prop("checked", false);
                verifyResto();
            }
        });

        $(".chk-order-type-na").change(function () {
            $("#select-all-order-type").prop("checked", false);
            verifyResto();
        });

        $(".chk-type-cuisine-na").change(function () {
            $("#select-all-type-cuisine").prop("checked", false);
            verifyResto();
        });

        $("#select-dpt").change(function () {
           verifyResto();
        });

        $("#select-all-resto").change(function () {
            if (!$(this).is(":checked")){
                $(".chk-resto").prop("checked", false);
            } else {
                $(".chk-resto").prop("checked", true);
            }
        });
    </script>

</div>



</body>
</html>