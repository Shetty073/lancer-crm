<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // seeders
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ChequeStatusSeeder::class);
        $this->call(EnquiryStatusSeeder::class);
        $this->call(ConfigurationSeeder::class);
        $this->call(BudgetRangeSeeder::class);
        $this->call(PaymentModeSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(ExpenseCategorySeeder::class);
    }
}
