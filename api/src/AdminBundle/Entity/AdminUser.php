<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * AdminUser
 *
 * @ORM\Table(name="admin_user")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\AdminUserRepository")
 */
class AdminUser implements UserInterface
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
     * @ORM\Column(name="userClientId", type="string", length=255)
     */
    private $userClientId;

    /**
     * @var string
     *
     * @ORM\Column(name="userPassword", type="string", length=255)
     */
    private $userPassword;

    private $userPlainPassword;

    /**
     * @var int
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->userClientId;
    }
    public function getPassword()
    {
        return $this->userPassword;
    }

    /**
     * Set userPlainPassword
     *
     * @param string $userPlainPassword
     *
     * @return ApiUser
     */
    public function setUserPlainPassword($userPlainPassword)
    {
        $this->userPlainPassword = $userPlainPassword;

        return $this;
    }

    /**
     * Get userPlainPassword
     *
     * @return string
     */
    public function getUserPlainPassword()
    {
        return $this->userPlainPassword;
    }

    public function getRoles()
    {
        return [];
    }

    public function getSalt()
    {
        return null;
    }
    public function eraseCredentials()
    {
        $this->userPassword = null;
    }

    /**
     * Set userClientId
     *
     * @param string $userClientId
     *
     * @return AdminUser
     */
    public function setUserClientId($userClientId)
    {
        $this->userClientId = $userClientId;

        return $this;
    }

    /**
     * Get userClientId
     *
     * @return string
     */
    public function getUserClientId()
    {
        return $this->userClientId;
    }

    /**
     * Set userPassword
     *
     * @param string $userPassword
     *
     * @return AdminUser
     */
    public function setUserPassword($userPassword)
    {
        $this->userPassword = $userPassword;

        return $this;
    }

    /**
     * Get userPassword
     *
     * @return string
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     *
     * @return AdminUser
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }
}

