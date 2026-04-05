<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $email = config('smartfarming.auth.email', 'admin@smartsabin.test');
        $password = config('smartfarming.auth.password', 'password123');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Smart Sabin Admin',
                'password' => Hash::make($password),
            ]
        );
    }
}
