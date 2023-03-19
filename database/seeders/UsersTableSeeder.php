<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use App\Models\Token;
use File;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $consoleUserId = DB::table('users')->insertGetId([
            'firstname' => 'web',
            'lastname' => 'console',
            'email' => '',
            'enabled' => 1,
            'created_at' => new \DateTime()
        ]);

        $userId = DB::table('users')->insertGetId([
            'firstname' => Str::random(10),
            'lastname' => Str::random(10),
            'email' => 'its.inevitable@hotmail.com',
            'password_hash' => Hash::make('password'),
            'dob' => '1989/05/10',
            'enabled' => 1,
            'created_at' => new \DateTime()
        ]);

        DB::table('users')->insertGetId([
            'firstname' => Str::random(10),
            'lastname' => Str::random(10),
            'email' => Str::random(10) . '@hotmail.com',
            'password_hash' => Hash::make('password'),
            'dob' => '2003/05/10',
            'enabled' => 1,
            'created_at' => new \DateTime()
        ]);

        $organisationId = DB::table('organisations')->insertGetId([
            'name' => 'InevitableTech.uk',
            'created_at' => new \DateTime()
        ]);

        $projectId = DB::table('projects')->insertGetId([
            'name' => 'BDD Analyser',
            'organisation_id' => $organisationId,
            'enabled' => 1,
            'main_branch' => 'main',
            'repo_url' => 'https://github.com/InevitableTech/bdd-analyser-api',
            'created_at' => new \DateTime()
        ]);

        DB::table('project_user')->insert([
            'user_id' => $userId,
            'project_id' => $projectId,
            'created_at' => new \DateTime()
        ]);

        DB::table('tokens')->insert([
            'token' => 'eyJpdiI6InpXOTBueWVjWVFHRG5uMktDVkVZTmc9PSIsInZhbHVlIjoibzU1L0FVNnRtR2NFN3JOL2dMSzRCRlg1bHdRR1RQTkVnTGRwWngvMWszTjk5eTMzNzlmWUZQVDE3MzBsbktqMkh3NGt2Umhza0k1aGVNZEJDSGJ3Unc9PSIsIm1hYyI6IjBmMjZhNWYzZTllMGNhNjQyODc5NGQ3ZWJkOTNiZjM2N2NmNzg3MTQzYWI5YTljOGY2ZjcwMWU3NzZkYzk1NDMiLCJ0YWciOiIifQ==',
            'expires_on' => new \DateTime('+3 years'),
            'user_id' => $consoleUserId,
            'policies' => json_encode(['resources' => '*']),
            'type' => Token::TYPE_CONSOLE,
            'description' => 'default',
            'created_at' => new \DateTime()
        ]);

        // Can a user have multiple tokens? Yes if the policies differ.
        // Is there such a thing as an app token?
        DB::table('tokens')->insert([
            'token' => 'eyJpdiI6Im5mc20wMkhMbU5MRFMzanBSckh2Q2c9PSIsInZhbHVlIjoiYVNCMUF2UkJhRlV3ZnRzMzdGOVFJLzF4azhvVUlYc1AyQ3FwQ1NFdU1HRE9jWVJSRkdJL1ROMDN5cEllNTdSaSIsIm1hYyI6ImQ5NTk3NzgzNzEzNTQwN2E1MGNlZTE3MTM4NDA1YzAxMGE4M2Q1OTliNzYyN2E1MWIwYWMxZDQzYjE4NDJlYjMiLCJ0YWciOiIifQ==',
            'expires_on' => new \DateTime('+3 years'),
            'user_id' => $userId,
            'policies' => json_encode([
                'resources' => [
                    '/user' => '*',
                    '/project' => '*',
                    '/analysis' => '*',
                    '/organisation' => '*',
                    '/token' => '*'
                ]
            ]),
            'type' => Token::TYPE_CONSOLE,
            'description' => 'default',
            'created_at' => new \DateTime()
        ]);

        $violationsJson = File::get("database/data/violations.json");
        $activeRulesJson = File::get("database/data/violations.json");
        $activeStepsJson = File::get("database/data/violations.json");
        $summaryJson = File::get("database/data/summary.json");

        DB::table('analysis')->insert([
            'run_at' => new \DateTime('2023-01-01'),
            'rules_version' => 'v1',
            'violations' => json_encode($violationsJson),
            'summary' => json_encode($summaryJson),
            'active_steps' => json_encode($activeStepsJson),
            'active_rules' => json_encode($activeRulesJson),
            'severities' => '"[\"0\",\"1\",\"2\",\"3\",\"4\"]"',
            'branch' => 'main',
            'commit_hash' => '7da73f3de7394a273587f872065f75a89c68066e',
            'created_at' => new \DateTime(),
            'project_id' => $projectId,
            'user_id' => $userId,
        ]);

        DB::table('analysis')->insert([
            'run_at' => new \DateTime('2023-01-03'),
            'rules_version' => 'v1',

            'violations' => json_encode($violationsJson),
            'summary' => json_encode($summaryJson),
            'active_steps' => json_encode($activeStepsJson),
            'active_rules' => json_encode($activeRulesJson),
            'severities' => '"[\"0\",\"1\",\"2\",\"3\",\"4\"]"',
            'branch' => 'main',
            'commit_hash' => '7da73f3de7394a273587f872065f75a89c68066e',
            'created_at' => new \DateTime(),
            'project_id' => $projectId,
            'user_id' => $userId,
        ]);
    }
}
