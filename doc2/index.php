<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 16/08/2017
 * Time: 15:10
 */
include "includes/init.php";
?>

<!DOCTYPE html>
<html>

<head>
    <?php include 'includes/head.php'; ?>
    <link href="https://fonts.googleapis.com/css?family=Kanit|Muli|Righteous" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway|Josefin+Sans" rel="stylesheet">
    <title><?php echo $nomSite; ?> - Documentation</title>
</head>


<body>

    <div class="navigation">
        <div class="nav-left" tabindex="1" style="overflow: hidden; outline: none;">
            <img src="img/logo_cmr.png" width="250">
            <ul>
                <li class="nav-left-item">
                    <a href="#accueil">
                        <span class="glyphicon glyphicon-home"></span> Accueil
                    </a>
                </li>
                <li class="nav-left-item">
                    <a href="#auth">
                        <span class="glyphicon glyphicon-lock"></span> Authentification
                    </a>
                </li>
                <li class="nav-left-item">
                    <a href="#restaurant-livraison">
                        <span class="glyphicon glyphicon-plane"></span> Restaurants en livraison
                    </a>
                </li>
                <li class="nav-left-item">
                    <a href="#resto-situation">
                        <span class="glyphicon glyphicon-map-marker"></span> Restaurants sur place
                    </a>
                </li>
                <li class="nav-left-item">
                    <a href="#resto-carte">
                        <span class="glyphicon glyphicon-cutlery"></span> Carte d'un restaurant
                    </a>
                </li>
                <li class="nav-left-item">
                    <a href="#commande-creer">
                        <span class="glyphicon glyphicon-asterisk"></span> Créer une commande
                    </a>
                </li>
                <li class="nav-left-item">
                    <a href="#commande-ajouter-produit">
                        <span class="glyphicon glyphicon-plus"></span> Ajouter un produit
                    </a>
                </li>
                <li class="nav-left-item">
                    <a href="#commande-supprimer-produit">
                        <span class="glyphicon glyphicon-remove"></span> Supprimer un produit
                    </a>
                </li>
                <li class="nav-left-item">
                    <a href="#commande-maj">
                        <span class="glyphicon glyphicon-pencil"></span> Modifier une commande
                    </a>
                </li>
                <li class="nav-left-item">
                    <a href="#commande-confirmer">
                        <span class="glyphicon glyphicon-ok"></span> Confirmer une commande
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="contenu-right">
        <H2>ConnectMyResto - Documentation API</H2>
        <section id="accueil" class="row">
            <div class="col-lg-6 infos-tool">
                <h4 class="titre-page">ConnectMyResto API</h4>
                ConnectMyResto API permet à tous les acteurs de la food-tech de récupérer en temps réél les informations des restaurants.<br />
                Ce système est disponible à tous les professionnels souhaitant recevoir la carte, les horaires, et/ou les commandes de nos restaurants partenaires.
                <br /><br />
                En créant un compte, vous aurez à disposition une clé d'API qui vous servira pour authentifier vos requêtes vers le serveur ainsi
                qu'un espace dédié vous permettant de faciliter le démararchage et la création de partenariat avec les restaurants.
                <br /><br />

                Pour tester l'intégration de vos requêtes sans affecter l'espace de production, vous avez à disposition un environnement de test,
                similaire à l'environnement de production, mais avec des données mockées.<br /><br />
            </div>
            <div class="col-lg-6 exemple-tool">
                <h5 class="titre-exemple">Environnement de production</h5>
                <pre style="padding-top: 0; padding-bottom: 0;background-color: #232323; border-color: #232323;"><code class="json">http://api.connectmyresto.com</code></pre>
                <h5 class="titre-exemple">Environnement de test</h5>
                <pre style="padding-top: 0; padding-bottom: 0;background-color: #232323; border-color: #232323;"><code class="json">http://api.test.connectmyresto.com</code></pre>
            </div>
        </section>

        <section id="auth" class="row">
            <div class="col-lg-6 infos-tool">
                <h4 class="titre-page">Authentifier ses requêtes</h4>
                Vous pouvez communiquer avec notre service par le biais de <a href="https://openclassrooms.com/courses/les-requetes-http" target="_blank" title="Voir un cours sur les requêtes HTTP">requêtes HTTP</a>,
                dans lesquelles les données sont présentées au format JSON.
                <br /><br />
                L'envoi de requêtes vers notre serveur nécessite une authentification grâce à votre clé d'API. Pour authentifier une requête,
                il suffit d'ajouter un entête HTTP avec pour clé le mot "Authorisation" et pour valeur votre clé d'API.
                Si vous utilisez notre SDK, il suffit d'instancier un nouvel objet CmrApi avec votre clé d'API en paramètre.
                L'axemple ci-dessous vous montre comment authentifier une requête :<br /><br />
            </div>
            <div class="col-lg-6 exemple-tool">
                <h5 class="titre-exemple">Exemple de header</h5>
                <pre style="padding-top: 0; padding-bottom: 0;background-color: #232323; border-color: #232323;"><code class="json">{
    "Authorisation": "VOTRE_CLE_API",
    "content-type": "application/json"
}</code></pre>
            </div>
        </section>

        <section id="restaurant-livraison" class="row">
            <div class="col-lg-6 infos-tool">
                <h4 class="titre-page">Récupérer les restaurants livrant une zone</h4>
                Un objet restaurant correspond à un de nos restaurants partenaires inscrits sur CMR.
                Les horaires ainsi que la carte de chacun de nos restaurants partenaires est modifiable en temps réel.
                Lorsque vous cherchez à récuperer un ou plusieurs restaurants, nous fournissons, dans les données renvoyées, une liste des catégories de produits, et chacune de ces catégories contiennent des produits de leur carte.<br /><br />
                <br /><br>

                <h5 class="sous-titre-page">URL de la requête</h5>
                <div class="col-md-2 method">
                    <h4 class="methode-http methode-get">GET</h4>
                </div>
                <div class="col-md-10 method">
                    <h4 class="url">... /restaurants/delivery/{params}</h4>
                </div>
                <hr>
                <div class="params-list">
                    <h5 class="sous-titre-page">Paramètres</h5>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Clé</th>
                            <th>Description</th>
                            <th>Exemple</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr class="active">
                                <td>insee</td>
                                <td>Code INSEE de la zone de livraison souhaitée</td>
                                <td>33039</td>
                                <td><span class="label label-danger">Obligatoire</span></td>
                            </tr>
                            <tr class="active">
                                <td>ville</td>
                                <td>Code postal et nom de la ville séparés par des tirets. Les espaces dans le nom de la ville doivent être remplacés par des "+"</td>
                                <td>33150-Cenon</td>
                                <td><span class="label label-danger">Obligatoire</span></td>
                            </tr>
                            <tr class="active">
                                <td>dates</td>
                                <td>Permet de préciser les dates desquelles on souhaite avoir les horaires des restaurants,
                                    au format Y-m-d séparées par des virgules</td>
                                <td>2018-03-15,2018-03-16</td>
                                <td><span class="label label-primary">Facultatif</span></td>
                            </tr>
                        <tr class="active">
                            <td>partnership</td>
                            <td>Permet un tri des résultats suivant l'état du partenariat avec ce dernier</td>
                            <td>
                                <ul style="margin: 0;padding:0;">
                                    <li><strong>all</strong>: valeur par défaut : ressort tous les restaurants</li>
                                    <li><strong>none</strong>: ressort uniquement les restaurants auxquels vous n'avez pas encore proposé de partenariat</li>
                                    <li><strong>waiting</strong>: ressort uniquement les restaurants qui sont en attente de confirmation du contrat</li>
                                    <li><strong>refused</strong>: ressort uniquement les restaurants ayant refusé vos propositions de partenariat</li>
                                    <li><strong>accepted</strong>: ressort uniquement les restaurants ayant accepté vos propositions de partenariat</li>
                                </ul>
                            </td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        </tbody>
                    </table>
                    Pour cette requête, vous devez préciser <strong>SOIT UNE VILLE, SOIT UN CODE INSEE</strong>, mais pas les deux.
                </div>
            </div>
            <div class="col-lg-6 exemple-tool">
                <h5 class="titre-exemple">Exemple de réponse</h5>
                <pre style="padding-top: 0; padding-bottom: 0;background-color: #232323; border-color: #232323;"><code class="json">{
    "id": 1,
    "name": "Le Port de Lagrange",
    "isOpen": false,
    "openStateString": "Fermé jusqu'a aujourd'hui 16:20",
    "logoUrl": null,
    "addressLine1": "21 rue des Mimosas",
    "addressLine2": null,
    "town": {
        "id": 9011,
        "codeINSEE": "33249",
        "name": "LORMONT",
        "countryCode": 33310,
        "country": {
            "id": 1,
            "name": "France",
            "code": "FR"
        }
    },
    "currency": {
        "id": 2,
        "name": "Dollars",
        "currencyCode": "USD"
    }
    "type": [
        {
            "id": 1,
            "name": "French Food"
        },
        {
            "id": 2,
            "name": "Chinese Food"
        },
        {
            "id": 3,
            "name": "Indian Food"
        }
    ],
    "deliveryFees": {
        "id": 7,
        "minOrder": 25,
        "deliveryTown": {
            "id": 3682,
            "codeINSEE": "33119",
            "name": "CENON",
            "countryCode": 33150,
            "country": {
                "id": 1,
                "name": "France",
                "code": "FR"
            }
        },
        "deliveryFee": 3.5
    },
    "timeToDelivery": "01:05",
    "marginDelivery": "00:10",
    "realHours": {
        "2018-07-11": [
            {
                "hourOpening": "2018-07-11 16:20:22",
                "hourClosing": "2018-07-11 16:30:00"
            }
        ],
        "2018-07-12": [
            {
                "hourOpening": "1970-01-01 20:00:00",
                "hourClosing": "1970-01-02 01:00:00"
            }
        ]
    },
    "paymentModes": [
        {
            "id": 1,
            "modeName": "Carte VISA - MASTERCARD",
            "modeCode": "CB"
        },
        {
            "id": 2,
            "modeName": "Carte Electron",
            "modeCode": "CB_EC"
        }
    ]
}</code></pre>
            </div>
        </section>

        <section id="resto-situation" class="row">
            <div class="col-lg-6 infos-tool">
                <h4 class="titre-page">Récupérer les restaurants situés dans une zone</h4>
                <div class="requete-description">
                    Pour récupérer la liste de tous les restaurants partenaires qui proposent des plats à emporter à proximité d'une zone,
                    il suffit d'envoyer les coordonnées GPS de l'endroit ou vous vous situez (latitude et longitude) sur l'URI ci-dessous et la requête vous
                    renverra les restaurants situés à 500m de distance du point envoyé. Vous pouvez trier ces résultats en envoyant des paramètres supplémentaires
                    dans l'URI (sous la forme nom=valeur, chacun de ces paramètres séparés d'un "&").
                </div>

                <h5 class="sous-titre-page">URL de la requête</h5>
                <div class="col-md-2 method">
                    <h4 class="methode-http methode-get">GET</h4>
                </div>
                <div class="col-md-10 method">
                    <h4 class="url">... /restaurants/take-away/{params}</h4>
                </div>

                <div class="params-list">
                    <h5 class="sous-titre-page">Paramètres</h5>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Clé</th>
                            <th>Description</th>
                            <th>Exemple</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="active">
                            <td>lat</td>
                            <td>Latitude du point GPS dont on cherche les restaurant a proximité</td>
                            <td>-0.00251885</td>
                            <td><span class="label label-danger">Obligatoire</span></td>
                        </tr>
                        <tr class="active">
                            <td>long</td>
                            <td>Longitude du point GPS dont on cherche les restaurant a proximité</td>
                            <td>47.152498652</td>
                            <td><span class="label label-danger">Obligatoire</span></td>
                        </tr>
                        <tr class="active">
                            <td>dates</td>
                            <td>Permet de préciser les dates desquelles on souhaite avoir les horaires des restaurants,
                                au format Y-m-d séparées par des virgules</td>
                            <td>2018-03-15,2018-03-16</td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        <tr class="active">
                            <td>partnership</td>
                            <td>Permet un tri des résultats suivant l'état du partenariat avec ce dernier</td>
                            <td>
                                <ul style="margin: 0;padding:0;">
                                    <li><strong>all</strong>: valeur par défaut : ressort tous les restaurants</li>
                                    <li><strong>none</strong>: ressort uniquement les restaurants auxquels vous n'avez pas encore proposé de partenariat</li>
                                    <li><strong>waiting</strong>: ressort uniquement les restaurants qui sont en attente de confirmation du contrat</li>
                                    <li><strong>refused</strong>: ressort uniquement les restaurants ayant refusé vos propositions de partenariat</li>
                                    <li><strong>accepted</strong>: ressort uniquement les restaurants ayant accepté vos propositions de partenariat</li>
                                </ul>
                            </td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
                <div class="col-lg-6 exemple-tool">
                    <h5 class="titre-exemple">Exemple de réponse</h5>
                    <pre style="padding-top: 0; padding-bottom: 0;background-color: #232323; border-color: #232323;"><code class="json">[
    {
        "id": 1,
        "name": "Le Port de Lagrange",
        "isOpen": true,
        "openStateString": "Ouvert jusqu'à 15:25",
        "logoUrl": null,
        "addressLine1": "21 rue des Mimosas",
        "addressLine2": null,
        "town": {
            "id": 9011,
            "codeINSEE": "33249",
            "name": "LORMONT",
            "countryCode": 33310,
            "country": {
                "id": 1,
                "name": "France",
                "code": "FR"
            }
        },
        "currency": {
            "id": 2,
            "name": "Dollars",
            "currencyCode": "USD"
        },
        "type": [
            {
                "id": 1,
                "name": "French Food"
            },
            {
                "id": 2,
                "name": "Chinese Food"
            },
            {
                "id": 3,
                "name": "Indian Food"
            }
        ],
        "realHours": {
            "2018-07-11": [
                {
                    "hourOpening": "2018-07-11 12:25:00",
                    "hourClosing": "2018-07-11 15:25:00"
                }
            ],
            "2018-07-12": [
                {
                    "hourOpening": "1970-01-01 18:55:00",
                    "hourClosing": "1970-01-01 23:55:00"
                }
            ]
        },
        "paymentModes": [
            {
                "id": 1,
                "modeName": "Carte VISA - MASTERCARD",
                "modeCode": "CB"
            },
            {
                "id": 5,
                "modeName": "Espèces",
                "modeCode": "ESP"
            }
        ],
        "imagesList": [],
        "distanceFromPoint": 0
    }
]</code></pre>
                </div>
        </section>

        <section id="resto-carte" class="row">
            <div class="col-lg-6 infos-tool">
                <h4 class="titre-page">Récupérer la carte d'un restaurant</h4>
                Pour récupérer uniquement la carte d'un restaurant, vous pouvez, à l'aide de son identifiant unique (id) envoyer une simple
                requête de type HTTP GET.<br /><br />
                La carte du restaurant vous est accessible uniquement si vous avez en place un partenariat actif avec ce dernier.
                La liste des plats est triée par catégories (Entrées, plats, desserts...) et sont renvoyés les propriétés (options)
                et les suppléments disponibles de chacuns des produits.
                <br /><br />
                <h5 class="sous-titre-page">URL de la requête</h5>
                <div class="col-md-2 method">
                    <h4 class="methode-http methode-get">GET</h4>
                </div>
                <div class="col-md-10 method">
                    <h4 class="url">... /restaurants/{id}/products</h4>
                </div>
            </div>
            <div class="col-lg-6 exemple-tool">
                <h5 class="titre-exemple">Exemple de réponse</h5>
                <pre style="padding-top: 0; padding-bottom: 0;background-color: #232323; border-color: #232323;"><code class="json">[
    {
        "id": 25,
        "name": "Entrées",
        "description": "Entrées",
        "productsList": [
            {
                "id": 56,
                "name": "Salade César",
                "description": "Salade César",
                "propertiesList": null,
                "supplementsList": null,
                "price": 10,
                "imgUrl": "http://resto.connectmyresto.com/assets/img/restaurants/1/products/51564erg4e98eg4r.png",
                "position": 1,
                "isActive": true,
                "isSoldOut": false,
                "isTakeAwayAuthorized": true
            }
        ],
        "isActive": true
    }
]</code></pre>
            </div>
        </section>

        <section id="commande-creer" class="row">
            <div class="col-lg-6 infos-tool">
                <h4 class="titre-page">Créer une commande</h4>

                La première étape pour transmettre une commande a un restaurant est de la créer. Pour ceci, il suffit simplement de préciser
                le type de commande que vous souhaitez créer (orderType: sur place, livraison...).
                Vous pouvez, si vous le souhaitez renseigner tous les champs obligatoires à la confirmation de la commande dès cette étape, ou tout simplement
                mettre à jour la commande avant ou au moment de la confirmation comme explicité plus bas. Une fois la commande créee, il vous sera possible avec son id
                d'y ajouter des produits à condition de le garder en mémoire.

                <br /><br />
                <h5 class="sous-titre-page">URL de la requête</h5>
                <div class="col-md-2 method">
                    <h4 class="methode-http methode-post">POST</h4>
                </div>
                <div class="col-md-10 method">
                    <h4 class="url">... /restaurants/{id}/orders</h4>
                </div>

                <div class="params-list">
                    <h5 class="sous-titre-page">Paramètres corps de requête (body)</h5>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Clé</th>
                            <th>Description</th>
                            <th>Exemple</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="active">
                            <td>orderType</td>
                            <td>Identifiant du type de commande (sur place, livraison par un tiers...)</td>
                            <td>1</td>
                            <td><span class="label label-danger">Obligatoire</span></td>
                        </tr>
                        <tr class="active">
                            <td>clientName</td>
                            <td>Nom du client demandeur</td>
                            <td>Unnom Unprenom</td>
                            <td><span class="label label-danger">Obligatoire</span></td>
                        </tr>
                        <tr class="active">
                            <td>clientPhone</td>
                            <td>Numéro de télephone du client </td>
                            <td>0555555555</td>
                            <td><span class="label label-danger">Obligatoire</span></td>
                        </tr>
                        <tr class="active">
                            <td>clientAddressLine1</td>
                            <td>Adresse du client (numéro, rue/avenue)</td>
                            <td>1 rue des coquelicots</td>
                            <td><span class="label label-danger">Obligatoire</span></td>
                        </tr>
                        <tr class="active">
                            <td>clientAddressLine2</td>
                            <td>Complément d'adresse du client (batiment, interphone...)</td>
                            <td>Batiment 2</td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        <tr class="active">
                            <td>clientTown</td>
                            <td>ville / code INSEE du client (identifiant d'objet Town)</td>
                            <td>8088</td>
                            <td><span class="label label-danger">Obligatoire</span></td>
                        </tr>
                        <tr class="active">
                            <td>precisions</td>
                            <td>Précisions supplémentaires du client </td>
                            <td>Pas trop de ketchup</td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        <tr class="active">
                            <td>hourToBeReady</td>
                            <td>Heure à laquelle la commande doit être prête (+ ou - le temps de livraison du restaurant)</td>
                            <td>19:30</td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        <tr class="active">
                            <td>amountSupported</td>
                            <td>Montant HT de la commission que vous prenez sur la commande</td>
                            <td>10.00</td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        <tr class="active">
                            <td>amountToCash</td>
                            <td>Montant à encaisser par le restaurant au service de la commande</td>
                            <td>20.00</td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6 exemple-tool">
                <h5 class="titre-exemple">Exemple de réponse</h5>
                <pre style="padding-top: 0; padding-bottom: 0;background-color: #232323; border-color: #232323;"><code class="json">{
    "id": 123,
    "reference": "NO9274DYBS",
    "restaurant": {
        "id": 1
    },
    "type": {
        "id": 2,
        "name": "Livraison par le restaurant"
    },
    "status": {
        "id": 1,
        "name": "Incomplete"
    },
    "clientName": "Mickael Lambda",
    "clientPhone": "05541232XX",
    "clientAddressLine1": null,
    "clientAddressLine2": null,
    "clientTown": {
        "id": 4115,
        "codeINSEE": "33281",
        "name": "MERIGNAC",
        "countryCode": 33700,
        "country": {
            "id": 1,
            "name": "France",
            "code": "FR"
        }
    },
    "hourToBeReady": "2018-03-20 11:30:00",
    "precisions": null,
    "productsList": [],
    "missingParams": {
        "MISS3": {
            "code": "MISS3",
            "text": "Required params missing",
            "params": []
        }
        "MISS2": {
            "code": "MISS2",
            "text": "Your order must have at least 1 product",
            "params": []
        },
        "MISS6": {
            "code": "MISS6",
            "text": "Your command must be more expensive to be delivered in your town",
            "params": [
                15
            ]
        }
    },
    "TotalHT": "00.00",
    "TotalTTC": "00.00",
    "deliveryFee": 5,
    "extraLinesList": null,
    "restaurantAmountToCash": null,
    "amountTakenByBusiness": null
}</code></pre>
            </div>
        </section>


        <section id="commande-ajouter-produit" class="row">
            <div class="col-lg-6 infos-tool">
                <h4 class="titre-page">Ajouter un produit à une commande</h4>

                Une fois votre commande crée, vous pouvez y ajouter des produits un par un (le but étant de requêter à la sélection d'un produit par vos clients).
                En conservant l'id de la commande que vous avez créée, vous pourrez envoyer une requête POST sur l'URL ci-dessous en précisant bien le produit à ajouter ainsi que ses
                propriétés (s'il en possède) et les suppléments à ajouter à ce produit. (S'il est possible d'en mettre).
                Le statut de la commande ainsi que les champs manquants pour sa validité seront mis à jour en temps réel lors du traitement de cette requête.

                <br /><br />
                <h5 class="sous-titre-page">URL de la requête</h5>
                <div class="col-md-2 method">
                    <h4 class="methode-http methode-post">POST</h4>
                </div>
                <div class="col-md-10 method">
                    <h4 class="url">... /orders/{id}/add-product</h4>
                </div>

                <div class="params-list">
                    <h5 class="sous-titre-page">Paramètres corps de requête (body)</h5>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Clé</th>
                            <th>Description</th>
                            <th>Exemple</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="active">
                            <td>id</td>
                            <td>Identifiant du produit à ajouter</td>
                            <td>9</td>
                            <td><span class="label label-danger">Obligatoire</span></td>
                        </tr>
                        <tr class="active">
                            <td>options</td>
                            <td>Tableau contenant les propriétés du produit (obligatoire si le produit requiert des propriétés)</td>
                            <td>
                                {"id": 2}, {"id": 4}
                            </td>
                            <td><span class="label label-danger">Obligatoire</span></td>
                        </tr>
                        <tr class="active">
                            <td>supplements</td>
                            <td>tableau contenant les suppléments du produit </td>
                            <td>{"id": 1}</td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6 exemple-tool">
                <h5 class="titre-exemple">Exemple de réponse</h5>
                <pre style="padding-top: 0; padding-bottom: 0;background-color: #232323; border-color: #232323;"><code class="json">{
    "id": 123,
    "reference": "NO9274DYBS",
    "restaurant": {
        "id": 1
    },
    "type": {
        "id": 2,
        "name": "Livraison par le restaurant"
    },
    "status": {
        "id": 1,
        "name": "Incomplete"
    },
    ...
    "productsList": [
        {
            "referenceKey": 137,
            "product": {
                "id": 9,
                "name": "Margherita",
                "description": "Base sauce tomate, mozzarella",
                "price": 10,
                "imgUrl": "http://resto.connectmyresto.com/assets/img/restaurants/1/products/1518785790_margherita.png",
                "position": 1,
                "isActive": true,
                "isSoldOut": false,
                "isTakeAwayAuthorized": true
            },
            "totalPrice": "15.00",
            "propertiesList": [
                {
                    "id": 2,
                    "name": "Pâte Fromagère",
                    "optionGroup": {
                        "id": 1,
                        "name": "Type de pâte"
                    },
                    "price": 2
                },
                {
                    "id": 4,
                    "name": "Grande",
                    "optionGroup": {
                        "id": 2,
                        "name": "Taille"
                    },
                    "price": 3
                }
            ],
            "supplementsList": []
        }
    ],
    "missingParams": {
        "MISS3": {
            "code": "MISS3",
            "text": "Required params missing",
            "params": []
        }
    },
    "TotalHT": "14.48",
    "TotalTTC": "15.00",
    "deliveryFee": 5,
    "extraLinesList": [],
    "restaurantAmountToCash": null,
    "amountTakenByBusiness": null
}</code></pre>
            </div>
        </section>



        <section id="commande-supprimer-produit" class="row">
            <div class="col-lg-6 infos-tool">
                <h4 class="titre-page">Supprimer un produit d'une commande</h4>

                Pour supprimer un prduit d'une commande, vous pouvez simplement envoyer une requête de type delete sur l'URI ci-dessous
                en précisant bien l'identifiant de la commande sur laquelle supprimer le produit ainsi que la referenceKey du produit (clé unique
                symbolisant la relation entre un produit + ses propriétés + ses suppléments et une commande.)
                Le statut de la commande ainsi que les champs manquants pour sa validité seront mis à jour en temps réel lors du traitement de cette requête.

                <br /><br />
                <h5 class="sous-titre-page">URL de la requête</h5>
                <div class="col-md-2 method">
                    <h4 class="methode-http methode-delete">DELETE</h4>
                </div>
                <div class="col-md-10 method">
                    <h4 class="url">... orders/{id}/remove-product/{referenceKey}</h4>
                </div>
            </div>
            <div class="col-lg-6 exemple-tool">
                <h5 class="titre-exemple">Exemple de réponse</h5>
                <pre style="padding-top: 0; padding-bottom: 0;background-color: #232323; border-color: #232323;"><code class="json">{
    "id": 123,
    "reference": "NO9274DYBS",
    "restaurant": {
        "id": 1
    },
    "type": {
        "id": 2,
        "name": "Livraison par le restaurant"
    },
    "status": {
        "id": 1,
        "name": "Incomplete"
    },
    "clientName": "Mickael Lambda",
    "clientPhone": "05541232XX",
    "clientAddressLine1": null,
    "clientAddressLine2": null,
    "clientTown": {
        "id": 4115,
        "codeINSEE": "33281",
        "name": "MERIGNAC",
        "countryCode": 33700,
        "country": {
            "id": 1,
            "name": "France",
            "code": "FR"
        }
    },
    "hourToBeReady": "2018-03-20 11:30:00",
    "precisions": null,
    "productsList": [],
    "missingParams": {
        "MISS3": {
            "code": "MISS3",
            "text": "Required params missing",
            "params": []
        },
        "MISS2": {
            "code": "MISS2",
            "text": "Your order must have at least 1 product",
            "params": []
        },
        "MISS6": {
            "code": "MISS6",
            "text": "Your command must be more expensive to been delivered in your town",
            "params": [
                15
            ]
        }
    },
    "TotalHT": "00.00",
    "TotalTTC": "00.00",
    "deliveryFee": 5,
    "extraLinesList": [],
    "restaurantAmountToCash": null,
    "amountTakenByBusiness": null
}</code></pre>
            </div>
        </section>



        <section id="commande-maj" class="row">
            <div class="col-lg-6 infos-tool">
                <h4 class="titre-page">Mettre à jour une commande</h4>

                La mise à jour des informations d'une commande en cours est assez simple, une fois la commande créée, vous pouvez la modifier autant de fois que nécessaire
                jusqu'a sa confirmation.
                Pour modifier une propriété de la commande, il suffit de préciser la nouvelle valeur dans le corps de cette requête.
                Le corps de la requête est de la même forme que pour créer une commande excepté qu'il n'est pas possible de modifier le type de commande
                (vous n'êtes pas obligé de passer tous les champs en une requête, mais il faudra les remplir tous et correctement pour que la commande soit considéré comme valide.
                Le statut de la commande ainsi que les champs manquants pour sa validité seront mis à jour en temps réel lors du traitement de cette requête.

                <br /><br />
                <h5 class="sous-titre-page">URL de la requête</h5>
                <div class="col-md-2 method">
                    <h4 class="methode-http methode-post">POST</h4>
                </div>
                <div class="col-md-10 method">
                    <h4 class="url">... /orders/{id}/update</h4>
                </div>

                <div class="params-list">
                    <h5 class="sous-titre-page">Paramètres corps de requête (body)</h5>
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Clé</th>
                            <th>Description</th>
                            <th>Exemple</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="active">
                            <td>clientName</td>
                            <td>Nom du client demandeur</td>
                            <td>Unnom Unprenom</td>
                            <td><span class="label label-danger">Obligatoire</span></td>
                        </tr>
                        <tr class="active">
                            <td>clientPhone</td>
                            <td>Numéro de télephone du client </td>
                            <td>0555555555</td>
                            <td><span class="label label-danger">Obligatoire</span></td>
                        </tr>
                        <tr class="active">
                            <td>clientAddressLine1</td>
                            <td>Adresse du client (numéro, rue/avenue)</td>
                            <td>1 rue des coquelicots</td>
                            <td><span class="label label-danger">Obligatoire</span></td>
                        </tr>
                        <tr class="active">
                            <td>clientAddressLine2</td>
                            <td>Complément d'adresse du client (batiment, interphone...)</td>
                            <td>Batiment 2</td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        <tr class="active">
                            <td>clientTown</td>
                            <td>ville / code INSEE du client (identifiant d'objet Town)</td>
                            <td>8088</td>
                            <td><span class="label label-danger">Obligatoire</span></td>
                        </tr>
                        <tr class="active">
                            <td>precisions</td>
                            <td>Précisions supplémentaires du client </td>
                            <td>Pas trop de ketchup</td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        <tr class="active">
                            <td>hourToBeReady</td>
                            <td>Heure à laquelle la commande doit être prête (+ ou - le temps de livraison du restaurant)</td>
                            <td>19:30</td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        <tr class="active">
                            <td>amountSupported</td>
                            <td>Montant HT de la commission que vous prenez sur la commande</td>
                            <td>10.00</td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        <tr class="active">
                            <td>amountToCash</td>
                            <td>Montant à encaisser par le restaurant au service de la commande</td>
                            <td>20.00</td>
                            <td><span class="label label-primary">Facultatif</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6 exemple-tool">
                <h5 class="titre-exemple">Exemple de réponse</h5>
                <pre style="padding-top: 0; padding-bottom: 0;background-color: #232323; border-color: #232323;"><code class="json">{
    "id": 123,
    "reference": "NO9274DYBS",
    "restaurant": {
        "id": 1
    },
    "type": {
        "id": 2,
        "name": "Livraison par le restaurant"
    },
    "status": {
        "id": 2,
        "name": "Confirmable"
    },
    "clientName": "Mickael Lambda",
    "clientPhone": "05541232XX",
    "clientAddressLine1": null,
    "clientAddressLine2": null,
    "clientTown": {
        "id": 4115,
        "codeINSEE": "33281",
        "name": "MERIGNAC",
        "countryCode": 33700,
        "country": {
            "id": 1,
            "name": "France",
            "code": "FR"
        }
    },
    "hourToBeReady": "2018-03-20 11:30:00",
    "precisions": null,
    "productsList": [
        {
            "referenceKey": 137,
            "product": {
                "id": 9,
                "name": "Margherita",
                "description": "Base sauce tomate, mozzarella",
                "price": 10,
                "imgUrl": "http://resto.connectmyresto.com/assets/img/restaurants/1/products/1518785790_margherita.png",
                "position": 1,
                "isActive": true,
                "isSoldOut": false,
                "isTakeAwayAuthorized": true
            },
            "totalPrice": "15.00",
            "propertiesList": [
                {
                    "id": 2,
                    "name": "Pâte Fromagère",
                    "optionGroup": {
                        "id": 1,
                        "name": "Type de pâte"
                    },
                    "price": 2
                },
                {
                    "id": 4,
                    "name": "Grande",
                    "optionGroup": {
                        "id": 2,
                        "name": "Taille"
                    },
                    "price": 3
                }
            ],
            "supplementsList": []
        }
    ],
    "missingParams": null,
    "TotalHT": "14.48",
    "TotalTTC": "15.00",
    "deliveryFee": 5,
    "extraLinesList": null,
    "restaurantAmountToCash": 15.00,
    "amountTakenByBusiness": 5.00
}</code></pre>
            </div>
        </section>



        <section id="commande-confirmer" class="row">
            <div class="col-lg-6 infos-tool">
                <h4 class="titre-page">Confirmer une commande</h4>

                Enfin, la dernière étape est d'envoyer la commande valide au restaurant, pour se faire, il est nécessaire de la confirmer.
                Pour cela, une simple requête POST sur l'URL ci-dessous suffit.
                Vous devrez veiller à ce que la commande possède bien le statut "Confirmable" avant de l'envoyer, sous peine de recevoir un message d'erreur.
                Au fur et à mesure de la construction de la commande, vous pourrez ajouter les informations manquantes pour que la commande soit valide. (MissingParams)
                Si la commande est valide et confirmée, elle sera renvoyée tel qu'elle apparaitra pour le restaurateur, si ce n'est pas le cas, un message d'erreur surviendra.

                <br /><br />
                <h5 class="sous-titre-page">URL de la requête</h5>
                <div class="col-md-2 method">
                    <h4 class="methode-http methode-post">POST</h4>
                </div>
                <div class="col-md-10 method">
                    <h4 class="url">... /orders/{orderId}/confirm</h4>
                </div>
            </div>
            <div class="col-lg-6 exemple-tool">
                <h5 class="titre-exemple">Exemple de réponse</h5>
                <pre style="padding-top: 0; padding-bottom: 0;background-color: #232323; border-color: #232323;"><code class="json">{
    "id": 123,
    "reference": "NO9274DYBS",
    "restaurant": {
        "id": 1
    },
    "type": {
        "id": 2,
        "name": "Livraison par le restaurant"
    },
    "status": {
        "id": 3,
        "name": "Ordered"
    },
    "clientName": "Mickael Lambda",
    "clientPhone": "05541232XX",
    "clientAddressLine1": null,
    "clientAddressLine2": null,
    "clientTown": {
        "id": 4115,
        "codeINSEE": "33281",
        "name": "MERIGNAC",
        "countryCode": 33700,
        "country": {
            "id": 1,
            "name": "France",
            "code": "FR"
        }
    },
    "hourToBeReady": "2018-03-20 11:30:00",
    "precisions": null,
    "productsList": [
        {
            "referenceKey": 137,
            "product": {
                "id": 9,
                "name": "Margherita",
                "description": "Base sauce tomate, mozzarella",
                "price": 10,
                "imgUrl": "http://resto.connectmyresto.com/assets/img/restaurants/1/products/1518785790_margherita.png",
                "position": 1,
                "isActive": true,
                "isSoldOut": false,
                "isTakeAwayAuthorized": true
            },
            "totalPrice": "15.00",
            "propertiesList": [
                {
                    "id": 2,
                    "name": "Pâte Fromagère",
                    "optionGroup": {
                        "id": 1,
                        "name": "Type de pâte"
                    },
                    "price": 2
                },
                {
                    "id": 4,
                    "name": "Grande",
                    "optionGroup": {
                        "id": 2,
                        "name": "Taille"
                    },
                    "price": 3
                }
            ],
            "supplementsList": []
        }
    ],
    "missingParams": null,
    "TotalHT": "14.48",
    "TotalTTC": "15.00",
    "deliveryFee": 5,
    "extraLinesList": null,
    "restaurantAmountToCash": 15.00,
    "amountTakenByBusiness": 5.00
}</code></pre>
            </div>
        </section>

    </div>

<?php include "includes/script_bas_page.php"; ?>

</body>
<script src="highlight/highlight.pack.js"></script>
<script src="js/coloration_syntaxique.js"></script>
</html>

