<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exchange
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExchangeRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"mail" = "MailExchange", "tel" = "TelephoneExchange"})
 */
abstract class Exchange
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var Interlocutor
     *
     * @ORM\ManyToOne(targetEntity="Interlocutor")
     */
    private $interlocutor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function isTelephoneInstance() {
        return $this instanceof TelephoneExchange;
    }

    public function isMailInstance() {
        return $this instanceof MailExchange;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Exchange
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Exchange
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Exchange
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set interlocutor
     *
     * @param \AppBundle\Entity\Interlocutor $interlocutor
     *
     * @return Exchange
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
