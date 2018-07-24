<?php
/**
 * Created by PhpStorm.
 * User: Loïc Sicaire
 * Date: 29/08/2017
 * Time: 12:49
 */

namespace CmrSdk;

/**
 * Class Autoloader
 */
class Autoloader{

    /**
     * Enregistre notre autoloader
     */
    static function register(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    static function registerRacine(){
        spl_autoload_register(array(__CLASS__, 'autoloadRacine'));
    }

    static function registerExceptions(){
        spl_autoload_register(array(__CLASS__, 'autoloadException'));
    }

    /**
     * Inclue le fichier correspondant à notre classe
     * @param $class string Le nom de la classe à charger
     */
    static function autoload($class){
        if(strpos($class, __NAMESPACE__.'\\') === 0){
            $class = str_replace(__NAMESPACE__.'\\', '', $class);
            $class = str_replace('\\', '/', $class);
            require __DIR__.'/'.$class.'.php';
        }
    }

    /**
     * Inclue le fichier correspondant à notre classe
     * @param $class string Le nom de la classe à charger
     */
    static function autoloadRacine($class){
        if(strpos($class, __NAMESPACE__.'\\') === 0){
            $class = str_replace(__NAMESPACE__.'\\', '', $class);
            $class = str_replace('\\', '/', $class);
            require __DIR__.'/'.$class.'.php';
        }
    }

    /**
     * Inclue le fichier correspondant à notre classe
     * @param $class string Le nom de la classe à charger
     */
    static function autoloadException($class){
        require "../Exceptions/".$class . '.php';
    }

}

Autoloader::register();
Autoloader::registerExceptions();