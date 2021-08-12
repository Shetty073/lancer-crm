<?php

namespace Database\Seeders;

use App\Models\BudgetRange;
use Illuminate\Database\Seeder;

class BudgetRangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BudgetRange::insert([
            ['range' => '20L - 30L'],
            ['range' => '30L - 40L'],
            ['range' => '40L - 50L'],
            ['range' => '50L - 60L'],
            ['range' => '60L - 70L'],
            ['range' => '70L - 80L'],
            ['range' => '80L - 90L'],
            ['range' => '90L - 1Cr'],
            ['range' => '1Cr - 1.2Cr'],
            ['range' => '1.2Cr - 1.5Cr'],
            ['range' => '1.5Cr and above'],
        ]);

    }
}
