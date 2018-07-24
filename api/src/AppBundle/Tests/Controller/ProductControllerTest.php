<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testGetproducts()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getProducts');
    }

    public function testGetproduct()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getProduct');
    }

    public function testPostsoldout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/postSoldOut');
    }

}
