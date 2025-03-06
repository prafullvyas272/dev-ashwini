<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 5; $i++) {
            Device::create([
                'deviceTypeId' => 1,  // by default unset
                // 'deviceAPIKey' => Str::random(32),   // Generate a random device API key
                'deviceId' => 'DEV-' . strtoupper(Str::random(8)),  // Generate a unique device ID
            ]);
        }
    }
}
