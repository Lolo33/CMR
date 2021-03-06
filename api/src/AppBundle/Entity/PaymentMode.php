<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentMode
 *
 * @ORM\Table(name="payment_mode")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaymentModeRepository")
 */
class PaymentMode
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
     * @ORM\Column(name="mode_name", type="string", length=255)
     */
    private $modeName;

    /**
     * @var string
     *
     * @ORM\Column(name="mode_code", type="text")
     */
    private $modeCode;


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
     * Set modeName
     *
     * @param string $modeName
     *
     * @return PaymentMode
     */
    public function setModeName($modeName)
    {
        $this->modeName = $modeName;

        return $this;
    }

    /**
     * Get modeName
     *
     * @return string
     */
    public function getModeName()
    {
        return $this->modeName;
    }



    /**
     * Set modeCode
     *
     * @param string $modeCode
     *
     * @return PaymentMode
     */
    public function setModeCode($modeCode)
    {
        $this->modeCode = $modeCode;

        return $this;
    }

    /**
     * Get modeCode
     *
     * @return string
     */
    public function getModeCode()
    {
        return $this->modeCode;
    }
}
