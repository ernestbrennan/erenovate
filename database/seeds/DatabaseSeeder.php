<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $g_project = \App\Models\GuaranteeProject::create([
            'project_id' => 929,
            'contractor_id' => 25613
        ]);

        $contract = \App\Models\Contract::create([
            'guarantee_project_id' => '',
            'hash' => str_random(16),
            'status' => 'pending',
        ]);

    }
}
