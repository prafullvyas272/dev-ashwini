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
        $this->call(DeviceTypeSeeder::class);
        $this->call(DeviceSeeder::class);
        $this->call(DeviceOwnerDetailSeeder::class);
        $this->call(LeasingPeriodSeeder::class);
    }
}
