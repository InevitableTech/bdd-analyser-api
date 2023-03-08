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
    protected $baseUrl = 'http://localhost:8000';

    private $tokenFilePath = __DIR__ . '/../build/token.txt';

    private static $data = [];

    protected $defaultHeaders = [
        'api_token' => 'averysecrettokenforapiauthorization==',
        'Accept-Version' => 'v1'
    ];

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function testCreateIfNotKnown()
    {
        // Create user, create token endpoint to be made available without authorisation.
        $userDetails = [
            'firstname' => 'test',
            'lastname' => 'ing',
            'dob' => (new DateTime('1985-05-10'))->format('d/m/Y'),
            'email' => 'its.inevitable+auto' . rand() . '@hotmail.com'
        ];

        $userResponse = $this->json('POST', '/user', $userDetails, $this->defaultHeaders);

        self::assertTrue($userResponse['success']);
        self::assertTrue(is_int($userResponse['data']['id']));

        self::$data['userId'] = $userResponse['data']['id'];
    }

    public function testCreateToken()
    {
        $tokenDetails = ['user_id' => self::$data['userId']];
        $tokenResponse = $this->json('POST', '/token', $tokenDetails, $this->defaultHeaders);

        self::assertTrue($tokenResponse['success']);
        self::assertTrue(is_string($tokenResponse['data']['token']));

        file_put_contents($this->tokenFilePath, $tokenResponse['data']['token']);
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
            'outcomes' => json_encode(['abc123' => 'issue1']),
            'summary' => json_encode(['files' => 3]),
            'active_rules' => json_encode(['Forceedge01\\BDDStaticAnalyserRules\\Rules\\NoFeatureWithoutNarrative']),
            'rules_version' => '1.3.0',
        ];
        $analysisResponse = $this->json('POST', '/analysis', $analysisData, $this->defaultHeaders);

        self::assertTrue($analysisResponse['success']);
        self::assertArrayHasKey('id', $analysisResponse['data']);
        self::assertTrue(is_int($analysisResponse['data']['id']));
    }
}
