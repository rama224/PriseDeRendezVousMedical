<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Patient Test',
            'email' => 'patient@test.com',
            'password' => Hash::make('password'),
        ]);
    }
}
