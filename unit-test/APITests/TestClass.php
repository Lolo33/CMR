<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 06/06/2018
 * Time: 10:50
 */

namespace APITests;
use PDO;
use PDOException;

class TestClass
{

    protected $url;
    public $nom;
    private $bdd;

    function __construct()
    {
        $this->url = \TestConst::URL_BASE_PROD;
    }

    protected function getBddInstance(){
        if ($this->bdd != null){
            return $this->bdd;
        }else {
            $host_name = "db703654569.db.1and1.com";
            $database = "db703654569";
            $user_name = "dbo703654569";
            $password = "Mate-maker33!";
            $host_name = "localhost";
            $database = "connect_resto";
            $user_name = "root";
            $password = "";

            try {
                $this->bdd = new PDO('mysql:host=' . $host_name . ';dbname=' . $database . ';charset=utf8', '' . $user_name . '', $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                return $this->bdd;
            } catch (PDOException $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }
    }

}