<?php

namespace Database\Seeders;

use App\Domains\Categories\Models\Category;
use App\Domains\Departments\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
                'code' => 'PUPR',
                'description' => 'Menangani infrastruktur jalan, jembatan, dan bangunan publik',
                'contact_email' => 'pupr@baritokualakab.go.id',
                'contact_phone' => '0511-1234567',
                'categories' => [
                    ['name' => 'Jalan Rusak', 'slug' => 'jalan-rusak', 'icon' => '🛣️', 'color' => '#ef4444'],
                    ['name' => 'Jembatan Rusak', 'slug' => 'jembatan-rusak', 'icon' => '🌉', 'color' => '#f97316'],
                    ['name' => 'Drainase Tersumbat', 'slug' => 'drainase-tersumbat', 'icon' => '🚰', 'color' => '#3b82f6'],
                ],
            ],
            [
                'name' => 'Badan Penanggulangan Bencana Daerah',
                'code' => 'BPBD',
                'description' => 'Menangani bencana alam dan keadaan darurat',
                'contact_email' => 'bpbd@baritokualakab.go.id',
                'contact_phone' => '0511-1234568',
                'categories' => [
                    ['name' => 'Banjir', 'slug' => 'banjir', 'icon' => '🌊', 'color' => '#0ea5e9'],
                    ['name' => 'Kebakaran', 'slug' => 'kebakaran', 'icon' => '🔥', 'color' => '#dc2626'],
                    ['name' => 'Pohon Tumbang', 'slug' => 'pohon-tumbang', 'icon' => '🌳', 'color' => '#16a34a'],
                ],
            ],
            [
                'name' => 'Dinas Lingkungan Hidup',
                'code' => 'DLH',
                'description' => 'Menangani kebersihan dan pengelolaan sampah',
                'contact_email' => 'dlh@baritokualakab.go.id',
                'contact_phone' => '0511-1234569',
                'categories' => [
                    ['name' => 'Sampah Menumpuk', 'slug' => 'sampah-menumpuk', 'icon' => '🗑️', 'color' => '#84cc16'],
                    ['name' => 'Pencemaran Air', 'slug' => 'pencemaran-air', 'icon' => '💧', 'color' => '#06b6d4'],
                    ['name' => 'Pencemaran Udara', 'slug' => 'pencemaran-udara', 'icon' => '💨', 'color' => '#64748b'],
                ],
            ],
            [
                'name' => 'Dinas Perhubungan',
                'code' => 'DISHUB',
                'description' => 'Menangani lalu lintas dan transportasi',
                'contact_email' => 'dishub@baritokualakab.go.id',
                'contact_phone' => '0511-1234570',
                'categories' => [
                    ['name' => 'Lampu Lalu Lintas Rusak', 'slug' => 'lampu-lalu-lintas-rusak', 'icon' => '🚦', 'color' => '#eab308'],
                    ['name' => 'Rambu Rusak', 'slug' => 'rambu-rusak', 'icon' => '🚸', 'color' => '#f59e0b'],
                    ['name' => 'Parkir Liar', 'slug' => 'parkir-liar', 'icon' => '🅿️', 'color' => '#8b5cf6'],
                ],
            ],
        ];

        foreach ($departments as $deptData) {
            $categories = $deptData['categories'];
            unset($deptData['categories']);

            $department = Department::create($deptData);

            foreach ($categories as $categoryData) {
                $department->categories()->create($categoryData);
            }
        }
    }
}
