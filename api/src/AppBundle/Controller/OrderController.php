<?php
namespace AppBundle\Controller;

use AppBundle\Entity\ExtraLine;
use AppBundle\Entity\Notification;
use AppBundle\Entity\OrderMenuProduct;
use AppBundle\Entity\Utils\Globals;
use AppBundle\Entity\Menu;
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderMenu;
use AppBundle\Entity\OrderProduct;
use AppBundle\Entity\OrderStatusHour;
use AppBundle\Entity\Product;
use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Utils\MissingParam;
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

class OrderController extends Controller
{

    /**
     * @param $car int      nombre de caractères
     * @return string       chaine aléatoire de longueur $car
     */
    function random($car) {
        $string = "";
        $chaine = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        srand((double)microtime()*1000000);
        for($i=0; $i<$car; $i++) {
            $string .= $chaine[rand()%strlen($chaine)];
        }
        return $string;
    }

    private function getRestaurant($idRestau){
        // Verify that restaurant id is not empty in the request body
        if (empty($idRestau))
            throw new BadRequestHttpException("You must specify a restaurant ID to add an order");
        $restau = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->find($idRestau);
        // Verify that the restaurant really exist
        if (!$restau || empty($restau))
            throw new BadRequestHttpException("This restaurant does not exist");
        // Verify that restaurant is ready to receive orders
        if (!$restau->getActive())
            throw new BadRequestHttpException("This restaurant does not accept orders right now.");
        return $restau;
    }
    private function getBusiness(Request $request){
        $token = $this->getUserKey($request);
        $buisness = $token->getApiUser()->getBusiness();
        if ($buisness != null)
            return $buisness;
        else
            throw new BadRequestHttpException("Your API key is not linked with a business. Contact support");
    }
    private function getOrder($idOrder){
        if ($idOrder == null)
            throw new BadRequestHttpException("You need to specify order");
        $order = $this->getDoctrine()->getRepository("AppBundle:Order")->find($idOrder);
        if (!$order || empty($order) || $order == null){
            throw new BadRequestHttpException("This order was not found");
        }
        return $order;
    }

    private function verifyWorkWith($buisness, $type, Restaurant $restau){
        $workWith = false;
        foreach ($restau->getContractsList() as $contract){
            if ($contract->getContract()->getBusiness()->getId() == $buisness->getId() && $type->getId() == $contract->getContract()->getOrderType()->getId() && $contract->getStatus()->getId() == 4)
                $workWith = true;
        }
        if (!$workWith)
            throw new BadRequestHttpException("This restaurant doesn't work with you! Be sure you use the right API key or you use the right orderType");
    }
    private function verifyBusinessOwnOrder($business, $order){
        if ($order->getBusiness() != $business)
            throw new BadRequestHttpException("You don't own this order.");
    }
    private function orderVerifications(Request $request, $order){
        $business = $this->getBusiness($request);
        $this->verifyWorkWith($business, $order->getType(), $order->getRestaurant());
        $this->verifyBusinessOwnOrder($business, $order);
    }

    private function isScheduleDeliveryValid($restau, $hourToBeReady){
        // Verify the restaurant can prepare this order for the hour the client asked him
        $prepTimeDT = new \DateTime($restau->getTimeBeforePreparation());
        $prepTimeInterval = new \DateInterval("PT" . $prepTimeDT->format("H") . "H" . $prepTimeDT->format("i") . 'M' . $prepTimeDT->format("s") . "S");
        $prepHour = (new \DateTime())->add($prepTimeInterval);
        if (new \DateTime($hourToBeReady) < $prepHour)
            return false;
        $hourValid = false;
        foreach ($restau->getScheduleDeliveryList() as $schedule){
            $hourDateTime = new \DateTime($hourToBeReady);
            if ($schedule->getDayOpening() == $hourDateTime->format("w")){
                $hourOpening = new \DateTime($hourDateTime->format("Y-m-d") . " " . (new \DateTime($schedule->getHourOpening()))->format("H:i:s"));
                $hourClosing = new \DateTime($hourDateTime->format("Y-m-d") . " " . (new \DateTime($schedule->getHourClosing()))->format("H:i:s"));
                $hourPrep = new \DateTime($restau->getTimeBeforePreparation());
                $intervalPrep = new \DateInterval("PT" . $hourPrep->format("H")."H".$hourPrep->format("i")."M".$hourPrep->format("s")."S");
                $hourDelivery = new \DateTime($restau->getTimeBeforePreparation());
                $intervalDelivery = new \DateInterval("PT" . $hourDelivery->format("H")."H".$hourDelivery->format("i")."M".$hourDelivery->format("s")."S");
                $hourOpening->add($intervalDelivery);
                $hourOpening->add($intervalPrep);
                $hourClosing->add($intervalDelivery);
                $hourClosing->add($intervalPrep);
                if ($hourOpening < new \DateTime($hourToBeReady) && $hourClosing > new \DateTime($hourToBeReady))
                    $hourValid = true;
            }
        }
        return $hourValid;
    }
    private function setStatus($order, $idStatus){
        $em = $this->get('doctrine.orm.entity_manager');
        // CREATING ORDERSTATUSHOUR
        // Add a new status "Ordered" at datetime:now for the order we just create
        if (count($order->getStatusList()) > 0) {
            foreach ($order->getStatusList() as $statusOrder) {
                $statusOrder->setCurrent(false);
                $em->merge($statusOrder);
            }
        }
        $status = new OrderStatusHour();
        $status->setStatus($this->getDoctrine()->getRepository("AppBundle:OrderStatus")->find($idStatus));
        $status->setHour(new \DateTime("now"));
        $status->setOrder($order);
        $status->setCurrent(true);
        $order->addStatusList($status);
        $em->persist($status);
        $em->flush();
    }
    private function setMissingParams(Order $order){
        $isValid = true;
        $scheduleText = new MissingParam("MISS1", "The Hour of the order is incorrect");
        $productsText = new MissingParam("MISS2","Your order must have at least 1 product");
        $fieldsText = new MissingParam("MISS3","Required params missing");
        $soldOutText = new MissingParam("MISS4","Some products you ordered are in sold-out");
        $townOutOfRangeText = new MissingParam("MISS5","This restaurant does not deliver in this town");
        $townMinOrderText = new MissingParam("MISS6","Your command must be more expensive to been delivered in your town");
        if ($order->getClientPhone() == null || $order->getClientName() == null || new \DateTime($order->getHourToBeReady()) == null || $order->getClientAddressLine1() == null || $order->getClientTown() == null || $order->getRestaurantAmountToCash() == null || $order->getAmountTakenByBusiness() == null){
            $isValid = false;
            $order->addMissingParams($fieldsText);
        }
        if ($order->getHourToBeReady() != null && !$this->isScheduleDeliveryValid($order->getRestaurant(), $order->getHourToBeReady())){
            $isValid = false;
            $order->addMissingParams($scheduleText);
        }
        if (count($order->getProductsList()) == 0){
            $isValid = false;
            $order->addMissingParams($productsText);
        }else{
            $countProdSO = 0;
            foreach ($order->getProductsList() as $orderProd){
                if ($orderProd->getProduct()->getIsSoldOut()) {
                    $countProdSO++;
                    $soldOutText->addParam($orderProd->getReferenceKey());
                }
            }
            if ($countProdSO > 0) {
                $isValid = false;
                $order->addMissingParams($soldOutText);
            }
        }
        if ($order->getType()->getId() != 1) {
            if ($order->getClientTown() != null) {
                $isTownDelivered = false;
                foreach ($order->getRestaurant()->getTownsDeliveredList() as $deliveryFee) {
                    if ($deliveryFee->getDeliveryTown() == $order->getClientTown()) {
                        $isTownDelivered = true;
                        if ($order->TotalRestaurantTTC() < $deliveryFee->getMinOrder()) {
                            $isValid = false;
                            $townMinOrderText->addParam($deliveryFee->getMinOrder());
                            $order->addMissingParams($townMinOrderText);
                        }
                    }
                }
                if (!$isTownDelivered) {
                    $isValid = false;
                    $order->addMissingParams($townOutOfRangeText);
                }
            } else
                $isValid = false;
        }
        if ($isValid)
            $this->setStatus($order, 2);
        else
            $this->setStatus($order, 1);
        return $order;
    }

    private function addOrderProduct(Request $request, $type, $order){
        $em = $this->get('doctrine.orm.entity_manager');
        // CREATING ORDERED PRODUCTS
        // Add all products with their options and supplements
        $orderProd = new OrderProduct();
        $prod = $this->getDoctrine()->getRepository("AppBundle:Product")->find($request->get("id"));
        if ($type->getId() != 2 && !$prod->getIsTakeAwayAuthorized())
            throw new BadRequestHttpException("This product is not available for this orderType : " . $prod->getName());
        if (!$prod->getIsActive())
            throw new BadRequestHttpException("This product is no longer available" . $prod->getName());
        if ($prod->getIsSoldOut())
            throw new BadRequestHttpException("Sold out for this product : " . $prod->getName());
        $orderProd->setPrecisions($request->get("precisions"));
        $orderProd->setIsReady(false);
        // CHECKS FOR SUPPLEMENTS
        if ($request->get("supplements") != null) {
            // Verify that all the supplements are really linked to the products the client has ordered
            $supplements = $request->get("supplements");
            foreach ($supplements as $supplement) {
                $sup = $this->getDoctrine()->getRepository("AppBundle:Supplement")->find($supplement["id"]);
                if (!$sup)
                    throw new BadRequestHttpException("This supplement does not exist");
                $supGrps = $this->getDoctrine()->getRepository("AppBundle:ProductSupplementCategory")->findBy(array("supGroup" => $sup->getSupGroup()));
                $prodOwnSup = false;
                foreach ($supGrps as $grp)
                    if ($grp->getProduct()->getId() == $prod->getId())
                        $prodOwnSup = true;
                if ($prodOwnSup)
                    $orderProd->addSupplementsList($sup);
                else
                    throw new BadRequestHttpException("The product " . $prod->getName() . " can't get this supplement.");
            }
        }
        // CHECK FOR OPTIONS
        if ($request->get("options") != null) {
            $groupsProduct = [];
            $options = $request->get("options");
            // Verify that all the options are really linked to the products the client has ordered
            foreach ($options as $option) {
                $opt = $this->getDoctrine()->getRepository("AppBundle:Property")->find($option["id"]);
                $groupsProduct[] = $opt->getOptionGroup();
                $prodOpts = $this->getDoctrine()->getRepository("AppBundle:ProductPropertyCategory")->findBy(array("optionGroup" => $opt->getOptionGroup()));
                $prodOwnOption = false;
                foreach ($prodOpts as $prodOpt) {
                    if ($prodOpt->getProduct()->getId() == $prod->getId())
                        $prodOwnOption = true;
                }
                if ($prodOwnOption)
                    $orderProd->addPropertiesList($opt);
                else
                    throw new BadRequestHttpException("This product can't get this option");
            }
            // Verify that all the required options have been choosed for the product
            $isPresent = true;
            foreach ($prod->getProperties() as $group)
                if (!in_array($group->getOptionGroup(), $groupsProduct))
                    $isPresent = false;
            if (!$isPresent)
                throw new BadRequestHttpException("Missing options for this product. Forgot any required option ?");
            foreach ($prod->getProperties() as $group){
                if ($group->getOptionGroup()->getIsUnique()){
                    $countProdCat = 0;
                    foreach ($options as $option) {
                        $opt = $this->getDoctrine()->getRepository("AppBundle:Property")->find($option["id"]);
                        if ($opt->getOptionGroup()->getId() == $group->getOptionGroup()->getId())
                            $countProdCat++;
                    }
                    if ($countProdCat > 1)
                        throw new BadRequestHttpException("This product can't have more than 1 property for the category : " . $opt->getOptionGroup()->getName());
                }
            }
        }
        $orderProd->setProduct($prod);
        $orderProd->setOrder($order);
        $order->addProductsList($orderProd);
        $em->persist($orderProd);
        return $order;

    }
    private function removeOrderProduct(OrderProduct $orderProd, Order $order){
        $em = $this->get('doctrine.orm.entity_manager');
        if ($orderProd->getOrder() != $order)
            throw new BadRequestHttpException("This order don't own this product.");
        /*foreach ($orderProd->getOptionsList() as $option)
            $em->remove($option);
        foreach ($orderProd->getSupplementsList() as $sup)
            $em->remove($sup);*/
        $order->removeProductsList($orderProd);
        $em->remove($orderProd);
        $em->flush();
        return $order;
    }

    private function setExtraLines(Request $request, $order){
        $em = $this->get('doctrine.orm.entity_manager');
        // Adding ExtraLines
        if ($request->get("extraLines") != null) {
            $extraLines = $request->get("extraLines");
            foreach ($extraLines as $extraLine){
                $line = new ExtraLine();
                $line->setText($extraLine["text"]);
                $line->setValue($extraLine["value"]);
                $line->setOrderConcerned($order);
                $line->setIsRestaurant(false);
                $order->addExtraLinesList($line);
                $em->persist($line);
            }
        }
    }
    private function setOrderParams(Request $request, Order $order){
        $phone = $request->get("clientPhone");
        $name = $request->get("clientName");
        $adressLine1 = $request->get("clientAddressLine1");
        $amountSupported = $request->get("amountTakenByBusiness");
        $amountToCash = $request->get("restaurantAmountToCash");
        $adressLine2 = null;
        if ($request->get("clientAddressLine2") != null){
            $adressLine2 = $request->get("clientAddressLine2");
        }
        $clientTown = null;
        if ($request->get("clientTown") != null) {
            $townId = $request->get("clientTown");
            $clientTown = $this->getDoctrine()->getRepository("AppBundle:Town")->find($townId);
            if ($clientTown == null || empty($clientTown))
                throw new BadRequestHttpException("This town does not exist.");
        }
        $hourToBeReady = null;
        if ($request->get("hourToBeReady") != null){
            $hourToBeReady = new \DateTime($request->get("hourToBeReady"));
        }else{
            $hourToBeReady = new \DateTime();
        }
        $precisions = $request->get("precisions");
        // CREATING ORDER
        if ($name != null)
            $order->setClientName($name);
        if ($phone != null)
            $order->setClientPhone($phone);
        if ($adressLine1 != null)
            $order->setClientAddressLine1($adressLine1);
        if ($adressLine2 != null)
            $order->setClientAddressLine2($adressLine2);
        if ($amountSupported != null)
            $order->setAmountTakenByBusiness($amountSupported);
        if ($amountToCash != null)
            $order->setRestaurantAmountToCash($amountToCash);
        if ($hourToBeReady != null)
            $order->setHourToBeReady($hourToBeReady);
        $order->setPrecisions($precisions);
        if ($clientTown != null) {
            $order->setClientTown($clientTown);
        }
        $this->setExtraLines($request, $order);
        return $order;
    }

    private function calculReductions($order){
        return $order;
    }
    private function debitBusiness($order){
        $em = $this->getDoctrine()->getManager();
        $com = $order->TotalTTCFloat() * 0.01;
        var_dump($com);
        $business = $order->getBusiness();
        if ($business->getSold() > $com){
            $business->setSold($business->getSold() - $com);
            $em->merge($business);
            $em->flush();
        }else{
            throw new BadRequestHttpException("Your account havn't enough money. Please charge it");
        }
    }


    /**
     * @Rest\View(serializerGroups={"orders"})
     * @Rest\Post("/orders/{id}/confirm")
     */
    public function confirmOrderAction(Request $request){
        $this->storeHistory($request);
        $idOrder = $request->get("id");
        $order = $this->getOrder($idOrder);
        $this->orderVerifications($request, $order);

        // Si la commande est confirmable
        if ($order->getActiveStatus()->getStatus()->getId() == 2) {
            $em = $this->get('doctrine.orm.entity_manager');
            // Confirme la commande
            $this->setStatus($order, 3);
            // Notification du restaurant pour une nouvelle commande
            $notif = new Notification();
            $notif->setRestaurant($order->getRestaurant());
            $notif->setText("Une commande vient d'être passée de la part de " . $order->getBusiness()->getName() . ". Venez la consulter !");
            $notif->setIsSeen(false);
            $notif->setIsShown(false);
            $notif->setOrder($order);
            $notif->setSentAt(new \DateTime());

            // débite le compte de l'application
            $this->debitBusiness($order);

            $em->persist($notif);
            $em->merge($order);
            $em->flush();
            return $order;
        }else
            throw new BadRequestHttpException("This order is not Complete yet.");
    }

    /**
     * @Rest\View(serializerGroups={"orders"})
     * @Rest\Delete("/orders/{id}/remove-product/{id_p}")
     */
    public function removeProductAction(Request $request){
        $this->storeHistory($request);
        $idOrderProd = $request->get("id_p");
        $idOrder = $request->get("id");
        $order = $this->getOrder($idOrder);

        // Si la commande est déja confirmée, erreur 400
        if ($order->status()->getId() > 2)
            throw new BadRequestHttpException("This order is confirmed. You can't add a product, you may want to create a new order ?!");

        $this->orderVerifications($request, $order);

        // Suppression du produit
        $orderProd = $this->getDoctrine()->getRepository("AppBundle:OrderProduct")->find($idOrderProd);
        $order = $this->removeOrderProduct($orderProd, $order);

        $order = $this->calculReductions($order);

        $order = $this->setMissingParams($order);

        return $order;
    }

    /**
     * @Rest\View(serializerGroups={"orders"})
     * @Rest\Post("/orders/{id_order}/add-product")
     */
    public function addProductAction(Request $request){
        $this->storeHistory($request);
        $em = $this->get('doctrine.orm.entity_manager');
        $idOrder = $request->get("id_order");
        $order = $this->getOrder($idOrder);

        // Si la commande est confirmée, erreur 400
        if ($order->status()->getId() > 2)
            throw new BadRequestHttpException("This order is confirmed. You can't add a product, you may create a new order ?!");

        $this->orderVerifications($request, $order);

        // Ajout du produit à la commande
        $order = $this->addOrderProduct($request, $order->getType(), $order);

        // calcul des promotions eventuelles et mise à jour des paramètres manquants
        $order = $this->calculReductions($order);
        $order = $this->setMissingParams($order);

        $em->merge($order);
        $em->flush();

        return $order;
    }

    /**
     * @Rest\View(serializerGroups={"orders"})
     * @Rest\Post("/restaurants/{id_r}/orders")
     */
    public function createOrderAction(Request $req){
        $this->storeHistory($req);
        if ($req->get("orderType") == null)
            throw new BadRequestHttpException("You need to specify an orderType to send command");

        $em = $this->get('doctrine.orm.entity_manager');

        $type = $req->get("orderType");
        $type = $this->getDoctrine()->getRepository("AppBundle:OrderType")->find($type);
        if (!$type || empty($type))
            throw new BadRequestHttpException("This orderType does not exist");

        $buisness = $this->getBusiness($req);
        $restau = $this->getRestaurant($req->get("id_r"));

        $order = new Order();
        $order->setBusiness($buisness);
        $order->setRestaurant($restau);
        $order->setType($type);
        $order->setReference($this->random(10));

        $this->verifyWorkWith($buisness, $type, $restau);

        $order = $this->setOrderParams($req, $order);
        $em->persist($order);

        $order = $this->setMissingParams($order);
        $em->flush();

        return $order;
    }

    /**
     * @Rest\View(serializerGroups={"orders"})
     * @Rest\Post("/orders/{id_order}/update")
     */
    public function updateOrderAction(Request $request){
        $this->storeHistory($request);
        $em = $this->get('doctrine.orm.entity_manager');
        $idOrder = $request->get("id_order");
        $order = $this->getOrder($idOrder);

        if ($order->status()->getId() > 2)
            throw new BadRequestHttpException("This order is confirmed. You can't add a product, you may create a new order ?!");

        $this->orderVerifications($request, $order);
        $order = $this->setOrderParams($request, $order);
        $order = $this->setMissingParams($order);

        $em->merge($order);
        $em->flush();

        return $order;
    }





    /**
     * @Rest\View(serializerGroups={"orders"})
     * @Rest\Delete("/orders/{id}")
     */
    public function removeOrderAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $token = $this->getUserKey($request);
        $buisness = $token->getApiUser()->getBusiness();
        $order = $em->getRepository("AppBundle:Order")->find($request->get("id"));
        if (!empty($order)){
            if ($order->getBusiness() == $buisness) {
                foreach ($order->getProductsList() as $prodOrder)
                    $em->remove($prodOrder);
                foreach ($order->getStatusList() as $status)
                    $em->remove($status);
                $em->remove($order);
                $em->flush();
            }else
                return Globals::errForbidden("You are not allowed to delete this order.");
        }
    }

    /**
     * @Rest\View(serializerGroups={"orders"})
     * @Rest\Get("/orders")
     */
    public function getOrdersAction(Request $request)
    {
        $token = $this->getUserKey($request);
        $buisness = $token->getApiUser()->getBusiness();
        return $buisness->getOrdersList();
    }

    /**
     * @Rest\View(serializerGroups={"orders"})
     * @Rest\Get("/orders/{id}")
     */
    public function getOrderAction(Request $request)
    {
        $orderId = $request->get("id");
        $order = $this->getOrder($orderId);

        $business = $this->getBusiness($request);
        $this->verifyBusinessOwnOrder($business, $order);

        if ($order->status()->getId() <= 2)
            $this->setMissingParams($order);

        return $order;
    }



    /**
     * @Rest\View(serializerGroups={"orders"})
     * @Rest\Post("/restaurants/{restaurant}/pre-orders")
     */
    /*public function postPreOrderAction(Request $request)
    {
        // BASIC CHECKS
        // Verify that there is a product or a menu in the order
        if ($request->get("products") == null && $request->get("menus") == null)
            throw new BadRequestHttpException("Your order must have a product or a menuu");
        // Verify that all client and order required informations are present
        $requiredParams = ["restaurant", "clientPhone", "clientName", "hourToBeReady", "orderType", "clientAddressLine1", "clientCountryCode", "clientCity"];
        foreach ($requiredParams as $param)
            if ($request->get($param) == null)
                throw new BadRequestHttpException("Some required informations are missing. Missing : " . $param);

        $em = $this->get('doctrine.orm.entity_manager');

        // SETTING VALUES FROM REQUEST BODY
        $products = $request->get("products");
        $menus = $request->get("menus");
        $rest_id = $request->get("restaurant");
        if ($request->get("amountAlreadyPaid") != null)
            $amountSupported = $request->get("amountAlreadyPaid");
        else
            $amountSupported = 0;
        $phone = $request->get("clientPhone");
        $type = $request->get("orderType");
        $name = $request->get("clientName");
        $adressLine1 = $request->get("clientAddressLine1");
        $adressLine2 = null;
        if ($request->get("clientAddressLine2") != null){
            $adressLine2 = $request->get("clientAddressLine2");
        }
        $countryCode = $request->get("clientCountryCode");
        $city = $request->get("clientCity");
        $hourToBeReady = $request->get("hourToBeReady");
        $precisions = $request->get("precisions");
        // CREATING ORDER
        $order = new Order();
        $token = $this->getUserKey($request);
        $buisness = $token->getApiUser()->getBusiness();
        $order->setBusiness($buisness);
        $order->setClientName($name);
        $order->setClientPhone($phone);
        $order->setHourToBeReady(new \DateTime($hourToBeReady));
        $order->setClientAdressLine1($adressLine1);
        $order->setClientAdressLine2($adressLine2);
        $order->setClientCountryCode($countryCode);
        $order->setClientCity($city);
        $order->setPrecisions($precisions);
        $type = $this->getDoctrine()->getRepository("AppBundle:OrderType")->find($type);
        if (!$type || empty($type))
            throw new BadRequestHttpException("This orderType does not exist");
        $order->setType($type);
        $order->setReference($this->random(10));

        // CHECKS FOR RESTAURANT
        // Verify that restaurant id is not empty in the request body
        if (empty($rest_id))
            throw new BadRequestHttpException("You must specify a restaurant ID to add an order");
        $restau = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->find($rest_id);
        // Verify that the restaurant really exist
        if (!$restau || empty($restau))
            throw new BadRequestHttpException("This restaurant does not exist");
        // Verify that restaurant is ready to receive orders
        if (!$restau->getActive())
            throw new BadRequestHttpException("This restaurant does not accept orders right now.");
        // Verify that the restaurant works with this buisness
        $workWith = false;
        foreach ($restau->getContractsList() as $contract){
            if ($contract->getBusiness()->getId() == $buisness->getId() && $type->getId() == $contract->getOrderType()->getId() && $contract->getStatus()->getId() == 3)
                $workWith = true;
        }
        if (!$workWith)
            throw new BadRequestHttpException("This restaurant doesn't work with you! Be sure you use the right API key or you use the right orderType");
        // Verify the restaurant can prepare this order for the hour the client asked him
        $prepTimeDT = new \DateTime($restau->getTimeBeforePreparation());
        $prepTimeInterval = new \DateInterval("PT" . $prepTimeDT->format("H") . "H" . $prepTimeDT->format("i") . 'M' . $prepTimeDT->format("s") . "S");
        $prepHour = (new \DateTime())->add($prepTimeInterval);
        if (new \DateTime($hourToBeReady) < $prepHour)
            throw new BadRequestHttpException("This restaurant can not prepare your order for the time you've asked. Minimal time for prepare your order is : " . $restau->getTimeBeforePreparation());
        // Verify that the hour is in a schedule
        $hourValid = false;
        foreach ($restau->getScheduleDeliveryList() as $schedule){
            $hourDateTime = new \DateTime($hourToBeReady);
            if ($schedule->getDayOpening() == $hourDateTime->format("w")){
                $hourOpening = new \DateTime($hourDateTime->format("Y-m-d") . " " . (new \DateTime($schedule->getHourOpening()))->format("H:i:s"));
                $hourClosing = new \DateTime($hourDateTime->format("Y-m-d") . " " . (new \DateTime($schedule->getHourClosing()))->format("H:i:s"));
                if ($hourOpening < new \DateTime($hourToBeReady) && $hourClosing > new \DateTime($hourToBeReady))
                    $hourValid = true;
            }
        }
        if (!$hourValid)
            throw new BadRequestHttpException("This restaurant doesn't open today at the time of your order");
        $order->setRestaurant($restau);
        $em->persist($order);

        // Adding ExtraLines
        if ($request->get("extraLines") != null) {
            $extraLines = $request->get("extraLines");
            foreach ($extraLines as $extraLine){
                $line = new ExtraLine();
                $line->setText($extraLine["text"]);
                $line->setValue($extraLine["value"]);
                $line->setOrderConcerned($order);
                $order->addExtraLinesList($line);
                $em->persist($line);
            }
        }

        // CREATING ORDERSTATUSHOUR
        // Add a new status "Ordered" at datetime:now for the order we just create
        $status = new OrderStatusHour();
        $status->setStatus($this->getDoctrine()->getRepository("AppBundle:OrderStatus")->find(1));
        $status->setHour(new \DateTime("now"));
        $status->setOrder($order);
        $status->setCurrent(true);
        $order->setAmountTakenByBuisness($amountSupported);
        $order->addStatusList($status);
        $em->persist($status);

        // CREATING ORDERED PRODUCTS
        // Add all products with their options and supplements
        if (!empty($products)) {
            foreach ($products as $product) {
                $orderProd = new OrderProduct();
                $prod = $this->getDoctrine()->getRepository("AppBundle:Product")->find($product["id"]);
                if ($type->getId() != 2 && !$prod->getIsTakeAwayAuthorized())
                    throw new BadRequestHttpException("This product is not available for this orderType : " . $prod->getName());
                if (!$prod->getIsActive())
                    throw new BadRequestHttpException("This product is no longer available" . $prod->getName());
                if ($prod->getIsSoldOut())
                    throw new BadRequestHttpException("Sold out for this product : " . $prod->getName());
                // CHECKS FOR SUPPLEMENTS
                if (isset($product["supplements"])) {
                    // Verify that all the supplements are really linked to the products the client has ordered
                    $supplements = $product["supplements"];
                    foreach ($supplements as $supplement) {
                        $sup = $this->getDoctrine()->getRepository("AppBundle:Supplement")->find($supplement["id"]);
                        if (!$sup)
                            throw new BadRequestHttpException("This supplement does not exist");
                        $supGrps = $this->getDoctrine()->getRepository("AppBundle:ProductSupplementCategory")->findBy(array("supGroup" => $sup->getSupGroup()));
                        $prodOwnSup = false;
                        foreach ($supGrps as $grp)
                            if ($grp->getProduct()->getId() == $prod->getId())
                                $prodOwnSup = true;
                        if ($prodOwnSup)
                            $orderProd->addSupplementsList($sup);
                        else
                            throw new BadRequestHttpException("The product " . $prod->getName() . " can't get this supplement : " . $supplement);
                    }
                }
                // CHECK FOR OPTIONS
                if (isset($product["options"])) {
                    $groupsProduct = [];
                    $options = $product["options"];
                    // Verify that all the options are really linked to the products the client has ordered
                    foreach ($options as $option) {
                        $opt = $this->getDoctrine()->getRepository("AppBundle:Property")->find($option["id"]);
                        $groupsProduct[] = $opt->getOptionGroup();
                        $prodOpts = $this->getDoctrine()->getRepository("AppBundle:ProductPropertyCategory")->findBy(array("optionGroup" => $opt->getOptionGroup()));
                        $prodOwnOption = false;
                        foreach ($prodOpts as $prodOpt)
                            if ($prodOpt->getProduct()->getId() == $prod->getId())
                                $prodOwnOption = true;
                        if ($prodOwnOption)
                            $orderProd->addOptionsList($opt);
                        else
                            throw new BadRequestHttpException("This product can't get this option");
                    }
                    // Verify that all the required options have been choosed for the product
                    $isPresent = true;
                    foreach ($prod->getProperties() as $group)
                        if (!in_array($group->getOptionGroup(), $groupsProduct))
                            $isPresent = false;
                    if (!$isPresent)
                        throw new BadRequestHttpException("Missing options for this product. Forgot any required option ?");
                }

                $orderProd->setProduct($prod);
                $orderProd->setOrder($order);
                $order->addProductsList($orderProd);
                $em->persist($orderProd);
            }
        }

        // CREATING ORDERED MENUS
        if (!empty($menus)){
            foreach ($menus as $menu){
                if ($menu["options"] != null) {
                    $menuOrder = new OrderMenu();
                    $objMenu = $this->getDoctrine()->getRepository("AppBundle:Menu")->find($menu["id"]);
                    if (count($objMenu->getMenuSizes()) > 0){
                        if (isset($menu["size"]) && $menu["size"] != null) {
                            $size = $this->getDoctrine()->getRepository("AppBundle:MenuSize")->find($menu["size"]);
                            if (!$size || empty($size))
                                return Globals::errNotFound("This size of menu does not exist");
                            $menuOrder->setSize($size);
                        }else
                            throw new BadRequestHttpException("You need to specify a size for this menu");
                    }
                    $options = $menu["options"];
                    $menuOrder->setOrder($order);
                    $menuOrder->setMenu($objMenu);
                    $nb_option_menu = count($size->getMenuOptionsList());
                    $nb_valid_options = 0;
                    if ($objMenu->getRestaurant()->getId() != $restau->getId())
                        throw new BadRequestHttpException("This menu is not linked to this restaurant" . $menu->getName());
                    // Verify the menu has all his menuoption specified
                    if (count($options) < $nb_option_menu)
                        throw new BadRequestHttpException("You need to specifiy all the Menu Options for this menu");
                    foreach ($options as $option) {
                        if ($option["product"] != null && $option["id"] != null) {
                            $menuOption = $this->getDoctrine()->getRepository("AppBundle:MenuOption")->find($option["id"]);
                            $productMenu = $em->getRepository("AppBundle:Product")->find($option["product"]);
                            foreach ($menuOption->getProductsList() as $optionProduct) {
                                if ($optionProduct->getProduct() == $productMenu) {
                                    $menuOrderProduct = new OrderMenuProduct();
                                    $menuOrderProduct->setProduct($productMenu);
                                    $menuOrderProduct->setOrderMenu($menuOrder);
                                    $nb_valid_options++;
                                    // CHECKS FOR SUPPLEMENTS
                                    if (isset($option["supplements"])) {
                                        // Verify that all the supplements are really linked to the products the client has ordered
                                        $supplements = $option["supplements"];
                                        foreach ($supplements as $supplement) {
                                            $sup = $this->getDoctrine()->getRepository("AppBundle:Supplement")->find($supplement["id"]);
                                            if (!$sup)
                                                throw new BadRequestHttpException("This supplement does not exist");
                                            $supGrps = $this->getDoctrine()->getRepository("AppBundle:ProductSupplementCategory")->findBy(array("supGroup" => $sup->getSupGroup()));
                                            $prodOwnSup = false;
                                            foreach ($supGrps as $grp)
                                                if ($grp->getProduct()->getId() == $productMenu->getId())
                                                    $prodOwnSup = true;
                                            if ($prodOwnSup)
                                                $menuOrderProduct->addSupplement($sup);
                                            else
                                                throw new BadRequestHttpException("The product can't get this supplement : " . $supplement);
                                        }
                                    }
                                    // CHECK FOR PROPERTIES
                                    if (isset($option["properties"]) && $option["properties"] != null){
                                        $properties = $option["properties"];
                                        if (count($properties) != count($productMenu->getProperties()))
                                            throw new BadRequestHttpException("You need to specifiy properties of the product in the menu");
                                        $groupsProduct = [];
                                        // Verify that all the options are really linked to the products the client has ordered
                                        foreach ($properties as $option) {
                                            $opt = $this->getDoctrine()->getRepository("AppBundle:Property")->find($option["id"]);
                                            $groupsProduct[] = $opt->getOptionGroup();
                                            $prodOwnOption = false;
                                            foreach ($size->getMenuOptionsList() as $prodOpt) {
                                                foreach ($prodOpt->getProductsList() as $prod){
                                                    foreach ($prod->getProperties() as $property) {
                                                        if ($property->getId() == $opt->getId())
                                                            $prodOwnOption = true;
                                                    }
                                                }
                                            }
                                            if ($prodOwnOption)
                                                $menuOrderProduct->addProperty($opt);
                                            else
                                                throw new BadRequestHttpException("This product in this menu can't get this option");
                                        }
                                        // Verify that all the required options have been choosed for the product
                                        $isPresent = true;
                                        foreach ($productMenu->getProperties() as $group)
                                            if (!in_array($group->getOptionGroup(), $groupsProduct))
                                                $isPresent = false;
                                        if (!$isPresent)
                                            throw new BadRequestHttpException("Missing options for this product. Forgot any required option ?");
                                    }
                                    $em->persist($menuOrderProduct);
                                    $menuOrder->addProductsList($menuOrderProduct);
                                    break;
                                }
                            }
                        } else
                            throw new BadRequestHttpException("You need to specify \"id\" and \"product\" fields in your options list.");
                    }
                    // Verify the product is really linked to the menu option specified
                    if ($nb_valid_options < $nb_option_menu)
                        throw new BadRequestHttpException("Some products you choosed are incompatible with his menu option in the value you specified");
                    else {
                        $em->persist($menuOrder);
                        $order->addMenusList($menuOrder);
                    }
                }else
                    throw new BadRequestHttpException("You need to specify an \"options\" field in your menu ");
            }
        }
        return $order;
    }

    /**
     * @Rest\View(serializerGroups={"orders"})
     * @Rest\Post("/restaurants/{restaurant}/orders")

    public function postOrderAction(Request $request)
    {
        // BASIC CHECKS
        // Verify that there is a product or a menu in the order
        if ($request->get("products") == null && $request->get("menus") == null)
            throw new BadRequestHttpException("Your order must have a product or a menuu");
        // Verify that all client and order required informations are present
        $requiredParams = ["clientPhone", "clientName", "hourToBeReady", "orderType", "clientAddressLine1", "clientCountryCode", "clientCity"];
        foreach ($requiredParams as $param)
            if ($request->get($param) == null)
                throw new BadRequestHttpException("Some required informations are missing. Missing : " . $param);

        $em = $this->get('doctrine.orm.entity_manager');

        // SETTING VALUES FROM REQUEST BODY
        $products = $request->get("products");
        $menus = $request->get("menus");
        $rest_id = $request->get("restaurant");

        $restau = $this->getRestaurant($rest_id);

        $type = $request->get("orderType");
        $type = $this->getDoctrine()->getRepository("AppBundle:OrderType")->find($type);
        if (!$type || empty($type))
            throw new BadRequestHttpException("This orderType does not exist");

        // CREATING ORDER
        $order = new Order();
        $buisness = $this->getBusiness($request);
        $order->setBusiness($buisness);
        $order->setType($type);
        $order->setRestaurant($restau);
        $em->persist($order);
        $order->setReference($this->random(10));

        $this->setOrderParams($request, $order);

        $this->setExtraLines($request, $order);
        $this->setStatus($order);

        $this->setProducts($products, $type, $order);

        // CREATING ORDERED MENUS
        if (!empty($menus)){
            foreach ($menus as $menu){
                if ($menu["options"] != null) {
                    $menuOrder = new OrderMenu();
                    $objMenu = $this->getDoctrine()->getRepository("AppBundle:Menu")->find($menu["id"]);
                    if (count($objMenu->getMenuSizes()) > 0){
                        if (isset($menu["size"]) && $menu["size"] != null) {
                            $size = $this->getDoctrine()->getRepository("AppBundle:MenuSize")->find($menu["size"]);
                            if (!$size || empty($size))
                                return Globals::errNotFound("This size of menu does not exist");
                            $menuOrder->setSize($size);
                        }else
                            throw new BadRequestHttpException("You need to specify a size for this menu");
                    }
                    $options = $menu["options"];
                    $menuOrder->setOrder($order);
                    $menuOrder->setMenu($objMenu);
                    $nb_option_menu = count($size->getMenuOptionsList());
                    $nb_valid_options = 0;
                    if ($objMenu->getRestaurant()->getId() != $restau->getId())
                        throw new BadRequestHttpException("This menu is not linked to this restaurant" . $menu->getName());
                    // Verify the menu has all his menuoption specified
                    if (count($options) < $nb_option_menu)
                        throw new BadRequestHttpException("You need to specifiy all the Menu Options for this menu");
                    foreach ($options as $option) {
                        if ($option["product"] != null && $option["id"] != null) {
                            $menuOption = $this->getDoctrine()->getRepository("AppBundle:MenuOption")->find($option["id"]);
                            $productMenu = $em->getRepository("AppBundle:Product")->find($option["product"]);
                            foreach ($menuOption->getProductsList() as $optionProduct) {
                                if ($optionProduct->getProduct() == $productMenu) {
                                    $menuOrderProduct = new OrderMenuProduct();
                                    $menuOrderProduct->setProduct($productMenu);
                                    $menuOrderProduct->setOrderMenu($menuOrder);
                                    $nb_valid_options++;
                                    // CHECKS FOR SUPPLEMENTS
                                    if (isset($option["supplements"])) {
                                        // Verify that all the supplements are really linked to the products the client has ordered
                                        $supplements = $option["supplements"];
                                        foreach ($supplements as $supplement) {
                                            $sup = $this->getDoctrine()->getRepository("AppBundle:Supplement")->find($supplement["id"]);
                                            if (!$sup)
                                                throw new BadRequestHttpException("This supplement does not exist");
                                            $supGrps = $this->getDoctrine()->getRepository("AppBundle:ProductSupplementCategory")->findBy(array("supGroup" => $sup->getSupGroup()));
                                            $prodOwnSup = false;
                                            foreach ($supGrps as $grp)
                                                if ($grp->getProduct()->getId() == $productMenu->getId())
                                                    $prodOwnSup = true;
                                            if ($prodOwnSup)
                                                $menuOrderProduct->addSupplement($sup);
                                            else
                                                throw new BadRequestHttpException("The product " . $prod->getName() . " can't get this supplement : " . $supplement);
                                        }
                                    }
                                    // CHECK FOR PROPERTIES
                                    if (isset($option["properties"]) && $option["properties"] != null){
                                        $properties = $option["properties"];
                                        if (count($properties) != count($productMenu->getProperties()))
                                            throw new BadRequestHttpException("You need to specifiy properties of the product in the menu");
                                        $groupsProduct = [];
                                        // Verify that all the options are really linked to the products the client has ordered
                                        foreach ($properties as $option) {
                                            $opt = $this->getDoctrine()->getRepository("AppBundle:Property")->find($option["id"]);
                                            $groupsProduct[] = $opt->getOptionGroup();
                                            $prodOwnOption = false;
                                            foreach ($size->getMenuOptionsList() as $prodOpt) {
                                                foreach ($prodOpt->getProductsList() as $prod){
                                                    foreach ($prod->getProperties() as $property) {
                                                        if ($property->getId() == $opt->getId())
                                                            $prodOwnOption = true;
                                                    }
                                                }
                                            }
                                            if ($prodOwnOption)
                                                $menuOrderProduct->addProperty($opt);
                                            else
                                                throw new BadRequestHttpException("This product in this menu can't get this option");
                                        }
                                        // Verify that all the required options have been choosed for the product
                                        $isPresent = true;
                                        foreach ($productMenu->getProperties() as $group)
                                            if (!in_array($group->getOptionGroup(), $groupsProduct))
                                                $isPresent = false;
                                        if (!$isPresent)
                                            throw new BadRequestHttpException("Missing options for this product. Forgot any required option ?");
                                    }
                                    $em->persist($menuOrderProduct);
                                    $menuOrder->addProductsList($menuOrderProduct);
                                    break;
                                }
                            }
                        } else
                            throw new BadRequestHttpException("You need to specify \"id\" and \"product\" fields in your options list.");
                    }
                    // Verify the product is really linked to the menu option specified
                    if ($nb_valid_options < $nb_option_menu)
                        throw new BadRequestHttpException("Some products you choosed are incompatible with his menu option in the value you specified");
                    else {
                        $em->persist($menuOrder);
                        $order->addMenusList($menuOrder);
                    }
                }else
                    throw new BadRequestHttpException("You need to specify an \"options\" field in your menu ");
            }
        }
        $em->flush();
        return $order;
    }*/



    /**
     * @Rest\View(serializerGroups={"orders"})
     * @Rest\Get("/orders-restaurant/{identifiers}")
     */
    /*public function getOrdersRestaurantAction(Request $request)
    {
        if ($this->getUserKey($request)->getValue() == "CW3tbH8v/SLvFG8/y0UPIOnenuw=") {
            $identifiers = explode(",", $request->get("identifiers"));
            if (count($identifiers) == 2) {
                $id_tab = explode("=", $identifiers[0]);
                $pass_tab = explode("=", $identifiers[1]);
                if ($id_tab[0] == "id" && $pass_tab[0] == "pass") {
                    $pass = $pass_tab[1];
                    $id = $id_tab[1];
                    $user = $this->getDoctrine()->getRepository("AppBundle:RestaurantUser")->findOneBy(array("username" => $id));
                    if (empty($user)) {
                        $user = $this->getDoctrine()->getRepository("AppBundle:RestaurantUser")->findOneBy(array("email" => $id));
                        if (empty($user))
                            throw new BadRequestHttpException("This username / email does not exist.");
                    }
                    if ($pass == $user->getConfirmationToken())
                        return $this->getDoctrine()->getRepository("AppBundle:Order")->findBy(array("restaurant" => $user->getRestaurant()), array("hourToBeReady" => "DESC"));
                    else
                        throw new BadRequestHttpException("These credentiels are incorrects");
                }else
                    throw new BadRequestHttpException("This request needs authentification by url. Required parameters are not valid.");
            } else
                throw new BadRequestHttpException("This request needs authentification by url. Required parameters are missing.");
        }else
            return Globals::errForbidden("You are not allowed to access this request. If you may be allowed, please contact the support");
    }*/

}
