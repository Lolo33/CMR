<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RestaurantControllerControllerTest extends WebTestCase
{
    public function testGetrestaurants()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getRestaurants');
    }

    public function testGetrestaurant()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getRestaurant');
    }

}
