<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderStatusControllerTest extends WebTestCase
{
    public function testGetstatus()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getStatus');
    }

    public function testPoststatus()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/postStatus');
    }

}
