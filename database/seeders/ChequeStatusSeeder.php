<?php

namespace Database\Seeders;

use App\Models\ChequeStatus;
use Illuminate\Database\Seeder;

class ChequeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChequeStatus::insert([
            ['status' => 'Received'],
            ['status' => 'Desposited'],
            ['status' => 'Cleared'],
            ['status' => 'Bounced'],
        ]);

    }
}
