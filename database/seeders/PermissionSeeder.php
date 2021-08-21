<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'permission_create',
            'permission_edit',
            'permission_show',
            'permission_delete',
            'permission_access',

            'role_create',
            'role_edit',
            'role_show',
            'role_delete',
            'role_access',

            'user_create',
            'user_edit',
            'user_show',
            'user_delete',
            'user_access',

            'enquiry_create',
            'enquiry_edit',
            'enquiry_show',
            'enquiry_delete',
            'enquiry_access',

            'followup_create',
            'followup_edit',
            'followup_show',
            'followup_delete',
            'followup_access',

            'client_create',
            'client_edit',
            'client_show',
            'client_delete',
            'client_access',

            'project_create',
            'project_edit',
            'project_show',
            'project_delete',
            'project_access',

            'payment_create',
            'payment_edit',
            'payment_show',
            'payment_delete',
            'payment_access',

            'report_access',
            'transaction_access',
            'user_management_access',
        ];

        foreach ($permissions as $permission)   {
            Permission::create([
                'name' => $permission
            ]);
        }

        // assign all permissions to Admin role
        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        // Chief Executive role
        $executive = Role::create(['name' => 'Chief Executive']);
        $executivePermissions = [
            'enquiry_create',
            'enquiry_edit',
            'enquiry_show',
            'enquiry_delete',
            'enquiry_access',

            'followup_create',
            'followup_edit',
            'followup_show',
            'followup_delete',
            'followup_access',

            'client_create',
            'client_edit',
            'client_show',
            'client_delete',
            'client_access',

            'project_create',
            'project_edit',
            'project_show',
            'project_delete',
            'project_access',

            'payment_create',
            'payment_edit',
            'payment_show',
            'payment_delete',
            'payment_access',

            'report_access',
            'transaction_access',
        ];

        foreach ($executivePermissions as $permission)   {
            $executive->givePermissionTo($permission);
        }

        // Executive role
        $executive = Role::create(['name' => 'Executive']);
        $executivePermissions = [
            'enquiry_create',
            'enquiry_edit',
            'enquiry_show',
            'enquiry_access',

            'followup_create',
            'followup_edit',
            'followup_show',
            'followup_access',

            'client_create',
            'client_edit',
            'client_show',
            'client_access',

            'project_create',
            'project_edit',
            'project_show',
            'project_access',

            'payment_create',
            'payment_edit',
            'payment_show',
            'payment_access',
        ];

        foreach ($executivePermissions as $permission)   {
            $executive->givePermissionTo($permission);
        }

        // Telecaller role
        $executive = Role::create(['name' => 'Telecaller']);
        $executivePermissions = [
            'enquiry_show',
            'enquiry_access',

            'followup_create',
            'followup_edit',
            'followup_show',
            'followup_access',
        ];

        foreach ($executivePermissions as $permission)   {
            $executive->givePermissionTo($permission);
        }
    }
}
