<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectTableSeeder extends Seeder
{
    const ID = 1;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projectId = 1;
        DB::table('projects')->upsert([
            'id' => $projectId,
            'name' => 'BDD Analyser',
            'organisation_id' => OrganisationTableSeeder::ID,
            'enabled' => 1,
            'main_branch' => 'main',
            'repo_url' => 'https://github.com/InevitableTech/bdd-analyser-api',
            'created_at' => new \DateTime()
        ], ['id' => $projectId]);

        $projectUserId = 1;
        DB::table('project_user')->upsert([
            'id' => $projectUserId,
            'user_id' => UsersTableSeeder::ID_WEB,
            'project_id' => $projectId,
            'created_at' => new \DateTime()
        ], ['id' => $projectUserId]);
    }
}
