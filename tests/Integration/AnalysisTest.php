<?php

namespace Tests\Integration;

use Tests\TestCase;

class AnalysisTest extends TestCase
{
    protected $baseUrl = 'http://localhost:8000';

    private $endpoint = '/analysis';

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
        $response = $this->json('GET', $this->endpoint . '/1', [], $this->defaultHeaders);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'run_at' => '2023-01-01 00:00:00',
                    'rules_version' => 'v1',
                    'id' => 1,
                    'outcomes' => ["file1" => ["line 45" => "issue", "message" => "something"]],
                    'summary' => ['files' => 12],
                    'active_rules' => ["Forceedge01\\BDDStaticAnalyserRules\\Rules\\NoEmptyFeature"],
                    'uri' => '/analysis/1',
                    'user_id' => 1,
                    'project_id' => 1,
                ]
            ])
        ;

        // $response
        //     ->assertJsonStructure([
        //         'success',
        //         'data' => [
        //             '*' => []
        //         ]
        //     ])
        // ;
    }
}
