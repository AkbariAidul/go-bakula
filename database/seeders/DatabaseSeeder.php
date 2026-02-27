<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            DepartmentCategorySeeder::class,
        ]);

        // Create Super Admin
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@baritokualakab.go.id',
        ]);
        $superAdmin->assignRole('super_admin');

        // Create Admin Dinas PUPR
        $adminPupr = User::factory()->create([
            'name' => 'Admin PUPR',
            'email' => 'pupr@baritokualakab.go.id',
        ]);
        $adminPupr->assignRole('admin_dinas');

        // Create Warga
        $warga = User::factory()->create([
            'name' => 'Warga Test',
            'email' => 'warga@example.com',
        ]);
        $warga->assignRole('warga');
    }
}
