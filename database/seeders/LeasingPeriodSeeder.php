<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeasingPeriod;

class LeasingPeriodSeeder extends Seeder
{
    public function run()
    {
        LeasingPeriod::create([
            'deviceId' => 1,  // Use actual device ID
            'leasingConstructionId' => 51342268,
            'leasingConstructionMaximumTraining' => 1000,
            'leasingConstructionMaximumDate' => '2021-06-01',
        ]);

        LeasingPeriod::create([
            'deviceId' => 1,  // Use actual device ID
            'leasingConstructionId' => 42115269,
            'leasingConstructionMaximumTraining' => null,
            'leasingConstructionMaximumDate' => '2021-10-01',
        ]);

        LeasingPeriod::create([
            'deviceId' => 1,  // Use actual device ID
            'leasingConstructionId' => 28524612,
            'leasingConstructionMaximumTraining' => 50,
            'leasingConstructionMaximumDate' => null,
        ]);
    }
}
