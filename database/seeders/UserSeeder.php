<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => '',
        ]);
        $user->setPasswordAttribute('admin123');
        $user->save();

        // Give admin rle to the user
        $role = Role::where(['name' => 'Admin'])->first();
        $user->assignRole($role);
    }
}
