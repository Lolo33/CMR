<?php
/**
 * Created by PhpStorm.
 * User: Loïc Sicaire
 * Date: 29/08/2017
 * Time: 15:54
 */

namespace CmrSdk\Actions;

use CmrSdk\Core\CoreClassInterface;
use CmrSdk\Exceptions\PermissionException;
use CmrSdk\Exceptions\ResponseException;
use CmrSdk\Requetes;

class Actions extends Requetes
{

    protected $objet;
    protected $modeProduction = false;

    public function __construct()
    {
        parent::__construct();
    }

    /// METHODES D'ACTION :: Effectuer une action sur un objet (à utiliser)
    private function PermissionDenied(){
        throw new PermissionException("Vous n'avez pas les droits pour effectuer cette action sur cette ressource");
    }
    /**
     * Envoie une requete pour récupérer une ressource par son id. Retourne le ressource souhaité sous forme d'objet.
     * @param int $id id de la ressource
     * @return CoreClassInterface           Un objet vide implémentant l'interface Core (sert à lister les propriétés)
     * @throws PermissionException              Leve une exception si l'utilisateur n'a pas la permission d'accès a cette ressource
     */
    public function Get($id)
    {
        return $this->PermissionDenied();
    }
    /**
     * Envoie une requete pour récupérer toutes les ressources et retourne un tablea d'objet hydraté
     * @return array|CoreClassInterface
     */
    protected function GetAll()
    {
        return $this->PermissionDenied();
    }
    /**
     * Envoie une requete pour ajouter une ressource, et retourne un objet du type de la ressource ajoutée.
     * @param \CmrSdk\Core\CoreClassInterface $obj
     * @return array|\CmrSdk\Core\CoreClassInterface
     */
    protected function Creer(CoreClassInterface $obj)
    {
        $content = $obj->serializeProperties();
        $reponse = $this->convertJsonToPHP($obj, $this->sendPostRequest($content));
        return $reponse;
    }
    /**
     * Envoie une requete pour supprimer une ressource, retourne true si la ressouce à bien été effacée, le code d'erreur HTTP sinon
     * @param int $id l'ID de la ressource à supprimer
     * @return bool|mixed                       true si la requete à bien fonctionné, 404 ou 403 sinon
     */
    protected function Supprimer($id)
    {
        $url = $this->url;
        $this->url .= "/" . $id;
        $rep = $this->sendDeleteRequest();
        $this->url = $url;
        return $rep;
    }

    protected function getOneBaseMethod($id){
        $url = $this->url;
        $this->url .= "/" . $id;
        $rep = $this->convertJsonToPHP($this->objet, $this->sendGetRequest());
        $this->url = $url;
        return $rep;
    }

    protected function getAllBaseMethod(){
        $aray_json = json_decode($this->sendGetRequest());
        $new_array_obj = array();
        if (is_array($aray_json)) {
            foreach ($aray_json as $unObjJson) {
                $rep_obj = $this->convertJsonToPHP($this->objet, json_encode($unObjJson));
                $new_array_obj[] = $rep_obj;
            }
        } else {
            $rep_obj = $this->convertJsonToPHP($this->objet, json_encode($aray_json));
            $new_array_obj[] = $rep_obj;
        }
        return $new_array_obj;
    }


    /// METHODES INTERNES :: Méthodes gérant fonctionnement des méthodes d'action
    protected function convertJsonToPHP($obj, $reponse_json)
    {
        $array_user_json = json_decode($reponse_json);
        $new_obj = $this->hydraterObjet($obj, $array_user_json);
        if (method_exists($new_obj, "truncObjetVide"))
            $new_obj->truncObjetVide();
        return $new_obj;
    }

    protected function hydraterObjet(CoreClassInterface $objet, $array_user_json)
    {
        $objet = clone ($objet);
        // On parcours toutes les propriétés de l'objet de base (méthode iterateProperties)
        foreach ($objet->iterateProperties() as $key => $val) {
            $getter = 'get' . ucfirst($key);
            if (method_exists($objet, $getter)) {
                if (is_array($val)) {
                    $setter = 'add' . ucfirst($key);
                } else {
                    $setter = 'set' . ucfirst($key);
                }
                if (isset($array_user_json->$key) && method_exists($objet, $setter)) {
                    // Valeur de la propriété : peut être de type "normal" (int, string...), Objet STD, ou tableau d'objets STD
                    $valeur_a_set = $array_user_json->$key;
                    //var_dump($key);
                    if (is_array($val)){
                        $firstElement = $val[0];
                        if (isset($firstElement)) {
                            $methodRemoveFirst = "init" . ucfirst($key);
                            $objet->$methodRemoveFirst();
                            foreach ($valeur_a_set as $v)
                                $objet->$setter($this->hydraterObjet(new $firstElement(), $v));
                        }
                    }else if ($val instanceof CoreClassInterface) {
                        $className = get_class($val);
                        $objet->$setter($this->hydraterObjet(new $className(), $valeur_a_set));
                    }else{
                        $objet->$setter($valeur_a_set);
                    }
                }
            }
        }
        return $objet;
    }

}