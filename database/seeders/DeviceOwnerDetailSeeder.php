<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeviceOwnerDetail;

class DeviceOwnerDetailSeeder extends Seeder
{
    public function run()
    {
        DeviceOwnerDetail::create([
            'device_id' => 1,  // Use actual device ID
            'billing_name' => 'WebOrigo Magyarország Zrt.',
            'address_country' => '348',
            'address_zip' => '1027',
            'address_city' => 'Budapest',
            'address_street' => 'Bem József utca 9. fszt.',
            'vat_number' => '28767116-2-41',
        ]);
    }
}
