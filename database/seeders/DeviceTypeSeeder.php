<?php

namespace Database\Seeders;

use App\Models\DeviceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deviceTypes = [
            'unset' => 'Registration not performed',
            'free' => 'Registered, activation code not assigned',
            'leasing' => 'Registered and activation code assigned',
            'restricted' => 'The device has been suspended'
        ];

        foreach ($deviceTypes as $name => $description) {
            DeviceType::create([
                'name' => $name,
                'description' => $description,
            ]);
        }
    }
}
