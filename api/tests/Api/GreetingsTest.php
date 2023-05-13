<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class GreetingsTest extends ApiTestCase
{
    public function testCreateGreeting(): void
    {
        static::createClient()->request(
            'POST',
            '/time',
            ['headers' => ['accept' => ['application/json']],
                'json' => ['shortId' => 'H4RGYLKDWW7N6BWCDMQHNZAHW2'], ]
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            'date' => 'Kévin',
        ]);
    }
}
