<?php

namespace AppBundle\Controller\Restaurant;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class ScheduleController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"schedules"})
     * @Rest\Get("restaurants/{id}/schedules")
     */
    public function getSchedulesAction(Request $request)
    {
        $restau = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->find($request->get("id"));
        return $restau->getScheduleList();
    }

    /**
     * @Rest\View(serializerGroups={"schedules"})
     * @Rest\Get("schedules/{id}")
     */
    public function getScheduleAction(Request $request)
    {
        return $this->getDoctrine()->getRepository("AppBundle:ScheduleDelivery")->find($request->get("id"));
    }

}
