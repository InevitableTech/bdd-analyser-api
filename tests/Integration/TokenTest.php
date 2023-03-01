<?php

namespace Tests\Integration;

use Tests\TestCase;

class TokenTest extends TestCase
{
    protected $baseUrl = 'http://localhost:8000';

    private $endpoint = '/token';

    protected $defaultHeaders = [
        'api_token' => 'averysecrettokenforapiauthorization==',
        'user_token' => 'kahlsjdhfjh2h34234k2h4j2j3hk4h2jak==',
        'accept-version' => 'v1'
    ];

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetToken()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->defaultHeaders);
        $response->assertJson([
                'success' => true,
                'data' => [
                    ['token' => $this->defaultHeaders['user_token'],]
                ]
            ]);

        $response
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'uri',
                        'token',
                        'created_at',
                        'expires_on',
                    ]
                ]
            ])
        ;
    }
}
