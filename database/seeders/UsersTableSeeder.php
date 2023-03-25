<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    const ID_CONSOLE = 1;
    const ID_WEB = 2;
    const ID_3 = 3;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $consoleUserId = 1;
        DB::table('users')->upsert([
            'id' => $consoleUserId,
            'firstname' => 'web',
            'lastname' => 'console',
            'email' => '',
            'enabled' => 1,
            'created_at' => new \DateTime()
        ], ['id' => $consoleUserId]);

        $userId = 2;
        DB::table('users')->upsert([
            'id' => $userId,
            'firstname' => Str::random(10),
            'lastname' => Str::random(10),
            'email' => 'its.inevitable@hotmail.com',
            'password_hash' => Hash::make('password'),
            'dob' => '1989/05/10',
            'enabled' => 1,
            'created_at' => new \DateTime()
        ], ['id' => $userId]);

        $user3Id = 3;
        DB::table('users')->upsert([
            'firstname' => Str::random(10),
            'lastname' => Str::random(10),
            'email' => Str::random(10) . '@hotmail.com',
            'password_hash' => Hash::make('password'),
            'dob' => '2003/05/10',
            'enabled' => 1,
            'created_at' => new \DateTime()
        ], ['id' => $user3Id]);
    }
}
