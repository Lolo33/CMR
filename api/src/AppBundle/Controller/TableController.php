<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TableController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"tables"})
     * @Rest\Get("restaurants/{id}/tables")
     */
    public function getTablesAction(Request $request)
    {
        $restaurant = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->find($request->get("id"));
        return $restaurant->getTables();
    }

    public function getTableAction()
    {
        return $this->render('AppBundle:Table:get_table.html.twig', array(
            // ...
        ));
    }

    /**
     * @Rest\View(serializerGroups={"tables"})
     * @Rest\Get("restaurants/{id}/schedules/{s_id}/tables-merge/{date}")
     */
    public function getTableMergeByScheduleAction(Request $request){
        $id_schedule = $request->get("s_id");
        $schedule = $this->getDoctrine()->getRepository("AppBundle:ScheduleBooking")->find($id_schedule);
        $date = new \DateTime($request->get("date"));
        $tables = [];
        $restaurant = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->find($request->get("id"));
        $addTable = true;
        foreach ($restaurant->getTables() as $table) {
            if ($table->getIsActive()) {
                $date_debut_schedule = new \DateTime($schedule->getHourOpening());
                $date_fin_schedule = new \DateTime($schedule->getHourClosing());
                $date_debut_schedule->setDate($date->format("Y"), $date->format("m"), $date->format("d"));
                $date_fin_schedule->setDate($date->format("Y"), $date->format("m"), $date->format("d"));
                foreach ($table->getBookingList() as $booking){
                    $hour = new \DateTime($booking->getHour());
                    if ($date_debut_schedule < $hour && $date_fin_schedule > $hour) {
                        $addTable = false;
                        break;
                    }
                }
                foreach ($table->getScheduleFreaks() as $freak) {
                    if (new \DateTime($freak->getDate()) == $date && $id_schedule == $freak->getSchedule()->getId()) {
                        if ($freak->getStatus()->getId() == 4) {
                            $addTable = false;
                            break;
                        } else
                            $addTable = true;
                    } else
                        if ($freak->getStatus()->getId() == 3) {
                            $addTable = false;
                            break;
                        } else
                            $addTable = true;
                }
                if ($addTable)
                    $tables[] = $table;
            }
        }
        return $tables;
    }

    /**
     * @Rest\View(serializerGroups={"tables"})
     * @Rest\Get("restaurants/{id}/schedules/{s_id}/tables/{date}")
     */
    public function getTableByScheduleAction(Request $request){
        $id_schedule = $request->get("s_id");
        $date = new \DateTime($request->get("date"));
        $tables = [];
        $restaurant = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->find($request->get("id"));
        $addTable = true;
        foreach ($restaurant->getTables() as $table) {
            if ($table->getIsActive()) {
                foreach ($table->getScheduleFreaks() as $freak) {
                    if (new \DateTime($freak->getDate()) == $date && $id_schedule == $freak->getSchedule()->getId()) {
                        if ($freak->getStatus()->getId() == 4) {
                            $addTable = false;
                            break;
                        } else
                            $addTable = true;
                    }else
                        if ($freak->getStatus()->getId() == 3){
                            $addTable = false;
                            break;
                        }else
                            $addTable = true;
                }
                if ($addTable)
                    $tables[] = $table;
            }
        }
        return $tables;
    }

    public function postTableAction()
    {
        return $this->render('AppBundle:Table:post_table.html.twig', array(
            // ...
        ));
    }

    public function removeTableAction()
    {
        return $this->render('AppBundle:Table:remove_table.html.twig', array(
            // ...
        ));
    }

}
