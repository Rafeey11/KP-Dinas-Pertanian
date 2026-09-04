<?php

namespace Database\Seeders;

use App\Models\Bpp;
use App\Models\Penyuluh;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ImportKoordinatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = base_path('temp_excel_reader/data.json');
        
        if (!file_exists($jsonPath)) {
            $this->command->error("File data.json not found!");
            return;
        }

        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);

        $count = 0;
        foreach ($data as $row) {
            // Validate row has name and BPP/Kecamatan
            if (!isset($row['__EMPTY']) || !isset($row['__EMPTY_3'])) {
                continue;
            }

            $name = trim($row['__EMPTY']);
            $nip = isset($row['__EMPTY_1']) ? trim($row['__EMPTY_1']) : null;
            $phone = isset($row['__EMPTY_2']) ? trim($row['__EMPTY_2']) : null;
            $bppName = trim($row['__EMPTY_3']);

            if (empty($name) || empty($bppName)) {
                continue;
            }

            // Exclude header or footer texts that might have slipped through
            if ($name == 'Garut,   Januari 2026' || str_contains(strtolower($name), 'ketua tim') || str_contains($name, 'KOORDINATOR')) {
                continue;
            }
            
            // Format phone to valid length if needed, it's string so it's fine.

            // Find BPP
            $bpp = Bpp::where('name', 'like', "%{$bppName}%")
                      ->orWhere('district', 'like', "%{$bppName}%")
                      ->first();

            if (!$bpp) {
                $this->command->warn("BPP not found for district: {$bppName}. Creating placeholder.");
                $bpp = Bpp::create([
                    'name' => 'BPP ' . $bppName,
                    'district' => $bppName,
                    'slug' => Str::slug('BPP ' . $bppName),
                ]);
            }

            // If NIP is empty, use name to check existence
            $matchAttributes = $nip ? ['nip' => $nip] : ['name' => $name, 'bpp_id' => $bpp->id];
            
            $penyuluh = Penyuluh::updateOrCreate(
                $matchAttributes,
                [
                    'bpp_id' => $bpp->id,
                    'name' => $name,
                    'slug' => Str::slug($name . '-' . rand(100, 999)),
                    'phone' => $phone,
                    'position' => 'Koordinator Penyuluh',
                    'employment_status' => 'PNS',
                    'is_public' => true,
                    'status' => 'active'
                ]
            );

            $count++;
            $this->command->info("Imported: {$name} - {$bpp->name}");
        }

        $this->command->info("Finished importing {$count} Koordinator Penyuluh.");
    }
}
