<?php

namespace AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UtilsControllerTest extends WebTestCase
{
    public function testGetcountries()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getCountries');
    }

}
