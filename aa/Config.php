<?php

/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 23/04/2018
 * Time: 12:18
 */
class Config
{

    const NOM_SITE = "CONNECT MY RESTO";
    private static $bdd = null;

    /**
     * @return PDO
     */
    public static function getBddInstance()
    {
        if (Config::$bdd != null){
            return Config::$bdd;
        }else {
            $host_name = "db703654569.db.1and1.com";
            $database = "db703654569";
            $user_name = "dbo703654569";
            $password = "Mate-maker33!";
            $host_name = "db732738821.db.1and1.com";
            $database = "db732738821";
            $user_name = "dbo732738821";
            $password = "Mate-maker33!";
            $host_name = "localhost";
            $database = "connect_resto";
            $user_name = "root";
            $password = "";

            try {
                Config::$bdd = new PDO('mysql:host=' . $host_name . ';dbname=' . $database . ';charset=utf8', '' . $user_name . '', $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                return Config::$bdd;
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }
    }



}