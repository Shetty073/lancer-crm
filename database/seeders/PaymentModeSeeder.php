<?php

namespace Database\Seeders;

use App\Models\PaymentMode;
use Illuminate\Database\Seeder;

class PaymentModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMode::create([
            'name' => 'Credit/Debit swipe',
            'is_digital' => true,
        ]);

        PaymentMode::create([
            'name' => 'Cheque',
            'is_cheque' => true,
        ]);

        PaymentMode::create([
            'name' => 'Cash',
            'is_cash' => true,
        ]);

        PaymentMode::create([
            'name' => 'Bank transfer',
        ]);

        PaymentMode::create([
            'name' => 'Other',
        ]);

    }
}
