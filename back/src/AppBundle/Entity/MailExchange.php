<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MailExchange
 *
 * @ORM\Table(name="mail_exchange")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MailExchangeRepository")
 */
class MailExchange extends Exchange
{

    /**
     * @var bool
     *
     * @ORM\Column(name="isSeen", type="boolean")
     */
    private $isSeen;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $emailAdress;

    /**
     * @var string
     *
     * @ORM\Column(name="mailjetId", type="string", length=255, nullable=true)
     */
    private $mailjetId;

    /**
     * Set isSeen
     *
     * @param boolean $isSeen
     *
     * @return MailExchange
     */
    public function setIsSeen($isSeen)
    {
        $this->isSeen = $isSeen;

        return $this;
    }

    /**
     * Get isSeen
     *
     * @return bool
     */
    public function getIsSeen()
    {
        return $this->isSeen;
    }

    /**
     * Set mailjetId
     *
     * @param string $mailjetId
     *
     * @return MailExchange
     */
    public function setMailjetId($mailjetId)
    {
        $this->mailjetId = $mailjetId;

        return $this;
    }

    /**
     * Get mailjetId
     *
     * @return string
     */
    public function getMailjetId()
    {
        return $this->mailjetId;
    }

    /**
     * Set emailAdress
     *
     * @param string $emailAdress
     *
     * @return MailExchange
     */
    public function setEmailAdress($emailAdress)
    {
        $this->emailAdress = $emailAdress;

        return $this;
    }

    /**
     * Get emailAdress
     *
     * @return string
     */
    public function getEmailAdress()
    {
        return $this->emailAdress;
    }
}
