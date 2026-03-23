<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Check if super admin already exists
        $existingSuperAdmin = User::where('role', User::ROLE_SUPER_ADMIN)->first();
        
        if (!$existingSuperAdmin) {
            User::create([
                'username' => 'superadmin',
                'email' => 'superadmin@mswdo.gov.ph',
                'password' => Hash::make('Admin123!'), // Change this password
                'full_name' => 'System Super Administrator',
                'role' => User::ROLE_SUPER_ADMIN,
                'status' => 'active',
                'email_verified_at' => now(), // Auto verified
            ]);

            $this->command->info('Super Admin account created successfully!');
        } else {
            $this->command->info('Super Admin already exists.');
        }
    }
}