<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingControllerTest extends WebTestCase
{
    public function testGetbookings()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getBookings');
    }

    public function testGetbooking()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getBooking');
    }

    public function testGetbookingrestaurant()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getBookingRestaurant');
    }

    public function testPostbooking()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/postBooking');
    }

    public function testRemovebooking()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/removeBooking');
    }

}
