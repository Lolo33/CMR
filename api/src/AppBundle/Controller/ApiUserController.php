<?php

namespace AppBundle\Controller;


use AppBundle\Entity\ApiUser;
use AppBundle\Form\ApiUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApiUserController extends Controller
{

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"userApi"})
     * @Rest\Post("/api-users")
     */
    public function postApiUserAction(Request $request)
    {
        $user = new ApiUser();
        $form = $this->createForm(ApiUserType::class, $user);

        $em = $this->get('doctrine.orm.entity_manager');

        $form->submit($request->request->all());

        $user_existing = $this->getDoctrine()->getRepository('AppBundle:ApiUser')->findBy(array("userClientId" => $request->get("userClientId")));
        if (!empty($user_existing)){
            throw new BadRequestHttpException("BadRequest : This clientId already exists");
        }
        if ($form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            // le mot de passe en clair est encodé avant la sauvegarde
            $encoded = $encoder->encodePassword($user, $user->getUserPlainPassword());
            $user->setUserPassword($encoded);


            $em->persist($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }

}
