<?php
session_start();

$_SESSION['restaurant_id'] = 1;

/*function connexionBdd(){
    $hote = "db703654569.db.1and1.com";
    $db = "db703654569";
    $user = "dbo703654569";
    $pass = "Mate-maker33!";
    try {
        $bdd = new PDO('mysql:host='.$hote.';dbname='.$db.';charset=utf8', $user, $pass);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $bdd;
    } catch (Exception $e) {
        die('<b>Erreur de connexion à la Bdd :</b> <br>' . $e->getMessage());
    }
} 

*/
function model($restaurant_id){
    $db= connexionBdd();
    $req1 = $db->prepare('');
    $req1->bindValue(":", $, PDO::PARAM_STR);
    $req1->execute();
} 
function sanitize($input) {
    return htmlspecialchars(trim($input));
}
function sanitize_tab($input) {
    return array_map('sanitize', $input);
}

function add_category($restaurant_id, $category_name, $category_description, $category_position){
    $db = connexionBdd();

    $req1 = $db->prepare('INSERT INTO product_category(restaurant_id, name, description, position) VALUES (:restaurant_id, :category_name, :category_description, :category_position');
    $req1->bindValue(":restaurant_id", $restaurant_id, PDO::PARAM_INT);
    $req1->bindValue(":category_name", $category_name, PDO::PARAM_STR);
    $req1->bindValue(":category_description", $category_description, PDO::PARAM_STR);
    $req1->bindValue(":category_position", $category_position, PDO::PARAM_INT);
    $req1->execute();

    $req2 = $db->prepare("SELECT * FROM product_category WHERE restaurant_id = :restaurant_id AND name = : category_name");
    $req2->bindValue(":restaurant_id", $restaurant_id, PDO::PARAM_INT);
    $req2->bindValue(":category_name", $category_name, PDO::PARAM_STR);
    $req2->execute();

    return $req2->fetch();
}
function list_category($restaurant_id){
    $db= connexionBdd();
    $req1 = $db->prepare('SELECT * FROM product_category WHERE restaurant_id = :restaurant_id ORDER BY position ASC');
    $req1->bindValue(":restaurant_id", $restaurant_id, PDO::PARAM_INT);
    $req1->execute();
}  
function list_product($restaurant_id){
    $db= connexionBdd();
    $req1 = $db->prepare('SELECT * FROM product INNER JOIN product_category ON product.category_id = product_category.id WHERE restaurant_id = :restaurant_id ORDER BY product.position ASC');
    $req1->bindValue(":restaurant_id", $restaurant_id, PDO::PARAM_INT);
    $req1->execute();
}
function card($restaurant_id){
    $list_category = list_category($restaurant_id);
    $list_product = list_product($restaurant_id);

    foreach ($list_category as $category_key => $category_value) {
        foreach ($list_product as $product_key => $product_value) {
            if($product_value["category_id"] == $category_value[0]){
                $list_category['product'][] = $product_value;
            }
        }
    }
    $card = $list_category;
    return $carte;
}