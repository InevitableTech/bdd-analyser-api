<?php

namespace Tests\Integration;

use Exception;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Mail\Mailer;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;

/**
 * This file is meant to be run in its entirety - as one big test.
 */
class EndToEndTest extends TestCase
{
    private $tokenFilePath = __DIR__ . '/../build/token.txt';

    private static $data = [];

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $this->setToken();
    }

    private function setToken()
    {
        file_put_contents($this->tokenFilePath, $this->defaultHeaders['user_token']);
    }

    public function testGetExistingToken()
    {
        $token = file_get_contents($this->tokenFilePath);
        $this->defaultHeaders['user_token'] = $token;

        $tokenDetails = $this->json('GET', '/token', $this->defaultHeaders);

        self::assertTrue(is_int($tokenDetails['data'][0]['user_id']));

        self::$data['userId'] = $tokenDetails['data'][0]['user_id'];
    }

    public function testCreateProject()
    {
        $token = file_get_contents($this->tokenFilePath);
        $this->defaultHeaders['user_token'] = $token;

        $projectDetails = [
            'name' => 'testProject',
            'user_id' => self::$data['userId']
        ];

        $projectResponse = $this->json('POST', '/project', $projectDetails, $this->defaultHeaders);

        self::assertTrue($projectResponse['success']);
        self::assertTrue(is_int($projectResponse['data']['id']));

        self::$data['projectId'] = $projectResponse['data']['id'];
    }

    public function testCreateAnalysis()
    {
        $token = file_get_contents($this->tokenFilePath);
        $this->defaultHeaders['user_token'] = $token;

        // We've now got enough to send the analysis across.
        $analysisData = [
            'user_id' => self::$data['userId'],
            'project_id' => self::$data['projectId'],
            'run_at' => (new \DateTime('now'))->format('Y-m-d H:i:s'),
            'violations' => json_encode(['abc123' => 'issue1']),
            'summary' => json_encode(['files' => 3]),
            'active_rules' => json_encode(['Forceedge01\\BDDStaticAnalyserRules\\Rules\\NoFeatureWithoutNarrative']),
            'active_steps' => json_encode(['I am on the dashboard']),
            'severities' => '["1", "2"]',
            'rules_version' => '1.3.0',
            'branch' => 'main',
            'commit_hash' => 'asdfasdfa'
        ];
        $analysisResponse = $this->json('POST', '/analysis', $analysisData, $this->defaultHeaders);

        $analysisResponse->assertStatus(201);

        self::assertTrue($analysisResponse['success']);
        self::assertArrayHasKey('id', $analysisResponse['data']);
        self::assertTrue(is_int($analysisResponse['data']['id']));
    }

    public function testDeleteProject()
    {
        $token = file_get_contents($this->tokenFilePath);
        $this->defaultHeaders['user_token'] = $token;

        $projectResponse = $this->json('DELETE', sprintf("/project/%d", self::$data['projectId']), [], $this->defaultHeaders);

        self::assertTrue($projectResponse['success']);
    }
}
