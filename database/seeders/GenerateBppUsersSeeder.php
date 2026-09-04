<?php

namespace Database\Seeders;

use App\Models\Bpp;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GenerateBppUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Nullify all codes first to prevent duplicate key constraint violations
        Bpp::query()->update(['code' => null]);

        $bpps = Bpp::orderBy('name')->get();
        $index = 1;

        foreach ($bpps as $bpp) {
            $code = 'BPP-' . str_pad($index, 3, '0', STR_PAD_LEFT);
            $bpp->update(['code' => $code]);
            $index++;
        }

        $this->command->info("BPP Codes updated successfully!");
    }
}
