<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Token;

class TokenTableSeeder extends Seeder
{
    const ID_1 = 1;

    const ID_2 = 2;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $consoleUserTokenId = 1;
        DB::table('tokens')->upsert([
            'id' => $consoleUserTokenId,
            'token' => 'eyJpdiI6InpXOTBueWVjWVFHRG5uMktDVkVZTmc9PSIsInZhbHVlIjoibzU1L0FVNnRtR2NFN3JOL2dMSzRCRlg1bHdRR1RQTkVnTGRwWngvMWszTjk5eTMzNzlmWUZQVDE3MzBsbktqMkh3NGt2Umhza0k1aGVNZEJDSGJ3Unc9PSIsIm1hYyI6IjBmMjZhNWYzZTllMGNhNjQyODc5NGQ3ZWJkOTNiZjM2N2NmNzg3MTQzYWI5YTljOGY2ZjcwMWU3NzZkYzk1NDMiLCJ0YWciOiIifQ==',
            'expires_on' => new \DateTime('+3 years'),
            'user_id' => UsersTableSeeder::ID_CONSOLE,
            'policies' => json_encode(['resources' => '*']),
            'type' => Token::TYPE_CONSOLE,
            'description' => 'default',
            'created_at' => new \DateTime()
        ], ['id' => $consoleUserTokenId]);

        // Can a user have multiple tokens? Yes if the policies differ.
        // Is there such a thing as an app token?
        $tokenId = 2;
        DB::table('tokens')->upsert([
            'id' => $tokenId,
            'token' => 'eyJpdiI6Im5mc20wMkhMbU5MRFMzanBSckh2Q2c9PSIsInZhbHVlIjoiYVNCMUF2UkJhRlV3ZnRzMzdGOVFJLzF4azhvVUlYc1AyQ3FwQ1NFdU1HRE9jWVJSRkdJL1ROMDN5cEllNTdSaSIsIm1hYyI6ImQ5NTk3NzgzNzEzNTQwN2E1MGNlZTE3MTM4NDA1YzAxMGE4M2Q1OTliNzYyN2E1MWIwYWMxZDQzYjE4NDJlYjMiLCJ0YWciOiIifQ==',
            'expires_on' => new \DateTime('+3 years'),
            'user_id' => UsersTableSeeder::ID_WEB,
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
        ], ['id' => $tokenId]);
    }
}
