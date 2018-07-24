<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PaymentModeController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"restaurant"})
     * @Rest\Get("/payment-modes")
     */
    public function getPaymentModesAction()
    {
        $modes = $this->getDoctrine()->getRepository("AppBundle:PaymentMode")->findAll();
        return $modes;
    }

}
