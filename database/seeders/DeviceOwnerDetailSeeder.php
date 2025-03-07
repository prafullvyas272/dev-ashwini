<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeviceOwnerDetail;

class DeviceOwnerDetailSeeder extends Seeder
{
    public function run()
    {
        DeviceOwnerDetail::create([
            'deviceId' => 1,  // Use actual device ID
            'billineName' => 'WebOrigo Magyarország Zrt.',
            'addressCountry' => '348',
            'addressZip' => '1027',
            'addressCity' => 'Budapest',
            'addressStreet' => 'Bem József utca 9. fszt.',
            'vatNumber' => '28767116-2-41',
        ]);
    }
}
