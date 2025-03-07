<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeasingPeriod;

class LeasingPeriodSeeder extends Seeder
{
    public function run()
    {
        LeasingPeriod::create([
            'deviceId' => 1,
            'leasingConstructionId' => 51342268,
            'leasingConstructionMaximumTraining' => 1000,
            'leasingConstructionMaximumDate' => '2021-06-01',
            'leasingActualPeriodStartDate' => '2021-06-01',
            'leasingNextCheck' => '2022-06-01',
        ]);

        LeasingPeriod::create([
            'deviceId' => 1,
            'leasingConstructionId' => 42115269,
            'leasingConstructionMaximumTraining' => null,
            'leasingConstructionMaximumDate' => '2021-10-01',
            'leasingActualPeriodStartDate' => '2022-06-01',
            'leasingNextCheck' => '2023-06-01',
        ]);

        LeasingPeriod::create([
            'deviceId' => 1,
            'leasingConstructionId' => 28524612,
            'leasingConstructionMaximumTraining' => 50,
            'leasingConstructionMaximumDate' => null,
            'leasingActualPeriodStartDate' => '2024-06-01',
            'leasingNextCheck' => '2025-06-01',
        ]);
    }
}
