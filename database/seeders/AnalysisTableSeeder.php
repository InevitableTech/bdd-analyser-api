<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use File;

class AnalysisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $violationsJson = File::get("database/data/violations.json");
        $activeRulesJson = File::get("database/data/violations.json");
        $activeStepsJson = File::get("database/data/violations.json");
        $summaryJson = File::get("database/data/summary.json");

        $analysisId = 1;
        DB::table('analysis')->upsert([
            'id' => $analysisId,
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
            'project_id' => ProjectTableSeeder::ID,
            'user_id' => UsersTableSeeder::ID_WEB,
        ], ['id' => $analysisId]);

        $analysisIdSecond = 2;
        DB::table('analysis')->upsert([
            'id' => $analysisIdSecond,
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
            'project_id' => ProjectTableSeeder::ID,
            'user_id' => UsersTableSeeder::ID_WEB,
        ], ['id' => $analysisIdSecond]);
    }
}
