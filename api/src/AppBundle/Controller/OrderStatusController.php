<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Globals;
use AppBundle\Entity\OrderStatusHour;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrderStatusController extends Controller
{


    /**
     * @Rest\View(serializerGroups={"orders"})
     * @Rest\Get("/orders/{id}/status")
     */
    public function getStatusAction(Request $request)
    {
        $order = $this->getDoctrine()->getRepository("AppBundle:Order")->find($request->get("id"));
        $token = $this->getUserKey($request);
        $buisness = $token->getApiUser()->getBusiness();
        if (!$order || empty($order) || $order == null)
            return Globals::errNotFound("This order does not exist");
        if ($order->getBusiness() != $buisness)
            return Globals::errForbidden("\"You are not allowed to see this order's status.\"");
        return $order->getStatusList();
    }

    /**
     * @Rest\View(serializerGroups={"orders"})
     * @Rest\Post("/orders/{id}/status")
     */
    public function postStatusAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $order = $em->getRepository("AppBundle:Order")->find($request->get("id"));
        $status = $request->get("status");
        foreach ($order->getStatusList() as $stat){
            if ($stat->getStatus()->getId() == $status)
                throw new BadRequestHttpException("This status already exist for this order");
            $stat->setCurrent(false);
            $em->merge($stat);
        }
        $statusHour = new OrderStatusHour();
        $statusHour->setOrder($order);
        $statusHour->setHour(new \DateTime("now"));
        $statusHour->setCurrent(true);
        $statusObj = $em->getRepository("AppBundle:OrderStatus")->find($status);
        if (!$status)
            throw new BadRequestHttpException("This status does not exist");
        $statusHour->setStatus($statusObj);
        $em->persist($statusHour);
        $order->addStatusList($statusHour);
        $em->merge($order);
        $em->flush();
        return $order;
    }

}
