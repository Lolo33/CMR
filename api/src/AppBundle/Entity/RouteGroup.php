<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RouteGroup
 *
 * @ORM\Table(name="route_group")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RouteGroupRepository")
 */
class RouteGroup
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
     * @ORM\Column(name="groupName", type="string", length=255)
     */
    private $groupName;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="Route", mappedBy="routeGroup")
     */
    private $routes;


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
     * Set groupName
     *
     * @param string $groupName
     *
     * @return RouteGroup
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;

        return $this;
    }

    /**
     * Get groupName
     *
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * Set routes
     *
     * @param string $routes
     *
     * @return RouteGroup
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * Get routes
     *
     * @return string
     */
    public function getRoutes()
    {
        return $this->routes;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->routes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add route
     *
     * @param \AppBundle\Entity\Route $route
     *
     * @return RouteGroup
     */
    public function addRoute(\AppBundle\Entity\Route $route)
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * Remove route
     *
     * @param \AppBundle\Entity\Route $route
     */
    public function removeRoute(\AppBundle\Entity\Route $route)
    {
        $this->routes->removeElement($route);
    }
}
