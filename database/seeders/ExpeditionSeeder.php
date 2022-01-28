<?php

namespace Database\Seeders;

use App\Models\Expedition;
use Illuminate\Database\Seeder;

class ExpeditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expedition = [
            'jne',
            'j&t express',
            'pos indonesia',
            'tiki',
            'sicepat',
            'ninja express'
        ];

        foreach($expedition as $item) {
            Expedition::create([
                'name' => $item
            ]);
        }
        //
    }
}
