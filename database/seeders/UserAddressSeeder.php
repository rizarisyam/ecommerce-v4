<?php

namespace Database\Seeders;

use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserAddress::create([
            'address' => "Jl. Horizon Broadway Blok M1/3, The Icon, BSD City, Sampora, Kec. Cisauk, Kabupaten Tangerang, Banten 15345",
            'user_id' => 2
        ]);
    }
}
