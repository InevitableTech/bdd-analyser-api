<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends BaseTestCase
{
    protected $baseUrl = 'http://localhost:8000';

    protected $defaultHeaders = [
        'api_token' => 'averysecrettokenforapiauthorization==',
        'user_token' => 'eyJpdiI6Im5mc20wMkhMbU5MRFMzanBSckh2Q2c9PSIsInZhbHVlIjoiYVNCMUF2UkJhRlV3ZnRzMzdGOVFJLzF4azhvVUlYc1AyQ3FwQ1NFdU1HRE9jWVJSRkdJL1ROMDN5cEllNTdSaSIsIm1hYyI6ImQ5NTk3NzgzNzEzNTQwN2E1MGNlZTE3MTM4NDA1YzAxMGE4M2Q1OTliNzYyN2E1MWIwYWMxZDQzYjE4NDJlYjMiLCJ0YWciOiIifQ==',
        'accept-version' => 'v1'
    ];

    use CreatesApplication;
}
