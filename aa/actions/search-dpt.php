<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/04/2018
 * Time: 15:10
 */

include '../init.php';

if (isset($_POST) && !empty($_POST)){

    $dpt = htmlspecialchars(trim($_POST["dpt"]));

    $req = $bdd->prepare("SELECT * FROM restaurant INNER JOIN delivery_town ON restaurant.town_id = delivery_town.id INNER JOIN departements ON delivery_town.departement_id = departements.id WHERE departements.code = :search");
    $req->bindValue(":search", $dpt, PDO::PARAM_STR);
    $req->execute();
    while ($res = $req->fetch()){
        $reqCountCountratActifs = $bdd->prepare("SELECT COUNT(id) FROM contract WHERE restaurant_id = :id AND status_id != 3");
        $reqCountCountratActifs->bindValue(":id", $res["id"], PDO::PARAM_INT);
        $reqCountCountratActifs->execute();
        $nbContratActifs = $reqCountCountratActifs->fetchColumn(); ?>
        <div class="cont-restau">
            <div class="row">
                <div class="col-lg-3">
                    <h4><?php echo $res["name"] ?></h4>
                </div>
                <div class="col-lg-3">
                    <?php echo $nbContratActifs; ?> contrats actifs
                </div>
                <div class="col-lg-3">
                    <a href="restaurant.php?id=<?php echo $res[0]; ?>"><button class="btn btn-info">Voir le restaurant</button></a>
                </div>
            </div>
        </div>
    <?php }
}