<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingBlocsControllerTest extends WebTestCase
{
    public function testGetblocs()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getBlocs');
    }

}
