<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RestaurantsControllerTest extends WebTestCase
{
    public function testGetrestaurants()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getRestaurants');
    }

}
