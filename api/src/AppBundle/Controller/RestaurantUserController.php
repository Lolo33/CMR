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

class RestaurantUserController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"restaurants"})
     * @Rest\Post("/users")
     */
    public function connectRestaurantUserAction(Request $request)
    {
        $user = $this->getDoctrine()->getRepository("AppBundle:RestaurantUser")->findOneBy(array("username" => $request->get("username")));
        if (empty($user)) {
            $user = $this->getDoctrine()->getRepository("AppBundle:RestaurantUser")->findOneBy(array("email" => $request->get("username")));
            if (empty($user))
                throw new BadRequestHttpException("Cet identifiant n'existe pas");
        }
        if (password_verify($request->get("password"), $user->getPassword()))
            return $user;
        else
            throw new BadRequestHttpException("Ces identifiants / mot de passe ne correspondent pas");
    }

}
