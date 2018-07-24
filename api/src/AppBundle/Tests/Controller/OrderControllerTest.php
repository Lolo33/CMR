<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    public function testGetorders()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getOrders');
    }

    public function testGetorder()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getOrder');
    }

    public function testPostorder()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/postOrder');
    }

    public function testRemoveorder()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/removeOrder');
    }

}
