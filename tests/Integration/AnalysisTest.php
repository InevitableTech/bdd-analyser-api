<?php

namespace Tests\Integration;

use Tests\TestCase;

class AnalysisTest extends TestCase
{
    private $endpoint = '/analysis';

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
            $token->getContent()
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAnalaysis()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->defaultHeaders);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'run_at',
                        'rules_version',
                        'id',
                        'violations',
                        'summary',
                        'active_rules',
                        'active_steps',
                        'branch',
                        'commit_hash',
                        'relations',
                        'severities',
                        'uri',
                        'user_id',
                        'project_id',
                    ]
                ]
            ])
        ;
    }
}
