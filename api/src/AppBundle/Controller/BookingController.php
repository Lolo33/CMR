<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Globals;
use AppBundle\Entity\ScheduleFreak;
use AppBundle\Entity\Table;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BookingController extends Controller
{

    private function isTableFree($table, $heure_debut_service, $heure_fin_service, $bookingDuration, $hour){
        $tableFree = true;
        // Vérification des autres réservations
        foreach ($table->getBookingList() as $booking) {
            $hourBooking = new \DateTime($booking->getHour());
            $duree_min_resa = new \DateTime($bookingDuration);
            $interval_min_resa = new \DateInterval("PT" . $duree_min_resa->format("H") . "H" . $duree_min_resa->format("i") . "M" . $duree_min_resa->format("s") . "S");
            $hourFinBooking = (new \DateTime($hourBooking->format("Y-m-d H:i:s")))->add($interval_min_resa);
            if ($heure_debut_service < $hourBooking && $heure_fin_service > $hourBooking) {
                if ($hourBooking < $hour && $hourFinBooking > $hour) {
                    $tableFree = false;
                }
            }
        }
        return $tableFree;
    }

    public function getBookingsAction()
    {
        return $this->render('AppBundle:Booking:get_bookings.html.twig', array(
            // ...
        ));
    }

    public function getBookingAction()
    {
        return $this->render('AppBundle:Booking:get_booking.html.twig', array(
            // ...
        ));
    }

    public function getBookingRestaurantAction()
    {
        return $this->render('AppBundle:Booking:get_booking_restaurant.html.twig', array(
            // ...
        ));
    }

    /**
     * @Rest\View(serializerGroups={"booking", "schedules"})
     * @Rest\Post("/restaurants/{id_r}/bookings")
     */
    public function postBookingAAAction(Request $request){

        if ($request->get("hour") != null && $request->get("id_r") != null) {

            $em = $this->get('doctrine.orm.entity_manager');

            $hour = new \DateTime($request->get("hour"));
            $nbPersonnes = $request->get("nbPersons");
            $restaurant = $this->getDoctrine()->getRepository("AppBundle:Restaurant")->find($request->get("id_r"));

            // Vérification des horaires
            $dOw = $hour->format("w");
            $schedules = $this->getDoctrine()->getRepository("AppBundle:ScheduleBooking")->findBy(array("dayOpening" => $dOw));
            $schedule = null;
            foreach ($schedules as $sch){
                $heure_debut_service = new \DateTime($sch->getHourOpening());
                $heure_fin_service = new \DateTime($sch->getHourClosing());
                $heure_debut_service->setDate($hour->format("Y"), $hour->format("m"), $hour->format("d"));
                $heure_fin_service->setDate($hour->format("Y"), $hour->format("m"), $hour->format("d"));
                if ($heure_debut_service <= $hour && $heure_fin_service >= $hour)
                    $schedule = $sch;
            }

            if ($schedule != null){
                $heure_debut_service = new \DateTime($schedule->getHourOpening());
                $heure_fin_service = new \DateTime($schedule->getHourClosing());
                $heure_debut_service->setDate($hour->format("Y"), $hour->format("m"), $hour->format("d"));
                $heure_fin_service->setDate($hour->format("Y"), $hour->format("m"), $hour->format("d"));
                $book = new Booking();
                $book->setStatus($this->getDoctrine()->getRepository("AppBundle:BookingStatus")->find(1));
                $book->setHour($hour);
                $book->setNbOfPersons($nbPersonnes);
                $book->setType($this->getDoctrine()->getRepository("AppBundle:BookingType")->find(1));
                $book->setClientName($request->get("clientName"));
                $book->setClientTel($request->get("clientPhone"));
                $book->setReference("GZIEJGO547");

                $selectedTable = null;
                foreach ($restaurant->getTables() as $table){
                    if ($this->isTableFree($table, $heure_debut_service, $heure_fin_service, $restaurant->getBookingDuration(), $hour)){
                        if ($nbPersonnes == $table->getNumberOfPersons()) {
                            $selectedTable = $table;
                            break;
                        }
                    }
                }
                if ($selectedTable == null){
                    foreach ($restaurant->getTables() as $k => $table){
                        if ($table->getNumberOfPersons() > $nbPersonnes){
                            if ($this->isTableFree($table, $heure_debut_service, $heure_fin_service, $restaurant->getBookingDuration(), $hour)) {
                                if ($k == 0)
                                    $selectedTable = $table;
                                else {
                                    $diff_nb_pers = $table->getNumberOfPersons() - $nbPersonnes;
                                    $diff_nb_pers_m_1 = $restaurant->getTables()[$k - 1]->getNumberOfPersons() - $nbPersonnes;
                                    if ($diff_nb_pers < $diff_nb_pers_m_1){
                                        $selectedTable = $table;
                                    }
                                }
                            }
                        }
                    }
                }
                if ($selectedTable == null){
                    throw new BadRequestHttpException("This restaurant have no table free for this number of persons.");
                }
                $book->addAssignedTable($selectedTable);
                $em->persist($book);

                $em->flush();
                return $book;

            }else
                throw new BadRequestHttpException("Your restaurant is not open for the date you specified");

        }else
            throw new BadRequestHttpException("You must specify a table and an hour for your booking");
    }

    /**
     * @Rest\View(serializerGroups={"booking", "schedules"})
     * @Rest\Post("/restaurants/{identifiers}/booking")
     */
    public function postBookingAction(Request $request)
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
                    // IDENTIFICATION REUSSIE
                    if (password_verify($pass, $user->getPassword())){

                        if ($request->get("hour") != null && $request->get("tables") != null) {

                            $em = $this->get('doctrine.orm.entity_manager');
                            $tables = [];
                            $tableArray = $request->get("tables");
                            foreach ($tableArray as $tableJson){
                                $tables[] = $em->getRepository("AppBundle:Table")->find($tableJson["id"]);
                            }

                            $hour = new \DateTime($request->get("hour"));

                            $nbPersonnes = $request->get("nbPersons");
                            $restaurant = $user->getRestaurant();

                            // Vérification des horaires
                            $schedule = $this->getDoctrine()->getRepository("AppBundle:ScheduleBooking")->find($request->get("schedule"));
                            $heure_debut_service = new \DateTime($schedule->getHourOpening());
                            $heure_fin_service = new \DateTime($schedule->getHourClosing());
                            $heure_debut_service->setDate($hour->format("Y"), $hour->format("m"), $hour->format("d"));
                            $heure_fin_service->setDate($hour->format("Y"), $hour->format("m"), $hour->format("d"));

                            if ($heure_debut_service < $hour && $heure_fin_service > $hour){
                                $book = new Booking();
                                $book->setStatus($this->getDoctrine()->getRepository("AppBundle:BookingStatus")->find(1));
                                $book->setHour($hour);
                                $book->setNbOfPersons($nbPersonnes);
                                $book->setType($this->getDoctrine()->getRepository("AppBundle:BookingType")->find(1));
                                $book->setClientName("NS Client");
                                $book->setClientTel("NS Tel");
                                $book->setReference("GZIEJGO547");
                                $tableFree = true;
                                if (count($tables) > 1){
                                    $mergedTab = new Table();
                                    $freakTables = [];
                                    $nbPersonnesTab = 0;
                                    $i = 0;
                                    $nameMergedTab = "";
                                    foreach ($tables as $table) {
                                        // Vérification des autres réservations
                                        foreach ($table->getBookingList() as $booking) {
                                            $hourBooking = new \DateTime($booking->getHour());
                                            $duree_min_resa = new \DateTime($restaurant->getBookingDuration());
                                            $interval_min_resa = new \DateInterval("PT" . $duree_min_resa->format("H") . "H" . $duree_min_resa->format("i") . "M" . $duree_min_resa->format("s") . "S");
                                            $hourFinBooking = (new \DateTime($hourBooking->format("Y-m-d H:i:s")))->add($interval_min_resa);
                                            if ($heure_debut_service < $hourBooking && $heure_fin_service > $hourBooking) {
                                                if ($hourBooking < $hour && $hourFinBooking > $hour) {
                                                    $tableFree = false;
                                                    break;
                                                }
                                            }
                                        }
                                        $freakMergedTables = new ScheduleFreak();
                                        $freakMergedTables->setStatus($em->getRepository("AppBundle:ScheduleFreakStatus")->find(4));
                                        $freakMergedTables->setDate(new \DateTime($hour->format("Y-m-d")));
                                        $freakMergedTables->setAffectedTable($table);
                                        $freakMergedTables->setSchedule($schedule);
                                        $freakTables[] = $freakMergedTables;
                                        $nameMergedTab .= $table->getTableNumber();
                                        if ($i != count($tables) - 1)
                                            $nameMergedTab .= " + ";
                                        $nbPersonnesTab += $table->getNumberOfPersons();
                                        $i++;
                                    }

                                    if ($nbPersonnes > $nbPersonnesTab)
                                        throw new BadRequestHttpException("This table cannot accept this number of persons.");

                                    $mergedTab->setIsActive(true);
                                    $mergedTab->setRestaurant($restaurant);
                                    $mergedTab->setIsBookable(1);
                                    $mergedTab->setTableNumber($nameMergedTab);
                                    $mergedTab->setNumberOfPersons($nbPersonnesTab);
                                    $em->persist($mergedTab);

                                    foreach ($freakTables as $freakTable){
                                        $em->persist($freakTable);
                                    }

                                    $freakNewTab = new ScheduleFreak();
                                    $freakNewTab->setStatus($em->getRepository("AppBundle:ScheduleFreakStatus")->find(3));
                                    $freakNewTab->setDate(new \DateTime($hour->format("Y-m-d")));
                                    $freakNewTab->setAffectedTable($mergedTab);
                                    $freakNewTab->setSchedule($schedule);
                                    $em->persist($freakNewTab);

                                    $book->addAssignedTable($mergedTab);

                                }else {
                                    foreach ($tables as $table) {
                                        // Vérification des autres réservations
                                        foreach ($table->getBookingList() as $booking) {
                                            $hourBooking = new \DateTime($booking->getHour());
                                            $duree_min_resa = new \DateTime($restaurant->getBookingDuration());
                                            $interval_min_resa = new \DateInterval("PT" . $duree_min_resa->format("H") . "H" . $duree_min_resa->format("i") . "M" . $duree_min_resa->format("s") . "S");
                                            $hourFinBooking = (new \DateTime($hourBooking->format("Y-m-d H:i:s")))->add($interval_min_resa);
                                            if ($heure_debut_service < $hourBooking && $heure_fin_service > $hourBooking) {
                                                if ($hourBooking < $hour && $hourFinBooking > $hour) {
                                                    $tableFree = false;
                                                    break;
                                                }
                                            }
                                        }
                                        if ($nbPersonnes > $table->getNumberOfPersons())
                                            throw new BadRequestHttpException("This table cannot accept this number of persons.");
                                    }
                                    $book->addAssignedTable($table);
                                }

                                if (!$tableFree)
                                    throw new BadRequestHttpException("This table is already booked for this moment");

                                $em->persist($book);

                                $em->flush();
                                return $book;

                            }else
                                throw new BadRequestHttpException("Your restaurant is not open for the date you specified");

                        }else
                            throw new BadRequestHttpException("You must specify a table and an hour for your booking");
                    } else
                        throw new BadRequestHttpException("These credentiels are incorrects");
                }else
                    throw new BadRequestHttpException("This request needs authentification by url. Required parameters are not valid.");
            } else
                throw new BadRequestHttpException("This request needs authentification by url. Required parameters are missing.");
        }else
            return Globals::errForbidden("You are not allowed to access this request. If you may be allowed, please contact the support");
    }

    public function removeBookingAction()
    {
        return $this->render('AppBundle:Booking:remove_booking.html.twig', array(
            // ...
        ));
    }

}
