<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RestaurantsController extends Controller
{
    public function getRestaurantsAction()
    {
        return $this->render('AppBundle:Restaurants:get_restaurants.html.twig', array(
            // ...
        ));
    }

}
