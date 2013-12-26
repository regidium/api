<?php

namespace Regidium\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CoreControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/app/v1/Alexey');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Hello Alexey")')->count());
    }
}
