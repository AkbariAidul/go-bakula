<?php

namespace Database\Seeders;

use App\Domains\Reports\Models\Report;
use App\Domains\Categories\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $warga = User::role('warga')->first();
        $categories = Category::all();

        if (!$warga || $categories->isEmpty()) {
            $this->command->warn('Tidak ada user warga atau kategori. Jalankan seeder lain terlebih dahulu.');
            return;
        }

        $sampleReports = [
            [
                'category_id' => $categories->where('name', 'Jalan Rusak')->first()?->id ?? $categories->first()->id,
                'title' => 'Jalan Berlubang di Jl. Ahmad Yani',
                'description' => 'Terdapat lubang besar di Jl. Ahmad Yani depan Pasar Marabahan. Lubang berdiameter sekitar 1 meter dan kedalaman 20cm. Sangat berbahaya untuk pengendara motor.',
                'latitude' => -2.5833,
                'longitude' => 114.6333,
                'address' => 'Jl. Ahmad Yani, Marabahan',
                'status' => 'pending',
            ],
            [
                'category_id' => $categories->where('name', 'Sampah Menumpuk')->first()?->id ?? $categories->first()->id,
                'title' => 'Sampah Tidak Diangkut Seminggu',
                'description' => 'Sampah di TPS Kelurahan Marabahan Kota sudah menumpuk selama seminggu tidak diangkut. Menimbulkan bau tidak sedap dan lalat.',
                'latitude' => -2.5850,
                'longitude' => 114.6350,
                'address' => 'TPS Kelurahan Marabahan Kota',
                'status' => 'verified',
                'verified_at' => now()->subDays(2),
            ],
            [
                'category_id' => $categories->where('name', 'Banjir')->first()?->id ?? $categories->first()->id,
                'title' => 'Banjir di Perumahan Griya Asri',
                'description' => 'Setiap hujan deras, air selalu menggenang setinggi 30cm di Perumahan Griya Asri. Drainase tersumbat dan perlu dibersihkan.',
                'latitude' => -2.5900,
                'longitude' => 114.6400,
                'address' => 'Perumahan Griya Asri, Marabahan',
                'status' => 'in_progress',
                'verified_at' => now()->subDays(3),
            ],
            [
                'category_id' => $categories->where('name', 'Lampu Jalan Mati')->first()?->id ?? $categories->first()->id,
                'title' => 'Lampu Jalan Mati di Jl. Veteran',
                'description' => 'Lampu jalan di Jl. Veteran sudah mati sejak 2 minggu lalu. Jalan menjadi gelap dan rawan kejahatan di malam hari.',
                'latitude' => -2.5820,
                'longitude' => 114.6320,
                'address' => 'Jl. Veteran, Marabahan',
                'status' => 'completed',
                'verified_at' => now()->subDays(10),
                'completed_at' => now()->subDays(1),
            ],
            [
                'category_id' => $categories->where('name', 'Jalan Rusak')->first()?->id ?? $categories->first()->id,
                'title' => 'Jembatan Retak di Desa Tabukan',
                'description' => 'Jembatan penghubung antar desa di Tabukan mengalami keretakan. Perlu segera diperbaiki karena dilalui banyak kendaraan berat.',
                'latitude' => -2.6000,
                'longitude' => 114.6500,
                'address' => 'Desa Tabukan',
                'status' => 'pending',
                'is_urgent' => true,
                'upvotes_count' => 52,
            ],
        ];

        foreach ($sampleReports as $reportData) {
            $reportData['user_id'] = $warga->id;
            $reportData['department_id'] = Category::find($reportData['category_id'])->department_id;
            
            // Create dummy photo path (in real scenario, this would be actual uploaded file)
            // For demo purposes, we'll use a placeholder path
            $reportData['photo_path'] = 'reports/sample-report.jpg';
            
            Report::create($reportData);
        }

        $this->command->info('Sample reports created successfully!');
    }
}
