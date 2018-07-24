<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"restaurants"})
     * @Rest\Post("/products/{id}/sold-out")
     */
    public function getProductsAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $product = $this->getDoctrine()->getRepository("AppBundle:Product")->find($request->get("id"));
        if (!$product || empty($product))
            return Globals::errNotFound("This product was not found");
        if ($request->get("sold-out") == null)
            throw new BadRequestHttpException("Required parameter \"sold-out\" is missing");
        $is_sold_out = $request->get("sold-out");
        if ($is_sold_out == "0")
            $product->setIsSoldOut(false);
        elseif ($is_sold_out == "1")
            $product->setIsSoldOut(true);
        else
            throw new BadRequestHttpException("This value is not valid. Please set \"sold-out\" to 0 (false) or 1 (true)");
        $em->merge($product);
        $em->flush();
        return $product;
    }

    /**
     *
     */
    public function getProductAction()
    {
        return $this->render('AppBundle:Product:get_product.html.twig', array(
            // ...
        ));
    }

    /**
     *
     */
    public function postSoldOutAction()
    {
        return $this->render('AppBundle:Product:post_sold_out.html.twig', array(
            // ...
        ));
    }

}
