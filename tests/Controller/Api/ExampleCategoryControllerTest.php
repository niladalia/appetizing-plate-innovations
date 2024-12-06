<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExampleCategoryControllerTest extends WebTestCase
{
    public function testGetExampleCategories(): void
    {
        $client = static::createClient();
        $response = $client->request('GET', '/api/example_category');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
