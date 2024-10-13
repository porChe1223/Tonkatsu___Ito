<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Aさん',
            'email' => 'a@example.com',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'Bさん',
            'email' => 'b@example.com',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'Cさん',
            'email' => 'c@example.com',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'Dさん',
            'email' => 'd@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
