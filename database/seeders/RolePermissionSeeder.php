<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view reports',
            'create reports',
            'update reports',
            'delete reports',
            'verify reports',
            'complete reports',
            'manage departments',
            'manage categories',
            'view analytics',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = \Spatie\Permission\Models\Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        $adminDinas = \Spatie\Permission\Models\Role::create(['name' => 'admin_dinas']);
        $adminDinas->givePermissionTo([
            'view reports',
            'update reports',
            'verify reports',
            'complete reports',
            'view analytics',
        ]);

        $warga = \Spatie\Permission\Models\Role::create(['name' => 'warga']);
        $warga->givePermissionTo([
            'view reports',
            'create reports',
        ]);
    }
}
