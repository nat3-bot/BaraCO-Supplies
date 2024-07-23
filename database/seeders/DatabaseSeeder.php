<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin BaraCo',
            'email' => 'nathan.lloyd.mendoza@adamson.edu.ph',
            'address' => 'Admin Address',
            'role' => 'admin',
            'phone' => '09215064228',
            'password' => Hash::make('4321dcba'),
        ]);
    }
}
