<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TelephoneExchange
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TelephoneExchangeRepository")
 */
class TelephoneExchange extends Exchange
{

    /**
     * @var string
     *
     * @ORM\Column(name="telephoneNumber", type="string", length=255)
     */
    private $telephoneNumber;

    /**
     * Set telephoneNumber
     *
     * @param string $telephoneNumber
     *
     * @return TelephoneExchange
     */
    public function setTelephoneNumber($telephoneNumber)
    {
        $this->telephoneNumber = $telephoneNumber;

        return $this;
    }

    /**
     * Get telephoneNumber
     *
     * @return string
     */
    public function getTelephoneNumber()
    {
        return $this->telephoneNumber;
    }
}

