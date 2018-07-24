<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Utils\Globals;
use AppBundle\Entity\Requetes;
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\ScheduleFormatted;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\DateTime;

class RestaurantController extends Controller
{

    /// FONCTIONS TRI / FORMATTAGE DES DONNEES
    /**
     * @param $Restaurant Restaurant        The restaurant Entity to order.
     * @return mixed                        The restaurant with ordered products and categories.
     */
    private function orderProductsRestaurant(Restaurant $Restaurant, $type = 1){
        $cats = [];
        foreach ($Restaurant->getCategoriesOfProducts() as $category){
            if ($category->getIsActive() == true) {
                $products = $category->getProductsList()->toArray();
                usort($products, array($this, "cmp"));
                $prods = [];
                foreach ($products as $product) {
                    if ($product->getIsActive())
                        if ($type != 2 || $type == 2 && $product->getIsTakeAwayAuthorized())
                            $prods[] = $product;
                }
                $category->setProductsList(new ArrayCollection($prods));
                $cats[] = $category;
            }
        }
        $categories = $cats;
        usort($categories, array($this, "cmp"));
        $Restaurant->setCategoriesOfProduct(new ArrayCollection($categories));
        return $Restaurant;
    }
    private function cmp($a, $b)
    {
        if ($a->getPosition() == $b->getPosition()) {
            return 0;
        }
        return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
    }

    /// fONCTIONS FORMATTAGE HORAIRES
    private function formatterHorairesRestaurant(Restaurant $Restaurant, $dates){
        $arrayNewSchedule = [];
        foreach ($Restaurant->getScheduleDeliveryList() as $schedule) {
            foreach ($dates as $date) {
                if ($schedule->getDayOpening() == $date->format("w") || $schedule->getDayClosing() == $date->format("w")){
                    $arrayNewSchedule[] = new ScheduleFormatted($schedule, $date, "FR");
                }
            }
        }
        $Restaurant->setRealHours($arrayNewSchedule);
        return $Restaurant;
    }
    private function formatterHorairesReellesRestaurant(Restaurant $Restaurant, $dates, $type = 1){
        $arrayDates = [];
        foreach ($dates as $date) {
            $arrayNewSchedule = [];
            foreach ($Restaurant->getScheduleDeliveryList() as $schedule) {
                if ($schedule->getDayOpening() == $date->format("w") || $schedule->getDayClosing() == $date->format("w")){
                    $realHourOpening = new \DateTime($schedule->getHourOpening());
                    $realHourClosing = new \DateTime($schedule->getHourClosing());
                    $now = new \DateTime();
                    if ($schedule->getDayOpening() == $now->format("w")) {
                        $realHourOpening->setDate($now->format("Y"), $now->format("m"), $now->format("d"));
                        $realHourClosing->setDate($now->format("Y"), $now->format("m"), $now->format("d"));
                        if ($now > $realHourOpening && $now < $realHourClosing){
                            $realHourOpening = $now;
                        }
                    }
                    $hourPrep = new \DateTime($Restaurant->getTimeBeforePreparation());
                    $intervalPrep = new \DateInterval("PT" . $hourPrep->format("H")."H".$hourPrep->format("i")."M".$hourPrep->format("s")."S");
                    $hourDelivery = new \DateTime($Restaurant->getTimeToDelivery());
                    $intervalDelivery = new \DateInterval("PT" . $hourDelivery->format("H")."H".$hourDelivery->format("i")."M".$hourDelivery->format("s")."S");
                    $realHourOpening->add($intervalPrep);
                    $realHourClosing->add($intervalPrep);
                    if ($type == 2) {
                        $realHourOpening->add($intervalDelivery);
                        $realHourClosing->add($intervalDelivery);
                    }
                    $schedule->setHourClosing($realHourClosing);
                    $schedule->setHourOpening($realHourOpening);
                    //var_dump($schedule->getHourOpening());
                    $arrayNewSchedule[] = new ScheduleFormatted($schedule, $date, "FR");
                }
            }
            $arrayDates[$date->format("Y-m-d")] = $arrayNewSchedule;
        }
        $Restaurant->setRealHours($arrayDates);
        return $Restaurant;
    }

    /// Fonctions statut open / close
    private function getStatutRestaurant(Restaurant $restaurant){
        $ajd = new \DateTime();
        $open = false;
        foreach ($restaurant->getScheduleDeliveryList() as $sch){
            $date_debut_schedule = new \DateTime($sch->getHourOpening());
            $date_fin_schedule = new \DateTime($sch->getHourClosing());
            if ($sch->getDayOpening() == $ajd->format("w")){
                $date_debut_schedule->setDate($ajd->format("Y"), $ajd->format("m"), $ajd->format("d"));
                $date_fin_schedule->setDate($ajd->format("Y"), $ajd->format("m"), $ajd->format("d"));
                if ($date_debut_schedule < $ajd && $date_fin_schedule > $ajd){
                    $restaurant->setOpenState(true);
                    $restaurant->setOpenStateString("Ouvert jusqu'à " . $date_fin_schedule->format("H:i"));
                    return $restaurant;
                }
            }
        }
        if ($open == false){
            $restaurant->setOpenState(false);
            $cmptDay = 0;
            $nextDay = (int)$ajd->format("w");
            while ($cmptDay <= 6) {
                if ($cmptDay == 0) {
                    $horaireJour = $this->getDoctrine()->getRepository("AppBundle:ScheduleDelivery")->findBy(array("restaurant" => $restaurant, "dayOpening" => $ajd->format("w")), array("hourOpening" => "ASC"));
                    foreach ($horaireJour as $hor) {
                        $date_debut_schedule = new \DateTime($hor->getHourOpening());
                        $date_fin_schedule = new \DateTime($hor->getHourClosing());
                        $date_debut_schedule->setDate($ajd->format("Y"), $ajd->format("m"), $ajd->format("d"));
                        $date_fin_schedule->setDate($ajd->format("Y"), $ajd->format("m"), $ajd->format("d"));
                        if ($date_debut_schedule > $ajd) {
                            $restaurant->setOpenStateString("Fermé jusqu'a aujourd'hui " . $date_debut_schedule->format("H:i"));
                            return $restaurant;
                        }
                    }
                }else{
                    $nextDay = $this->nextDay($nextDay);
                    $horaireJour = $this->getDoctrine()->getRepository("AppBundle:ScheduleDelivery")->findBy(array("restaurant" => $restaurant, "dayOpening" => $nextDay), array("hourOpening" => "ASC"));
                    if (!empty($horaireJour) && $horaireJour != null) {
                        $restaurant->setOpenStateString("Fermé jusqu'a " . $this->dayToString($nextDay) . " " . (new \DateTime($horaireJour[0]->getHourOpening()))->format("H:i"));
                        return $restaurant;
                    }else {
                        $cmptDay++;
                        continue;
                    }
                }
                $cmptDay++;
            }
        }
        return $restaurant;
    }
    private function dayToString($day){
        switch ($day){
            case 0:
                $dayString = "Dimanche";
                break;
            case 1:
                $dayString = "Lundi";
                break;
            case  2:
                $dayString = "Mardi";
                break;
            case 3:
                $dayString = "Mercredi";
                break;
            case 4:
                $dayString = "Jeudi";
                break;
            case 5:
                $dayString = "Vendredi";
                break;
            case 6:
                $dayString = "Samedi";
                break;
        }
        return $dayString;
    }
    private function nextDay($day){
        if ($day > 5)
            return 0;
        return $day+1;
    }

    private function cmpOpen($a, $b){
        if ($a->isOpen() != null && $b->isOpen() != null) {
            if (!$a->isOpen() && $b->isOpen())
                return -1;
            elseif (!$b->isOpen() && $a->isOpen())
                return 1;
            else
                return 0;
        }else{
            return 0;
        }
    }
    private function orderRestaurantOpen($arrayRestaurant){
        usort($arrayRestaurant,  array($this, "cmpOpen"));
        return $arrayRestaurant;
    }

    private function getTokenUser(Request $request){
        $token = $request->headers->get("Authorisation");
        $userToken = $this->getDoctrine()->getRepository("AppBundle:UserKey")->findOneBy(array("value" => $token));
        return $userToken;
    }

    private function serializeRestaurant($restaurant, $type = 1, $town = null, $dates = null){
        $tomorrow = new \DateTime();
        $tomorrow->add(new \DateInterval("P1D"));
        if ($dates == null)
            $restaurant = $this->formatterHorairesReellesRestaurant($restaurant, array(new \DateTime(), $tomorrow), $type);
        else
            $restaurant = $this->formatterHorairesReellesRestaurant($restaurant, $dates, $type);
        $restaurant = $this->getStatutRestaurant($restaurant);
        $restaurant = $this->setPaymentModesForSerialization($restaurant, $type);
        if ($type == 2){
            $restaurant = $this->setDeliveryFeesForSerialization($restaurant, $town);
        }
        return $restaurant;
    }

    private function setPaymentModesForSerialization($restaurant, $mode){
        $tab_pm = [];
        foreach ($restaurant->getPaymentModes() as $restoPM){
            if ($restoPM->getOrderType()->getId() == $mode){
                $tab_pm[] = $restoPM->getPaymentMode();
            }
        }
        $restaurant->setPaymentModes($tab_pm);
        return $restaurant;
    }

    private function setDeliveryFeesForSerialization($restaurant, $town_id){
        foreach ($restaurant->getTownsDeliveredList() as $fee) {
            if ($fee->getDeliveryTown()->getId() == $town_id) {
                $restaurant->setDeliveryFees($fee);
            }
        }
        return $restaurant;
    }



    /// COMPTES
    private function getBddInstance()
    {
        $host_name = "db703654569.db.1and1.com";
        $database = "db703654569";
        $user_name = "dbo703654569";
        $password = "Mate-maker33!";
        try {
            $bdd = new \PDO('mysql:host=' . $host_name . ';dbname=' . $database . ';charset=utf8', '' . $user_name . '', $password, array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING));
            return $bdd;
        } catch (\Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
    /**
     * @Rest\View(serializerGroups={"restaurants-dpt"})
     * @Rest\Get("/restaurants/departement/{code}")
     */
    public function getRestaurantByDepartement(Request $request)
    {
        $this->storeHistory($request);
        $dpt = $this->getDoctrine()->getRepository("AppBundle:Departement")->findOneBy(array("code" => $request->get("code")));
        $tabDpt = [];
        $bdd = $this->getBddInstance();
        $dptCompt = 0;
        $dptComptAccepted = 0;
        foreach ($dpt->getTownsList() as $town){
            $reqAccepted = $bdd->prepare("SELECT COUNT(DISTINCT (restaurant.id)) 
            FROM restaurant 
            INNER JOIN delivery_fee ON delivery_fee.restaurant_id = restaurant.id 
            INNER JOIN contract ON contract.restaurant_id = restaurant.id
            INNER JOIN contract_virgin ON contract.contract_id = contract_virgin.id
            INNER JOIN delivery_town ON delivery_fee.delivery_town_id = delivery_town.id 
            WHERE delivery_town.id = :town_id AND  contract.status_id = 4 AND contract_virgin.business_id = :bus_id;");
            $reqAccepted->bindValue(":bus_id", $this->getTokenUser($request)->getApiUser()->getBusiness()->getId(), \PDO::PARAM_INT);
            $reqAccepted->bindValue(":town_id", $town->getId(), \PDO::PARAM_INT);
            $reqAccepted->execute();
            $req= $bdd->prepare("SELECT COUNT(DISTINCT (restaurant.id)) 
            FROM restaurant 
            INNER JOIN delivery_fee ON delivery_fee.restaurant_id = restaurant.id 
            INNER JOIN delivery_town ON delivery_fee.delivery_town_id = delivery_town.id 
            WHERE delivery_town.id = :town_id;");
            $req->bindValue(":town_id", $town->getId(), \PDO::PARAM_INT);
            $req->execute();
            $comptAccepted = $reqAccepted->fetchColumn();
            $compt = $req->fetchColumn();
            $dptCompt += $compt;
            $dptComptAccepted += $comptAccepted;
            $town->setNbRestaurantsAccepted($comptAccepted);
            $town->setNbRestaurants($compt);
            $tabDpt[] = $town;
        }
        $dpt->setNbRestaurants($compt);
        $dpt->setNbRestaurantsAccepted($comptAccepted);
        $dpt->setTownsList($tabDpt);
        return $dpt;
    }
    /**
     * @Rest\View(serializerGroups={"restaurants-dpt"})
     * @Rest\Get("/restaurants/country/{code}")
     */
    public function getRestaurantByCountry(Request $request)
    {
        $this->storeHistory($request);
        $country = $this->getDoctrine()->getRepository("AppBundle:Country")->findOneBy(array("code" => $request->get("code")));
        $fr = [];
        $frComptAccepted = 0;
        $frCompt = 0;
        $bdd = $this->getBddInstance();
        foreach ($country->getDepartementsList() as $dpt){
            $reqAccepted = $bdd->prepare("SELECT COUNT(DISTINCT (restaurant.id)) 
            FROM restaurant 
            INNER JOIN delivery_fee ON delivery_fee.restaurant_id = restaurant.id 
            INNER JOIN contract ON contract.restaurant_id = restaurant.id
            INNER JOIN contract_virgin ON contract.contract_id = contract_virgin.id
            INNER JOIN delivery_town ON delivery_fee.delivery_town_id = delivery_town.id 
            INNER JOIN departements ON delivery_town.departement_id = departements.id 
            WHERE departements.id = :dpt_id AND  contract.status_id = 4 AND contract_virgin.business_id = :bus_id;");
            $reqAccepted->bindValue(":bus_id", $this->getTokenUser($request)->getApiUser()->getBusiness()->getId(), \PDO::PARAM_INT);
            $reqAccepted->bindValue(":dpt_id", $dpt->getId(), \PDO::PARAM_INT);
            $reqAccepted->execute();
            $req= $bdd->prepare("SELECT COUNT(DISTINCT (restaurant.id)) 
            FROM restaurant 
            INNER JOIN delivery_fee ON delivery_fee.restaurant_id = restaurant.id 
            INNER JOIN delivery_town ON delivery_fee.delivery_town_id = delivery_town.id 
            INNER JOIN departements ON delivery_town.departement_id = departements.id 
            WHERE departements.id = :dpt_id;");
            $req->bindValue(":dpt_id", $dpt->getId(), \PDO::PARAM_INT);
            $req->execute();
            $comptAccepted = $reqAccepted->fetchColumn();
            $frComptAccepted += $comptAccepted;
            $dpt->setNbRestaurantsAccepted($comptAccepted);
            $compt = $req->fetchColumn();
            $frCompt += $compt;
            $dpt->setNbRestaurants($compt);
            $fr[] = $dpt;
        }
        $country->setNbRestaurants($frCompt);
        $country->setNbRestaurantsAccepted($frComptAccepted);
        $country->setDepartementsList($fr);
        return $country;
    }


    /// RECUPERER UN RESTAURANT
    /**
     * @Rest\View(serializerGroups={"restaurant"})
     * @Rest\Get("/restaurants/{id}")
     */
    public function getRestaurantAction(Request $request)
    {
        $this->storeHistory($request);
        $restaurant = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->find($request->get("id"));
        $contracts = $this->getDoctrine()->getRepository("AppBundle:Contract")->findBy(array("restaurant" => $restaurant));
        $isAbleToSee = false;
        foreach ($contracts as $contract) {
            if ($contract->getContract()->getBusiness()->getId() == $this->getTokenUser($request)->getApiUser()->getBusiness()->getId() && $contract->getStatus()->getId() == 4) {
                $isAbleToSee = true;
            }
        }
        if ($restaurant != null && !empty($restaurant)) {
            if ($isAbleToSee){
                $tomorrow = new \DateTime();
                $tomorrow->add(new \DateInterval("P1D"));
                return $this->formatterHorairesReellesRestaurant($this->getStatutRestaurant($this->orderProductsRestaurant($restaurant)), array(new \DateTime(), $tomorrow));
            }else
                return Globals::errForbidden("Vous n'êtes pas abilité a voir les informations de ce restaurant. Veuillez passer un contrat avec lui!");
        }else
            return Globals::errNotFound("Ce restaurant n'existe pas");
    }
    /**
     * @Rest\View(serializerGroups={"restaurant-delivery"})
     * @Rest\Get("/restaurants/{id}/delivery")
     */
    public function getRestaurantDeliveryAction(Request $request)
    {
        $this->storeHistory($request);
        $restaurant = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->find($request->get("id"));
        $contracts = $this->getDoctrine()->getRepository("AppBundle:Contract")->findBy(array("restaurant" => $restaurant));
        $isAbleToSee = false;
        foreach ($contracts as $contract) {
            if ($contract->getContract()->getBusiness()->getId() == $this->getTokenUser($request)->getApiUser()->getBusiness()->getId() && $contract->getStatus()->getId() == 4) {
                $isAbleToSee = true;
            }
        }
        if ($restaurant != null && !empty($restaurant)) {
            if ($isAbleToSee){
                $tomorrow = new \DateTime();
                $tomorrow->add(new \DateInterval("P1D"));
                return $this->setPaymentModesForSerialization($this->formatterHorairesReellesRestaurant($this->getStatutRestaurant($this->orderProductsRestaurant($restaurant, 2)), array(new \DateTime(), $tomorrow), 2), 2);
            }else
                return Globals::errForbidden("Vous n'êtes pas abilité a voir les informations de ce restaurant. Veuillez passer un contrat avec lui!");
        }else
            return Globals::errNotFound("Ce restaurant n'existe pas");
    }
    /**
     * @Rest\View(serializerGroups={"restaurant"})
     * @Rest\Get("/restaurants/{id}/take-away")
     */
    public function getRestaurantTakeAwayAction(Request $request)
    {
        $this->storeHistory($request);
        $restaurant = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->find($request->get("id"));
        $contracts = $this->getDoctrine()->getRepository("AppBundle:Contract")->findBy(array("restaurant" => $restaurant));
        $isAbleToSee = false;
        foreach ($contracts as $contract) {
            if ($contract->getContract()->getBusiness()->getId() == $this->getTokenUser($request)->getApiUser()->getBusiness()->getId() && $contract->getStatus()->getId() == 4) {
                $isAbleToSee = true;
            }
        }
        if ($restaurant != null && !empty($restaurant)) {
            if ($isAbleToSee){
                $tomorrow = new \DateTime();
                $tomorrow->add(new \DateInterval("P1D"));
                return $this->setPaymentModesForSerialization($this->formatterHorairesReellesRestaurant($this->getStatutRestaurant($this->orderProductsRestaurant($restaurant)), array(new \DateTime(), $tomorrow), 1), 1);
            }else
                return Globals::errForbidden("Vous n'êtes pas abilité a voir les informations de ce restaurant. Veuillez passer un contrat avec lui!");
        }else
            return Globals::errNotFound("Ce restaurant n'existe pas");
    }


    /// RECUPERER UNE LISTE DE RESTAURANTS
    /**
     * @Rest\View(serializerGroups={"restaurants"})
     * @Rest\Get("/restaurants")
     */
    public function getRestaurantsAction(Request $request)
    {
        $this->storeHistory($request);
        $restaurants_ordered = [];
        $restaurants = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->findAll();
        foreach ($restaurants as $restaurant){
            $restaurant = $this->orderProductsRestaurant($restaurant);
            $restaurant = $this->formatterHorairesRestaurant($restaurant, array(new \DateTime()));
            $restaurants_ordered[] = $restaurant;
        }
        return $restaurants_ordered;
    }
    /**
     * @Rest\View(serializerGroups={"restaurants-delivery"})
     * @Rest\Get("/restaurants/delivery/{params}")
     */
    public function getRestaurantsFromAdressAction(Request $request)
    {
        $this->storeHistory($request);
        $params_tab = explode("&", $request->get("params"));
        $nb_required_options_given = 0;
        $tab_param = [];
        foreach ($params_tab as $params){
            $param_n_tab = explode("=", $params);
            if ($param_n_tab[0] == "insee"){
                $tab_param["insee"] = $param_n_tab[1];
                $nb_required_options_given++;
            }
            if ($param_n_tab[0] == "type"){
                $tab_param["type"] = $param_n_tab[1];
            }
            if ($param_n_tab[0] == "ville"){
                $tab_ville = explode("-", $param_n_tab[1]);
                if (count($tab_ville) != 2)
                    throw new BadRequestHttpException("You have to enter a valid town parameter (countryCode-name+of+the+town)");
                $nom_ville = str_replace("+", " ", $tab_ville[1]);
                $cp = intval($tab_ville[0]);
                $ville = $this->getDoctrine()->getRepository("AppBundle:Town")->findOneBy(array("countryCode" => $cp, "name" => $nom_ville));
                if (empty($ville) || $ville == null)
                    throw new BadRequestHttpException("Nous n'avons pas pu trouver cette zone");
                $tab_param["insee"] = $ville->getCodeINSEE();
                $nb_required_options_given++;
            }
            if ($param_n_tab[0] == "dates"){
                $tab_param["dates"] = $param_n_tab[1];
            }
            if ($param_n_tab[0] == "partnership"){
                $tab_param["part"] = $param_n_tab[1];
            }
            if ($param_n_tab[0] == "paymentmode"){
                $tab_param["payment"] = $param_n_tab[1];
            }
        }
        // Check parameters names
        if ($nb_required_options_given == 1){
            $restaurantsDelivered = [];
            $town = $this->getDoctrine()->getRepository("AppBundle:Town")->findOneBy(array("codeINSEE" => $tab_param["insee"]));
            // Check if restaurants deliver on this distance, and add restaurants to the list to return
            foreach ($town->getDeliveryFees() as $fees){
                $restaurantsDelivered[] = $this->serializeRestaurant($fees->getRestaurant(), 2, $town->getId());
            }
            // List of restaurants who deliver to the place send in parameters
            if (!isset($tab_param["part"]) || $tab_param["part"] == null) {
                // List of restaurants who deliver to the place send in parameters
                $restaurantsDelivered = $this->filterPartnership($request, $restaurantsDelivered, "accepted");
            }else{
                $restaurantsDelivered = $this->filterPartnership($request, $restaurantsDelivered, $tab_param["part"]);
            }
            if (isset($tab_param["type"]) && $tab_param["type"] != null){
                $restaurantsDelivered = $this->filterTypeCuisine($restaurantsDelivered, $tab_param["type"]);
            }
            //var_dump($tab_param["payment"]);
            // Filter Payment mode
            if (isset($tab_param["payment"]) && $tab_param["payment"] != null)
                $restaurantsDelivered = $this->filterPaymentModes($restaurantsDelivered, $tab_param["payment"], 2);
            return $this->orderRestaurantOpen($restaurantsDelivered);
        }else{
            throw new BadRequestHttpException("This request needs at least ville or insee parameters. Check your request parameters names");
        }
    }
    /**
     * @Rest\View(serializerGroups={"restaurants"})
     * @Rest\Get("/restaurants/take-away/{params}")
     */
    public function getRestaurantsForTakeAwayAction(Request $request)
    {
        $this->storeHistory($request);
        $params_tab = explode("&", $request->get("params"));
        $nb_required_options_given = 0;
        $tab_param = [];
        foreach ($params_tab as $params){
            $param_n_tab = explode("=", $params);
            if ($param_n_tab[0] == "lat"){
                $tab_param["lat"] = $param_n_tab[1];
                $nb_required_options_given++;
            }
            if ($param_n_tab[0] == "long"){
                $tab_param["long"] = $param_n_tab[1];
                $nb_required_options_given++;
            }
            if ($param_n_tab[0] == "dates"){
                $tab_param["dates"] = $param_n_tab[1];
            }
            if ($param_n_tab[0] == "partnership"){
                $tab_param["part"] = $param_n_tab[1];
            }
            if ($param_n_tab[0] == "paymentmode"){
                $tab_param["payment"] = $param_n_tab[1];
            }
        }

        if (!isset($tab_param["lat"]) || !isset($tab_param["long"]))
            throw new BadRequestHttpException("You need to send lat and long parameters in request's URL");
        // Set latitude and longitude of the delivery area from parameters
        $lat = $tab_param["lat"];
        $long = $tab_param["long"];

        // Definition of url parameters to request GOOGLE MAPS API
        $url_base = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric";
        $origins = "&origins=" . $lat . "," . $long;
        $key = "&key=AIzaSyAqMGCM0n8uYQK9_c92ObzKeWK-9UimGLY";

        $restaurants = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->findAll();
        $restaurantsFound = [];
        // Check if restaurants deliver on this distance, and add restaurants to the list to return
        foreach ($restaurants as $restaurant){
            // Set restaurants location to send request
            $destination = "&destinations=" . $restaurant->getLatitude() . "," . $restaurant->getLongitude();
            // Set URL for the Google Maps request
            $url = $url_base . $origins . $destination . $key;
            // Send request to Google Maps API
            $req = new Requetes($url);
            $rep = json_decode($req->sendGetRequest());
            $distance = $rep->rows[0]->elements[0]->distance->value;
            if ($distance < 5000){
                $restaurant->setDistanceFromPoint($distance);
                $restaurant = $this->getStatutRestaurant($restaurant);
                if (!isset($tab_param["dates"]) || $tab_param["dates"] == null) {
                    $restaurantsFound[] = $this->serializeRestaurant($restaurant);
                }else{
                    $dates = $tab_param["dates"];
                    $dates_to_set = [];
                    foreach ($dates as $date){
                        $dates_to_set[] = new \DateTime($date);
                    }
                    $restaurantsFound[] = $this->serializeRestaurant($restaurant, 1, null, $dates_to_set);
                }
            }
        }
        // Filter partnership
        if (!isset($tab_param["part"]) || $tab_param["part"] == null) {
            // List of restaurants who deliver to the place send in parameters
            $restaurantsFound = $this->filterPartnership($request, $restaurantsFound, "accepted");
        }else{
            $restaurantsFound = $this->filterPartnership($request, $restaurantsFound, $tab_param["part"]);
        }
        // Filter Payment mode
        if (isset($tab_param["payment"]) && $tab_param["payment"] != null)
            $restaurantsFound = $this->filterPaymentModes($restaurantsFound, $tab_param["payment"], 1);

        return $restaurantsFound;

    }
    /**
     * @Rest\View(serializerGroups={"restaurants"})
     * @Rest\Get("/restaurants/queing/{params}")
     */
    public function getRestaurantsForQueingAction(Request $request)
    {
        $this->storeHistory($request);
        $params_tab = explode("&", $request->get("params"));
        // Check number of parameters
        if (count($params_tab) >= 2){
            $lat_tab = explode("=", $params_tab[0]);
            $long_tab = explode("=", $params_tab[1]);
            // Check parameters names
            if ($lat_tab[0] == "lat" && $long_tab[0] == "long"){
                // Set latitude and longitude of the delivery area from parameters
                $lat = $lat_tab[1];
                $long = $long_tab[1];

                // Definition of url parameters to request GOOGLE MAPS API
                $url_base = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric";
                $origins = "&origins=" . $lat . "," . $long;
                $key = "&key=AIzaSyAqMGCM0n8uYQK9_c92ObzKeWK-9UimGLY";

                if (count($params_tab) == 2 || count($params_tab) == 3 && explode("=", $params_tab[2])[0] == "dates"){
                    $restaurants = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->findAll();
                    $restaurantsFound = [];
                    // Check if restaurants deliver on this distance, and add restaurants to the list to return
                    foreach ($restaurants as $restaurant){
                        // Set restaurants location to send request
                        $destination = "&destinations=" . $restaurant->getLatitude() . "," . $restaurant->getLongitude();
                        // Set URL for the Google Maps request
                        $url = $url_base . $origins . $destination . $key;
                        // Send request to Google Maps API
                        $req = new Requetes($url);
                        $rep = json_decode($req->sendGetRequest());
                        $distance = $rep->rows[0]->elements[0]->distance->value;
                        if ($distance < 5000){
                            if (count($params_tab) == 2) {
                                $tomorrow = new \DateTime();
                                $tomorrow->add(new \DateInterval("P1D"));
                                $restaurantsFound[] = $this->formatterHorairesReellesRestaurant($restaurant, array(new \DateTime(), $tomorrow), 1);
                            }else{
                                $dates_tab = explode("=", $params_tab[2]);
                                $dates = explode(",", $dates_tab[1]);
                                $dates_to_set = [];
                                foreach ($dates as $date){
                                    $dates_to_set[] = new \DateTime($date);
                                }
                                $restaurantsFound[] = $this->formatterHorairesReellesRestaurant($restaurant, $dates_to_set, 1);
                            }
                        }
                    }
                    // List of restaurants who deliver to the place send in parameters
                    return $restaurantsFound;
                }else{
                    throw new BadRequestHttpException("Number of parameters invalid");
                }

            }else{
                throw new BadRequestHttpException("This request needs lat & long parameters. Check your request parameters names");
            }
        }else{
            throw new BadRequestHttpException("This request needs 2 parameters. Check your request syntax");
        }

    }
    /**
     * @Rest\View(serializerGroups={"restaurants"})
     * @Rest\Get("/restaurants/reservations/{params}")
     */
    public function getRestaurantsForBookingAction(Request $request)
    {
        $this->storeHistory($request);
        $params_tab = explode("&", $request->get("params"));
        // Check number of parameters
        if (count($params_tab) >= 2){
            $lat_tab = explode("=", $params_tab[0]);
            $long_tab = explode("=", $params_tab[1]);
            // Check parameters names
            if ($lat_tab[0] == "lat" && $long_tab[0] == "long"){
                // Set latitude and longitude of the delivery area from parameters
                $lat = $lat_tab[1];
                $long = $long_tab[1];

                // Definition of url parameters to request GOOGLE MAPS API
                $url_base = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric";
                $origins = "&origins=" . $lat . "," . $long;
                $key = "&key=AIzaSyAqMGCM0n8uYQK9_c92ObzKeWK-9UimGLY";

                if (count($params_tab) == 2 || count($params_tab) == 3 && explode("=", $params_tab[2])[0] == "dates"){
                    $restaurants = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->findAll();
                    $restaurantsFound = [];
                    // Check if restaurants deliver on this distance, and add restaurants to the list to return
                    foreach ($restaurants as $restaurant){
                        // Set restaurants location to send request
                        $destination = "&destinations=" . $restaurant->getLatitude() . "," . $restaurant->getLongitude();
                        // Set URL for the Google Maps request
                        $url = $url_base . $origins . $destination . $key;
                        // Send request to Google Maps API
                        $req = new Requetes($url);
                        $rep = json_decode($req->sendGetRequest());
                        $distance = $rep->rows[0]->elements[0]->distance->value;
                        if ($distance < 5000){
                            if (count($params_tab) == 2) {
                                $restaurantsFound[] = $this->formatterHorairesRestaurant($restaurant, array(new \DateTime()));
                            }else{
                                $dates_tab = explode("=", $params_tab[2]);
                                $dates = explode(",", $dates_tab[1]);
                                $dates_to_set = [];
                                foreach ($dates as $date){
                                    $dates_to_set[] = new \DateTime($date);
                                }
                                $restaurantsFound[] = $this->formatterHorairesRestaurant($restaurant, $dates_to_set);
                            }
                        }
                    }
                    // List of restaurants who deliver to the place send in parameters
                    return $restaurantsFound;
                }else{
                    throw new BadRequestHttpException("Number of parameters invalid");
                }

            }else{
                throw new BadRequestHttpException("This request needs lat & long parameters. Check your request parameters names");
            }
        }else{
            throw new BadRequestHttpException("This request needs 2 parameters. Check your request syntax");
        }

    }

    /// FILTRES
    private function filterPaymentModes($restaurants, $mode, $orderType){
        if ($mode == "all")
            return $restaurants;
        $tab_sort = [];
        foreach ($restaurants as $restaurant){
            $modes = $this->getDoctrine()->getRepository("AppBundle:RestaurantPaymentMode")->findBy(array("restaurant" => $restaurant, "orderType" => $orderType));
            $modEntity = $this->getDoctrine()->getRepository("AppBundle:PaymentMode")->findOneBy(array("modeCode" => $mode));
            if (empty($modEntity) || $modEntity == null)
                throw new BadRequestHttpException("This payment mode does not exist");
            foreach ($modes as $mod) {
                if ($mode == $mod->getPaymentMode()->getModeCode()) {
                    $tab_sort[] = $restaurant;
                }
            }
        }
        return $tab_sort;
    }
    private function filterTypeCuisine($restaurants, $type){
        if ($type == "all")
            return $restaurants;
        $tab_sort = [];
        $typeEntity = $this->getDoctrine()->getRepository("AppBundle:TypeOfCuisine")->find($type);
        if (empty($typeEntity) || $typeEntity == null)
            throw new BadRequestHttpException("This type does not exist");
        foreach ($restaurants as $restaurant){
            foreach ($restaurant->getType() as $typeResto){
                if ($typeResto->getId() == $type){
                    $tab_sort[] = $restaurant;
                }
            }
        }
        return $tab_sort;
    }
    private function filterPartnership($request, $restaurants, $mode){
        if ($mode == "all")
            return $restaurants;
        $tab_sort = [];
        foreach ($restaurants as $restaurant){
            $contract = $this->getDoctrine()->getRepository("AppBundle:Contract")->findBy(array("restaurant" => $restaurant));
            $haveContract = false;
            if ($mode != "all" && $mode != "refused" && $mode != "waiting" && $mode != "accepted" && $mode != "none")
                throw new BadRequestHttpException("This partnership filter does not exist. Please type one of these partnership filter: all, accepted, refused, waiting, none");
            foreach ($contract as $contract){
                if ($contract->getContract()->getBusiness() == $this->getTokenUser($request)->getApiUser()->getBusiness()) {
                    if ($mode == "refused" && $contract->getStatus()->getId() == 1) {
                        $tab_sort[] = $restaurant;
                        break;
                    }elseif ($mode == "waiting" && $contract->getStatus()->getId() == 2) {
                        $tab_sort[] = $restaurant;
                        break;
                    }else if ($mode == "accepted" && $contract->getStatus()->getId() == 4){
                        $tab_sort[] = $restaurant;
                        break;
                    }
                }else {
                    if ($mode == "none")
                        $tab_sort[] = $restaurant;
                }
            }
        }
        return $tab_sort;
    }






    /// AUTRES
    /**
     * @Rest\View(serializerGroups={"restaurants"})
     * @Rest\Get("/region/{cc}/restaurants/{mode}")
     */
    public function getRestaurantByRegionAction(Request $request){
        $this->storeHistory($request);
        $cp = $request->get("cc");
        if ($request->get("mode") == "delivery") {
            $town = $this->getDoctrine()->getRepository("AppBundle:Town")->findOneBy(array("countryCode" => $cp));
            if (!$town)
                throw new BadRequestHttpException("This region is not delivered at this moment");
            return $town->getRestaurants();
        }elseif ($request->get("mode") == "location"){
            $restaurants = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->findBy(array("countryCode" => $cp));
            return $restaurants;
        }
    }
    /**
     * @Rest\View(serializerGroups={"restaurant"})
     * @Rest\Get("/restaurants/{id}/products")
     */
    public function getRestaurantProductsAction(Request $request)
    {
        $this->storeHistory($request);
        $restaurant = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->find($request->get("id"));
        $restaurant = $this->orderProductsRestaurant($restaurant);
        return $restaurant->getCategoriesOfProducts();
    }
    /**
     * @Rest\View(serializerGroups={"restaurants"})
     * @Rest\Post("/restaurants/{id}/active")
     */
    public function postRestaurantActiveAction(Request $request){
        $this->storeHistory($request);
        if ($this->getUserKey($request)->getValue() == "CW3tbH8v/SLvFG8/y0UPIOnenuw=") {
            $em = $this->get('doctrine.orm.entity_manager');
            $restaurant = $this->getDoctrine()->getRepository('AppBundle:Restaurant')->find($request->get("id"));
            if (!$restaurant || empty($restaurant))
                return Globals::errNotFound("This restaurant does not exist");
            $active = $request->get("active");
            if ($active == 1)
                $isActive = true;
            elseif($active == 0)
                $isActive = false;
            else
                throw new BadRequestHttpException("The value for the field \"active\" is not supported. Verify this value");
            $restaurant->setActive($isActive);
            $em->merge($restaurant);
            $em->flush();
            return $restaurant;
        }else
            return Globals::errForbidden("You are not allowed to access this url");
    }
    /**
     * @Rest\View(serializerGroups={"restaurants"})
     * @Rest\Get("/country/{code}/restaurants/{mode}")
     */
    public function getRestaurantsFromCountryAction(Request $request)
    {
        $this->storeHistory($request);
        $restaurants_ordered = [];
        $country = $this->getDoctrine()->getRepository("AppBundle:Country")->findOneBy(array("code" => $request->get("code")));
        if (!$country)
            return Globals::errNotFound("This country was not found");
        if ($request->get("mode") == "delivery") {
            $dts = $this->getDoctrine()->getRepository("AppBundle:Town")->findBy(array("country" => $country));
            $restaurants = [];
            foreach ($dts as $dt){
                $restaurants[] = $dt->getRestaurants();
            }
            foreach ($restaurants as $restaurant)
                $restaurants_ordered[] = $restaurant;
        }elseif ($request->get("mode") == "location") {
            $restaurants = $country->getRestaurantsList();
            foreach ($restaurants as $restaurant) {
                $restaurants_ordered[] = $this->orderProductsRestaurant($restaurant);
            }
        }else
            throw new BadRequestHttpException("This search mode is not available");
        return $restaurants_ordered;
    }


}
