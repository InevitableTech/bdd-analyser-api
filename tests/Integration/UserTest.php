<?php

namespace Tests\Integration;

use Tests\TestCase;

class UserTest extends TestCase
{
    private $endpoint = '/user';

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
                'data' =>  [
                    [
                    'email' => 'its.inevitable@hotmail.com',
                    ],
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
