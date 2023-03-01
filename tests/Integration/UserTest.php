<?php

namespace Tests\Integration;

use Tests\TestCase;

class UserTest extends TestCase
{
    protected $baseUrl = 'http://localhost:8000';

    private $endpoint = '/user';

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
    public function testGetUser()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->defaultHeaders);
        $response->assertJson([
                'success' => true,
                'data' => [
                    ['email' => 'its.inevitable@hotmail.com',]
                ]
            ]);

        $response
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'uri',
                        'firstname',
                        'lastname',
                        'email',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ])
        ;
    }
}
