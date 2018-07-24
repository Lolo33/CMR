<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UtilsController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"country-list"})
     * @Rest\Get("/admin/countries")
     */
    public function getCountriesAction()
    {
        return $this->getDoctrine()->getRepository("AppBundle:Country")->findAll();
    }

}
