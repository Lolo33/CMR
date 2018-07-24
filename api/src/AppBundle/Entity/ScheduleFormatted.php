<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 03/01/2018
 * Time: 13:32
 */

namespace AppBundle\Entity;


class ScheduleFormatted
{

    private $date;
    private $day;
    private $dayString;
    private $lang;
    private $schedule;
    private $month;
    private $monthString;
    private $hourOpening;
    private $hourClosing;
    private $status;
    private $year;

    private function putMonthInString(){
        switch ($this->month){
            case "01":
                $this->monthString = "Janvier";
                break;
            case "02":
                $this->monthString = "Février";
                break;
            case "03":
                $this->monthString = "Mars";
                break;
            case "04":
                $this->monthString = "Avril";
                break;
            case "05":
                $this->monthString = "Mai";
                break;
            case "06":
                $this->monthString = "Juin";
                break;
            case "07":
                $this->monthString = "Juillet";
                break;
            case "08":
                $this->monthString = "Aout";
                break;
            case "09":
                $this->monthString = "Septembre";
                break;
            case "10":
                $this->monthString = "Octobre";
                break;
            case "11":
                $this->monthString = "Novembre";
                break;
            case "12":
                $this->monthString = "Décembre";
                break;
        }
    }

    private function putDayInString(){
        switch ($this->date->format("w")){
            case 0:
                $this->dayString = "Dimanche";
                break;
            case 1:
                $this->dayString = "Lundi";
                break;
            case  2:
                $this->dayString = "Mardi";
                break;
            case 3:
                $this->dayString = "Mercredi";
                break;
            case 4:
                $this->dayString = "Jeudi";
                break;
            case 5:
                $this->dayString = "Vendredi";
                break;
            case 6:
                $this->dayString = "Samedi";
                break;
        }
    }

    public function __construct($schedule, $date, $lang)
    {
        $this->schedule = $schedule;
        $this->date = $date;
        $this->lang = $lang;
        $this->day = $date->format("m");;
        $this->month = $date->format("m");
        $this->year = $date->format("Y");
        $this->hourOpening = new \DateTime($schedule->getHourOpening());
        $this->hourClosing = new \DateTime($schedule->getHourClosing());
        if ($schedule->getRestaurant()->getActive())
            $this->status = "Ouvert";
        else
            $this->status = "Fermé";
        $this->putDayInString();
        $this->putMonthInString();
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return mixed
     */
    public function getDayString()
    {
        return $this->dayString;
    }

    /**
     * @param mixed $dayString
     */
    public function setDayString($dayString)
    {
        $this->dayString = $dayString;
    }

    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param mixed $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return mixed
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @param mixed $schedule
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }

    /**
     * @return mixed
     */
    public function getMonthString()
    {
        return $this->monthString;
    }

    /**
     * @param mixed $monthString
     */
    public function setMonthString($monthString)
    {
        $this->monthString = $monthString;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getHourOpening()
    {
        return $this->hourOpening->format("Y-m-d H:i:s");;
    }

    /**
     * @param mixed $hourOpening
     */
    public function setHourOpening($hourOpening)
    {
        $this->hourOpening = $hourOpening;
    }

    /**
     * @return mixed
     */
    public function getHourClosing()
    {
        return $this->hourClosing->format("Y-m-d H:i:s");
    }

    /**
     * @param mixed $hourClosing
     */
    public function setHourClosing($hourClosing)
    {
        $this->hourClosing = $hourClosing;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }





}