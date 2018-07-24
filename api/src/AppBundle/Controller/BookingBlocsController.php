<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BookingBloc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BookingBlocsController extends Controller
{

    private function setStatusFromDurations($duree_bloc, $duree_min, $bloc, $isFreakUnavailable){
        if ($duree_bloc >= $duree_min && !$isFreakUnavailable) {
            $bloc->setStatus("Réservable");
            // Sinon ce bloc n'est pas réservable
        } else {
            $bloc->setStatus("Non réservable");
        }
        $bloc->setDuration($duree_bloc);
        return $bloc;
    }

    private function durationToSeconds(\DateTime $duration){
        return ($duration->format("s") / 60) + ($duration->format("i")) + ($duration->format("H") * 60);
    }

    /**
     * @Rest\View(serializerGroups={"booking", "schedules"})
     * @Rest\Get("/tables/{t_id}/schedules/{s_id}/date/{date}/blocs")
     */
    public function getBlocsAction(Request $request)
    {
        $table = $this->getDoctrine()->getRepository("AppBundle:Table")->find($request->get("t_id"));
        $schedule = $this->getDoctrine()->getRepository("AppBundle:ScheduleBooking")->find($request->get("s_id"));
        $date_debut_schedule = new \DateTime($schedule->getHourOpening());
        $date_fin_schedule = new \DateTime($schedule->getHourClosing());
        $date_ajd = new \DateTime($request->get("date"));
        $restaurant = $table->getRestaurant();
        if ($schedule->getDayOpening() == $date_ajd->format("w")) {
            $diff_schedules = $date_debut_schedule->diff($date_fin_schedule);
            $diff_schedules->h += 1;
            $duree_totale_service = new \DateTime($diff_schedules->h . ":" . $diff_schedules->i . ":" . $diff_schedules->s);
            $duree_totale_s = $this->durationToSeconds($duree_totale_service);
            $blocs = [];
            $date_debut_schedule->setDate($date_ajd->format("Y"), $date_ajd->format("m"), $date_ajd->format("d"));
            $date_fin_schedule->setDate($date_ajd->format("Y"), $date_ajd->format("m"), $date_ajd->format("d"));
            $bookings = $table->getBookingList();
            $duree_min_resa = new \DateTime($restaurant->getBookingDuration());
            $interval_min_resa = new \DateInterval("PT" . $duree_min_resa->format("H") . "H" . $duree_min_resa->format("i") . "M" . $duree_min_resa->format("s") . "S" );
            $i = 0;
            $nbBlocs = 0;
            $isFreakUnavailable = false;
            foreach ($table->getScheduleFreaks() as $freak){
                if ($freak->getDate() == $date_ajd && $freak->getSchedule()->getId() == $schedule->getId() && $freak->getStatus()->getId() == 1)
                    $isFreakUnavailable = true;
            }
            if (!$table->getIsBookable()) {
                $blocEntier = new BookingBloc();
                $blocEntier->setStatus("Non Réservable");
                $blocEntier->setHeureDebut($date_debut_schedule);
                $blocEntier->setHeureFin($date_fin_schedule);
                $blocEntier->setDuration($duree_totale_service);
                $blocEntier->setRatio(1);
                $blocs[] = $blocEntier;
            }else{
                foreach ($bookings as $booking) {
                    $hour = new \DateTime($booking->getHour());
                    if ($date_debut_schedule < $hour && $date_fin_schedule > $hour) {
                        if ($i == 0) {
                            // On récupère l'intervalle de temps au format DateTime afin de le comparer et on crée le nouveau bloc
                            $diff_premiere_resa = $date_debut_schedule->diff((new \DateTime($hour->format("Y-m-d H:i:s"))));
                            $duree_avant_premiere_resa = new \DateTime($diff_premiere_resa->h . ":" . $diff_premiere_resa->i . ":" . $diff_premiere_resa->s);
                            // Si la réservation n'est pas directement à l'heure d'ouverture, on crée un nouveau bloc
                            if ($diff_premiere_resa->h != 0 || $diff_premiere_resa->i != 0 || $diff_premiere_resa->s != 0) {
                                $blocVide = new BookingBloc();
                                // Si ce bloc possède un temps supérieur ou égal à celui d'une réservation, il est réservable
                                $blocVide = $this->setStatusFromDurations($duree_avant_premiere_resa, $duree_min_resa, $blocVide, $isFreakUnavailable);
                                $duree_bloc_s = $this->durationToSeconds($duree_avant_premiere_resa);
                                $blocVide->setHeureDebut($date_debut_schedule);
                                $blocVide->setHeureFin((new \DateTime($date_debut_schedule->format("Y-m-d H:i:s")))->add($diff_premiere_resa));
                                $blocVide->setRatio(round($duree_bloc_s / $duree_totale_s, 2));
                                $blocs[] = $blocVide;
                                $nbBlocs++;
                            }
                            $blocResa = new BookingBloc();
                            $blocResa->setBooking($booking);
                            $blocResa->setStatus("Réservé");
                            $blocResa->setHeureDebut($hour);
                            $blocResa->setHeureFin((new \DateTime($hour->format("Y-m-d H:i:s")))->add($interval_min_resa));
                            $blocResa->setDuration($duree_min_resa);
                            $duree_resa_s = $this->durationToSeconds($duree_min_resa);
                            $blocResa->setRatio(round($duree_resa_s / $duree_totale_s, 2));
                            $blocs[] = $blocResa;
                            $nbBlocs++;
                        } else {
                            $lastBloc = $blocs[$nbBlocs - 1];
                            $hour_last_booking = new \DateTime($lastBloc->getBooking()->getHour());
                            $diff_resa_precedente = $hour->diff($hour_last_booking);
                            $duree_avant_resa_suivante = new \DateTime($diff_resa_precedente->h . ":" . $diff_resa_precedente->i . ":" . $diff_resa_precedente->s);
                            if ($diff_resa_precedente->h != 0 || $diff_resa_precedente->i != 0 || $diff_resa_precedente->s != 0) {
                                $blocVide = new BookingBloc();
                                // Si ce bloc possède un temps supérieur ou égal à celui d'une réservation, il est réservable
                                $blocVide = $this->setStatusFromDurations($duree_avant_resa_suivante, $duree_min_resa, $blocVide, $isFreakUnavailable);
                                $duree_bloc_s = $this->durationToSeconds($duree_avant_resa_suivante);
                                $blocVide->setHeureDebut(new \DateTime($lastBloc->getHeureFin()));
                                $blocVide->setHeureFin($hour);
                                $blocVide->setRatio(round($duree_bloc_s / $duree_totale_s, 2));
                                $blocs[] = $blocVide;
                                $nbBlocs++;
                            }
                            $blocResa = new BookingBloc();
                            $blocResa->setBooking($booking);
                            $blocResa->setStatus("Réservé");
                            $blocResa->setDuration($duree_min_resa);
                            $blocResa->setHeureDebut($hour);
                            $hour_end_resa = (new \DateTime($hour->format("Y-m-d H:i:s")))->add($interval_min_resa);
                            $blocResa->setHeureFin($hour_end_resa);
                            $duree_resa_s = $this->durationToSeconds($duree_min_resa);
                            $blocResa->setRatio(round($duree_resa_s / $duree_totale_s, 2));
                            $blocs[] = $blocResa;
                            $nbBlocs++;
                        }
                        $i++;
                    }
                }
                if ($nbBlocs > 0) {
                    $lastBloc = $blocs[$nbBlocs - 1];
                    $hour_last_booking = new \DateTime($lastBloc->getBooking()->getHour());
                    $hour_end_last_booking = $hour_last_booking->add($interval_min_resa);
                    $diff_last_resa = $hour_end_last_booking->diff($date_fin_schedule);
                    if ($hour_end_last_booking < $date_fin_schedule) {
                        $blocVideFin = new BookingBloc();
                        $duree_avant_fin_service = new \DateTime($diff_last_resa->h . ":" . $diff_last_resa->i . ":" . $diff_last_resa->s);
                        $blocVideFin = $this->setStatusFromDurations($duree_avant_fin_service, $duree_min_resa, $blocVideFin, $isFreakUnavailable);
                        $duree_bloc_s = $this->durationToSeconds($duree_avant_fin_service);
                        $blocVideFin->setRatio(round($duree_bloc_s / $duree_totale_s, 2));
                        $blocVideFin->setHeureDebut(new \DateTime($lastBloc->getHeureFin()));
                        $blocVideFin->setHeureFin($date_fin_schedule);
                        $blocs[] = $blocVideFin;
                    }
                } else {
                    $blocEntier = new BookingBloc();
                    if ($isFreakUnavailable)
                        $blocEntier->setStatus("Non Réservable");
                    else
                        $blocEntier->setStatus("Réservable");
                    $blocEntier->setHeureDebut($date_debut_schedule);
                    $blocEntier->setHeureFin($date_fin_schedule);
                    $blocEntier->setDuration($duree_totale_service);
                    $blocEntier->setRatio(1);
                    $blocs[] = $blocEntier;
                }
            }
            return $blocs;
        }else{
            return new BadRequestHttpException("This schedule is not set for the same day that you've asked.");
        }
    }

}
