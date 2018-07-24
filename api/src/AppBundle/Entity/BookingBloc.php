<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 19/12/2017
 * Time: 10:10
 */

namespace AppBundle\Entity;


class BookingBloc
{

    private $status;
    private $booking;
    private $duration;
    private $ratio;
    private $heure_debut;
    private $heure_fin;

    /**
     * @return mixed
     */
    public function getHeureDebut()
    {
        return $this->heure_debut->format("Y-m-d H:i:s");
    }

    /**
     * @param mixed $heure_debut
     */
    public function setHeureDebut($heure_debut)
    {
        $this->heure_debut = $heure_debut;
    }

    /**
     * @return mixed
     */
    public function getHeureFin()
    {
        return $this->heure_fin->format("Y-m-d H:i:s");
    }

    /**
     * @param mixed $heure_fin
     */
    public function setHeureFin($heure_fin)
    {
        $this->heure_fin = $heure_fin;
    }

    /**
     * @return mixed
     */
    public function getRatio()
    {
        return $this->ratio;
    }
    /**
     * @param mixed $ratio
     */
    public function setRatio($ratio)
    {
        $this->ratio = $ratio;
    }

    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
        return $this;
    }

    public function getBooking(){
        return $this->booking;
    }
    public function setBooking($booking){
        $this->booking = $booking;
        return $this;
    }

    public function getDuration(){
        return $this->duration->format("Y-m-d H:i:s");
    }
    public function setDuration($duration){
        $this->duration = $duration;
        return $this;
    }

}