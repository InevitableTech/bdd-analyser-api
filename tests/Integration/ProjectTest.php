<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
{
    protected $baseUrl = 'http://localhost:8000';

    private $endpoint = '/project';

    private $defaultHeaders = [
        'api_token' => 'averysecrettokenforapiauthorization==',
        'user_token' => 'kahlsjdhfjh2h34234k2h4j2j3hk4h2jak==',
        'accept-version' => 'v1'
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->includeRoutes('v1');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testApiVersion()
    {
        // Only gets active token
        $token = $this->get('/');

        $this->assertEquals(
            $this->app->version(),
            $this->response->getContent()
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetProject()
    {
        $this->json('GET', $this->endpoint, [], $this->defaultHeaders)
            ->seeJson([
                'success' => true,
                'name' => 'BDD Analyser',
            ]);

        $this->response
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
