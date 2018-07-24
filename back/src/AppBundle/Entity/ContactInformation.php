<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContactInformation
 *
 * @ORM\Table(name="contact_information")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactInformationRepository")
 */
class ContactInformation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @var Interlocutor
     *
     * @ORM\ManyToOne(targetEntity="Interlocutor", inversedBy="contactInformations" ,cascade={"persist"})
     */
    private $interlocutor;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return ContactInformation
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return ContactInformation
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }


    /**
     * Set interlocutor
     *
     * @param \AppBundle\Entity\Interlocutor $interlocutor
     *
     * @return ContactInformation
     */
    public function setInterlocutor(\AppBundle\Entity\Interlocutor $interlocutor = null)
    {
        $this->interlocutor = $interlocutor;

        return $this;
    }

    /**
     * Get interlocutor
     *
     * @return \AppBundle\Entity\Interlocutor
     */
    public function getInterlocutor()
    {
        return $this->interlocutor;
    }
}
