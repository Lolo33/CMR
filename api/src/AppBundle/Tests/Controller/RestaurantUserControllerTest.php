<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RestaurantUserControllerTest extends WebTestCase
{
    public function testConnectrestaurantuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/connectRestaurantUser');
    }

}
