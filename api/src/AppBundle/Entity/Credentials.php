<?php
/**
 * Created by PhpStorm.
 * User: Niquelesstup
 * Date: 08/06/2017
 * Time: 13:52
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Credentials
{

    /**
     * @Assert\NotNull()
     */
    protected $login;

    /**
     * @Assert\NotNull()
     */
    protected $password;

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}