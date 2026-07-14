<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'ayad@admin.com'], // change this if you want a different login email
            [
                'name'              => 'Ayad Mohammed',
                'password'          => Hash::make('Ayad1234'),
                'is_admin'          => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
