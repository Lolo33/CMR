<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ScheduleBookingController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"schedules"})
     * @Rest\Get("/restaurants/{id}/schedules-booking/{day}")
     */
    public function getScheduleOfDayAction(Request $request)
    {
        $schedules = [];
        $restaurant = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->find($request->get("id"));
        if (!$restaurant || empty($restaurant))
            return Globals::errNotFound("This restaurant was not found");
        foreach ($restaurant->getScheduleDeliveryList() as $schedule) {
            if ($schedule->getDayOpening() == $request->get("day"))
                $schedules[] = $schedule;
        }
        return $schedules;
    }

}
