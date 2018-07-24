<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScheduleBookingControllerTest extends WebTestCase
{
    public function testGetscheduleofday()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getScheduleOfDay');
    }

}
