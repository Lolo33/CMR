<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScheduleControllerTest extends WebTestCase
{
    public function testGetschedules()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getSchedules');
    }

    public function testGetschedule()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getSchedule');
    }

}
