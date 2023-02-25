<?php

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Mail\Mailer;

class EndToEndTest extends TestCase
{
    protected $baseUrl = 'http://localhost:8000';

    private $tokenFilePath = __DIR__ . '/../build/token.txt';

    private $defaultHeaders = [
        'api_token' => 'averysecrettokenforapiauthorization==',
        'Accept-Version' => 'v1'
    ];

    public function setUp(): void
    {
        // Mock the mailer
        $mock = $this
            ->getMockBuilder('Swift_Mailer')
            ->disableOriginalConstructor()
            ->onlyMethods(['send', 'getTransport'])
            ->addMethods(['setFrom', 'stop'])
            ->getMock();

        $mock->expects($this->any())
            ->method('getTransport')
            ->willReturnSelf();

        parent::setUp();
        $this->app['mailer']->setSwiftMailer($mock);

        $this->includeRoutes($this->defaultHeaders['Accept-Version']);
    }

    public function testCreateIfNotKnown()
    {
        // Create user, create token endpoint to be made available without authorisation.
        $userDetails = [
            'firstname' => 'test',
            'lastname' => 'ing',
            'dob' => (new DateTime('1985-05-10'))->format('Y-m-d H:i:s'),
            'email' => 'its.inevitable+auto' . rand() . '@hotmail.com'
        ];
        $userResponse = $this->json('POST', '/user', $userDetails, $this->defaultHeaders)->response;

        self::assertTrue($userResponse['success'], print_r($userResponse, true));
        self::assertTrue(is_int($userResponse['data']['id']), print_r($userResponse, true));

        $tokenDetails = ['user_id' => $userResponse['data']['id']];
        $tokenResponse = $this->json('POST', '/token', $tokenDetails, $this->defaultHeaders)->response;

        self::assertTrue($tokenResponse['success'], print_r($tokenResponse, true));
        self::assertTrue(is_string($tokenResponse['data']['token']));

        file_put_contents($this->tokenFilePath, $tokenResponse['data']['token']);
    }

    public function testPerformAnalysis()
    {
        $token = file_get_contents($this->tokenFilePath);
        $this->defaultHeaders['user_token'] = $token;

        $tokenDetails = $this->get('/token', $this->defaultHeaders)->response;

        $projectDetails = [
            'name' => 'testProject',
            'user_id' => $tokenDetails->original['data'][0]['user_id']
        ];

        $projectResponse = $this->json('POST', '/project', $projectDetails, $this->defaultHeaders)->response;

        self::assertTrue($projectResponse['success'], print_r($projectResponse, true));
        self::assertTrue(is_int($projectResponse['data']['id']), print_r($projectResponse, true));

        // We've now got enough to send the analysis across.
        $analysisData = [
            'project_id' => $projectResponse['data']['id'],
            'run_at' => (new \DateTime('now'))->format('Y-m-d H:i:s'),
            'outcomes' => ['abc123' => 'issue1'],
            'summary' => ['files' => 3],
            'active_rules' => ['Forceedge01\\BDDStaticAnalyserRules\\Rules\\NoFeatureWithoutNarrative'],
            'rules_version' => '1.3.0',
        ];
        $analysisResponse = $this->json('POST', '/analysis', $analysisData, $this->defaultHeaders)->response;

        self::assertTrue($analysisResponse['success'], print_r($analysisResponse, true));
        self::assertArrayHasKey('id', $analysisResponse['data']);
    }
}
