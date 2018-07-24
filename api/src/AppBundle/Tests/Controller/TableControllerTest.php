<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TableControllerTest extends WebTestCase
{
    public function testGettables()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getTables');
    }

    public function testGettable()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getTable');
    }

    public function testPosttable()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/postTable');
    }

    public function testRemovetable()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/removeTable');
    }

}
