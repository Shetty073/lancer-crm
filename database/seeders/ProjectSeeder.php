<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::insert([
            [
                'name' => 'Piramal Vaikunth',
                'details' => 'Balkum | 32 acres | 12 towers',
            ],
        ]);

    }
}
