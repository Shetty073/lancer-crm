<?php

namespace Database\Seeders;

use App\Models\EnquiryStatus;
use Illuminate\Database\Seeder;

class EnquiryStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EnquiryStatus::insert([
            ['status' => 'Oppurtunity'],
            ['status' => 'Suspect'],
            ['status' => 'Cold'],
            ['status' => 'Lost'],
            ['status' => 'Closed'],
        ]);

    }
}
