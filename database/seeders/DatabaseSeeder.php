<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            OrganisationTableSeeder::class,
            ProjectTableSeeder::class,
            TokenTableSeeder::class,
            AnalysisTableSeeder::class,
        ]);
    }
}
