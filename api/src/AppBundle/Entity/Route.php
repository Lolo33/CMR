<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Route
 *
 * @ORM\Table(name="route")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RouteRepository")
 */
class Route
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var RouteGroup
     *
     * @ORM\ManyToOne(targetEntity="RouteGroup", inversedBy="routes")
     */
    private $routeGroup;


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
     * Set name
     *
     * @param string $name
     *
     * @return Route
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Route
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set routeGroup
     *
     * @param \AppBundle\Entity\RouteGroup $routeGroup
     *
     * @return Route
     */
    public function setRouteGroup(\AppBundle\Entity\RouteGroup $routeGroup = null)
    {
        $this->routeGroup = $routeGroup;

        return $this;
    }

    /**
     * Get routeGroup
     *
     * @return \AppBundle\Entity\RouteGroup
     */
    public function getRouteGroup()
    {
        return $this->routeGroup;
    }
}
