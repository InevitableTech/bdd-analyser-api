<?php

namespace Tests\Integration;

use Tests\TestCase;

class TokenTest extends TestCase
{
    private $endpoint = '/token';

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
