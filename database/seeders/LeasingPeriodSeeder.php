<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeasingPeriod;

class LeasingPeriodSeeder extends Seeder
{
    public function run()
    {
        LeasingPeriod::create([
            'device_id' => 1,  // Use actual device ID
            'leasing_construction_id' => 51342268,
            'leasing_construction_maximum_training' => 1000,
            'leasing_construction_maximum_date' => '2021-06-01',
        ]);

        LeasingPeriod::create([
            'device_id' => 1,  // Use actual device ID
            'leasing_construction_id' => 42115269,
            'leasing_construction_maximum_training' => null,
            'leasing_construction_maximum_date' => '2021-10-01',
        ]);

        LeasingPeriod::create([
            'device_id' => 1,  // Use actual device ID
            'leasing_construction_id' => 28524612,
            'leasing_construction_maximum_training' => 50,
            'leasing_construction_maximum_date' => null,
        ]);
    }
}
