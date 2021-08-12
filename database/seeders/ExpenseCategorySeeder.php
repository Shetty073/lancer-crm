<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExpenseCategory::insert([
            [
                'name' => 'IT Cost',
                'remark' => 'IT development + maintenance costs'
            ],
            [
                'name' => 'Marketing Cost',
                'remark' => 'Ad campaigning costs',
            ],
            [
                'name' => 'Travelling/Logistics',
                'remark' => 'Travelling or logistics costs',
            ],
        ]);

    }
}
