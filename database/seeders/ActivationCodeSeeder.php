<?php

namespace Database\Seeders;

use App\Models\ActivationCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActivationCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            ActivationCode::create([
                'activationCode' => Str::random(20) . uniqid()
            ]);
        }
    }
}
