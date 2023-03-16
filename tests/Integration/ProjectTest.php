<?php

namespace Tests\Integration;

use Tests\TestCase;

class ProjectTest extends TestCase
{
    private $endpoint = '/project';

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetProject()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->defaultHeaders);

        $response->assertJson([
                'success' => true,
                'data' => [
                    [
                    'name' => 'BDD Analyser',
                    ]
                ]
            ]);

        $response
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'uri',
                        'name',
                        'enabled',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ])
        ;
    }
}
