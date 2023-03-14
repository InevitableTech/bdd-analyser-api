<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

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
            'created_at' => new \DateTime()
        ]);

        DB::table('project_user')->insert([
            'user_id' => $userId,
            'project_id' => $projectId,
            'created_at' => new \DateTime()
        ]);

        DB::table('tokens')->insert([
            'token' => Crypt::encryptString(Str::random(60)),
            'expires_on' => new \DateTime('+3 years'),
            'user_id' => $consoleUserId,
            'policies' => json_encode(['resources' => '*']),
            'created_at' => new \DateTime()
        ]);

        // Can a user have multiple tokens? Yes if the policies differ.
        // Is there such a thing as an app token?
        DB::table('tokens')->insert([
            'token' => Crypt::encryptString('kahlsjdhfjh2h34234k2h4j2j3hk4h2jak=='),
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
            'created_at' => new \DateTime()
        ]);

        DB::table('analysis')->insert([
            'run_at' => new \DateTime('2023-01-01'),
            'rules_version' => 'v1',
            'outcomes' => json_encode([
                'file1' => [
                    'line 45' => 'issue',
                    'message' => 'something'
                ]
            ]),
            'active_rules' => json_encode([
                "Forceedge01\\BDDStaticAnalyserRules\\Rules\\NoEmptyFeature",
            ]),
            'summary' => json_encode([
                "files" => 12
            ]),
            'created_at' => new \DateTime(),
            'branch' => 'main',
            'project_id' => $projectId,
            'user_id' => $userId,
        ]);
    }
}
