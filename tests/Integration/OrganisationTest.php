<?php

namespace Tests\Integration;

use Tests\TestCase;

class OrganisationTest extends TestCase
{
    private $endpoint = '/organisation';

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
