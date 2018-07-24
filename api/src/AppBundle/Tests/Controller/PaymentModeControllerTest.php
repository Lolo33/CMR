<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaymentModeControllerTest extends WebTestCase
{
    public function testGetpaymentmodes()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getPaymentModes');
    }

}
