<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RouteBlackList
 *
 * @ORM\Table(name="route_black_list")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BlackListRepository")
 */
class RouteBlackList
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
     * @ORM\ManyToOne(targetEntity="RestaurantUser", inversedBy="routesBlackListed")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="RouteGroup")
     */
    private $routeGroup;

    /**
     * @var bool
     *
     * @ORM\Column(name="isHidden", type="boolean", options={"default" : false})
     */
    private $isHidden;


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
     * Set user
     *
     * @param string $user
     *
     * @return RouteBlackList
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set routeGroup
     *
     * @param string $routeGroup
     *
     * @return RouteBlackList
     */
    public function setRouteGroup($routeGroup)
    {
        $this->routeGroup = $routeGroup;

        return $this;
    }

    /**
     * Get routeGroup
     *
     * @return string
     */
    public function getRouteGroup()
    {
        return $this->routeGroup;
    }

    /**
     * Set isHidden
     *
     * @param boolean $isHidden
     *
     * @return RouteBlackList
     */
    public function setIsHidden($isHidden)
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    /**
     * Get isHidden
     *
     * @return bool
     */
    public function getIsHidden()
    {
        return $this->isHidden;
    }
}

