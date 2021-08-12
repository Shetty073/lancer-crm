<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configuration::insert([
            ['name' => '1 BHK'],
            ['name' => '2 BHK'],
            ['name' => '3 BHK'],
            ['name' => '4 BHK'],
            ['name' => 'Plot'],
            ['name' => 'Commercial'],
            ['name' => 'Other'],
        ]);

    }
}
