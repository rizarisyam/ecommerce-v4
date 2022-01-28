<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        $admin = User::create([
            'name' => 'Admim',
            'email' => 'admin@localhost.com',
            'password' => Hash::make('password')
        ]);

        $admin->assignRole('admin');

        $user = User::create([
            'name' => 'Riza Nurfat Risyam',
            'email' => 'risyam23riza@gmail.com',
            'password' => Hash::make('password')
        ]);

        $user->assignRole('user');


    }
}
