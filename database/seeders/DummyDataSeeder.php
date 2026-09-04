<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Bpp;
use App\Models\Penyuluh;
use App\Models\NewsCategory;
use App\Models\News;
use App\Models\Video;
use App\Models\ReportType;
use App\Models\ReportRequest;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin CWS
        $admin = User::create([
            'name' => 'Admin CWS',
            'username' => 'admin_cws',
            'email' => 'admin@cws.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 2. BPP
        $bppsData = [
            ['Garut Kota', 'Kecamatan Garut Kota', '-7.2278', '107.9087'],
            ['Tarogong Kidul', 'Kecamatan Tarogong Kidul', '-7.2155', '107.8860'],
            ['Tarogong Kaler', 'Kecamatan Tarogong Kaler', '-7.1712', '107.8809'],
            ['Cilawu', 'Kecamatan Cilawu', '-7.2917', '107.9304'],
            ['Leles', 'Kecamatan Leles', '-7.0673', '107.9174'],
            ['Kadungora', 'Kecamatan Kadungora', '-7.0457', '107.9042'],
            ['Cibiuk', 'Kecamatan Cibiuk', '-7.0396', '107.9730'],
            ['Limbangan', 'Kecamatan Balubur Limbangan', '-7.0345', '108.0163'],
        ];

        $bpps = [];
        foreach ($bppsData as $index => $bppInfo) {
            $bpps[] = Bpp::create([
                'code' => 'BPP-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'name' => 'BPP ' . $bppInfo[0],
                'slug' => Str::slug('BPP ' . $bppInfo[0]),
                'district' => $bppInfo[1],
                'address' => 'Jl. Raya ' . $bppInfo[0] . ' No. ' . rand(10, 99),
                'phone' => '0262-' . rand(100000, 999999),
                'email' => 'bpp.' . Str::slug($bppInfo[0]) . '@garut.go.id',
                'contact_person' => 'Koordinator ' . $bppInfo[0],
                'working_area' => $bppInfo[1] . ' dan sekitarnya',
                'latitude' => $bppInfo[2],
                'longitude' => $bppInfo[3],
                'status' => 'active',
            ]);
        }

        // 3. User BPP & Penyuluh
        foreach ($bpps as $bpp) {
            User::create([
                'bpp_id' => $bpp->id,
                'name' => 'Operator ' . $bpp->name,
                'username' => 'operator_' . str_replace('-', '_', $bpp->slug),
                'email' => 'operator.' . str_replace('-', '_', $bpp->slug) . '@cws.id',
                'password' => Hash::make('password'),
                'role' => 'bpp',
                'status' => 'active',
            ]);

            // Buat 3-5 penyuluh per BPP
            $numPenyuluh = rand(3, 5);
            for ($i = 1; $i <= $numPenyuluh; $i++) {
                $gender = rand(0, 1) ? 'Laki-laki' : 'Perempuan';
                $name = ($gender == 'Laki-laki' ? 'Bapak ' : 'Ibu ') . 'Penyuluh ' . $bpp->id . '-' . $i;
                Penyuluh::create([
                    'bpp_id' => $bpp->id,
                    'nip' => '198' . rand(0, 9) . rand(100000000000, 999999999999),
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'position' => 'Penyuluh Pertanian Lapangan',
                    'employment_status' => rand(0, 1) ? 'PNS' : 'THL-TBPP',
                    'phone' => '0812' . rand(10000000, 99999999),
                    'email' => Str::slug($name) . '@example.com',
                    'address' => $bpp->address,
                    'working_area' => 'Desa Binaan ' . $i,
                    'village_area' => 'Desa Binaan ' . $i,
                    'farmer_group' => 'Kelompok Tani Makmur ' . $i . ', Kelompok Tani Sejahtera ' . $i,
                    'is_public' => true,
                    'status' => 'active',
                ]);
            }
        }

        // 4. News Category
        $categories = [
            'Kegiatan Penyuluhan',
            'Informasi Pertanian',
            'Pelatihan dan Bimtek',
            'Pengumuman',
        ];
        foreach ($categories as $cat) {
            NewsCategory::create([
                'name' => $cat,
                'slug' => Str::slug($cat),
            ]);
        }
        $catIds = NewsCategory::pluck('id')->toArray();

        // 5. News
        $penyuluhs = Penyuluh::all();
        for ($i = 1; $i <= 10; $i++) {
            $bpp = collect($bpps)->random();
            $penyuluh = $penyuluhs->where('bpp_id', $bpp->id)->random();
            News::create([
                'bpp_id' => $bpp->id,
                'author_penyuluh_id' => $penyuluh->id,
                'category_id' => $catIds[array_rand($catIds)],
                'created_by' => User::where('bpp_id', $bpp->id)->first()->id ?? $admin->id,
                'approved_by' => $admin->id,
                'title' => 'Kegiatan Penyuluhan Bersama ' . $penyuluh->name . ' di ' . $bpp->district,
                'slug' => Str::slug('Kegiatan Penyuluhan Bersama ' . $penyuluh->name . ' di ' . $bpp->district . ' ' . $i),
                'excerpt' => 'Ini adalah ringkasan dari kegiatan penyuluhan yang dilaksanakan pada minggu ini.',
                'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p><p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>',
                'activity_location' => 'Balai Desa di ' . $bpp->district,
                'activity_date' => now()->subDays(rand(1, 30)),
                'status' => 'published',
                'published_at' => now()->subDays(rand(1, 20)),
            ]);
        }

        // 6. Videos
        $videoLinks = [
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'https://www.youtube.com/watch?v=L_jWHffIx5E',
        ];

        for ($i = 1; $i <= 4; $i++) {
            Video::create([
                'bpp_id' => collect($bpps)->random()->id,
                'created_by' => $admin->id,
                'title' => 'Video Edukasi Pertanian Seri ' . $i,
                'description' => 'Penjelasan mengenai teknik budidaya modern di Kabupaten Garut.',
                'source_type' => 'youtube',
                'video_url' => $videoLinks[array_rand($videoLinks)],
                'activity_date' => now()->subDays(rand(1, 60)),
                'status' => 'active',
                'show_on_homepage' => $i <= 2,
                'sort_order' => $i,
            ]);
        }

        // 7. Report Types
        $reportTypes = [
            ['name' => 'Laporan Bulanan', 'description' => 'Laporan kegiatan penyuluhan yang disampaikan setiap bulan.'],
            ['name' => 'Laporan Triwulan', 'description' => 'Laporan evaluasi capaian triwulanan.'],
            ['name' => 'RKTP', 'description' => 'Rencana Kerja Tahunan Penyuluh.'],
            ['name' => 'Programa Penyuluhan', 'description' => 'Programa penyuluhan pertanian desa/kecamatan.'],
        ];
        foreach ($reportTypes as $rt) {
            ReportType::create([
                'name' => $rt['name'],
                'slug' => Str::slug($rt['name']),
                'description' => $rt['description'],
            ]);
        }

        // 8. Report Requests (contoh permintaan laporan aktif)
        $rtIds = ReportType::pluck('id', 'name');
        
        $rr1 = ReportRequest::create([
            'report_type_id' => $rtIds['Laporan Bulanan'],
            'created_by' => $admin->id,
            'title' => 'Laporan Bulanan Penyuluh - Agustus 2026',
            'description' => 'Silakan kirimkan laporan kegiatan penyuluhan bulan Agustus 2026.',
            'period' => 'Agustus',
            'period_year' => 2026,
            'start_date' => '2026-08-01',
            'deadline' => '2026-08-31',
            'allowed_formats' => ['pdf', 'docx', 'doc'],
            'max_file_size' => 10240,
            'notes' => 'Pastikan laporan mencantumkan kegiatan harian dan daftar kelompok tani yang dikunjungi.',
            'status' => 'active',
        ]);

        $rr2 = ReportRequest::create([
            'report_type_id' => $rtIds['Laporan Bulanan'],
            'created_by' => $admin->id,
            'title' => 'Laporan Bulanan Penyuluh - Juli 2026',
            'description' => 'Silakan kirimkan laporan kegiatan penyuluhan bulan Juli 2026.',
            'period' => 'Juli',
            'period_year' => 2026,
            'start_date' => '2026-07-01',
            'deadline' => '2026-07-31',
            'allowed_formats' => ['pdf', 'docx'],
            'max_file_size' => 10240,
            'status' => 'closed',
        ]);

        $rr3 = ReportRequest::create([
            'report_type_id' => $rtIds['RKTP'],
            'created_by' => $admin->id,
            'title' => 'RKTP Penyuluh Tahun 2026',
            'description' => 'Kirimkan Rencana Kerja Tahunan Penyuluh untuk tahun 2026.',
            'period' => 'Tahunan',
            'period_year' => 2026,
            'start_date' => '2026-01-01',
            'deadline' => '2026-09-30',
            'allowed_formats' => ['pdf'],
            'max_file_size' => 20480,
            'status' => 'active',
        ]);
    }
}
