<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganisationTableSeeder extends Seeder
{
    const ID = 1;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organisationId = 1;
        DB::table('organisations')->upsert([
            'id' => $organisationId,
            'name' => 'InevitableTech.uk',
            'created_at' => new \DateTime()
        ], ['id' => $organisationId]);
    }
}
