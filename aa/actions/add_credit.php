<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/05/2018
 * Time: 13:37
 */

include "../init.php";
include "../stripe.php";

redirectIfNotConnecte();
$user = unserialize($_SESSION["user"]);


$req_bus = $bdd->prepare("SELECT * FROM business WHERE id = :busId");
$req_bus->bindValue(":busId", $user["business_id"], PDO::PARAM_INT);
$req_bus->execute();
$bus = $req_bus->fetch();

$amount = htmlspecialchars(trim($_POST["amount"]));
$total_stripe = intval(100 * $amount);

if (isset($_POST['stripeToken'])) {
    $source = add_source_for_stripe($bus["stripe_id"], $_POST['stripeToken']);
    $charge = charge_stripe_by_source($bus["stripe_id"], $source->id, $total_stripe, true);
    if ( isset($charge->status) AND $charge->status == "succeeded" ) {
        $req = $bdd->prepare("UPDATE business SET solde = :sold WHERE id = :id");
        $req->bindValue(":id", $bus["id"], PDO::PARAM_INT);
        $req->bindValue(":sold", $bus["solde"] + $amount, PDO::PARAM_INT);
        $req->execute();
    }
}elseif (isset($_POST["selected-card"])){
    $source_id = htmlspecialchars(trim($_POST["selected-card"]));
    $charge = charge_stripe_by_source($bus["stripe_id"], $source_id, $total_stripe, true);
    if ( isset($charge->status) AND $charge->status == "succeeded" ) {
        $req = $bdd->prepare("UPDATE business SET solde = :sold WHERE id = :id");
        $req->bindValue(":id", $bus["id"], PDO::PARAM_INT);
        $req->bindValue(":sold", $bus["solde"] + $amount, PDO::PARAM_INT);
        $req->execute();
    }
}
header("Location: ../dashboard.php");