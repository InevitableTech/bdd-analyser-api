<?php

namespace Tests\Integration;

use Tests\TestCase;

class OrganisationTest extends TestCase
{
    protected $baseUrl = 'http://localhost:8000';

    private $endpoint = '/organisation';

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
    public function testGetOrganisation()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->defaultHeaders);

        $response->assertJson([
                'success' => true,
                'data' => [
                    [
                    'name' => 'InevitableTech.uk',
                    ]
                ]
            ]);

        $response
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ])
        ;
    }
}
