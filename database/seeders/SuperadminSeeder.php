<?php

namespace Database\Seeders;

use App\Models\Superadmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    public function run(): void
    {
        Superadmin::updateOrCreate(
            ['email' => 'admin@hris-pertamina.com'],
            [
                'username' => 'superadmin',
                'email'    => 'admin@hris-pertamina.com',
                'no_telp'  => '08110000001',
                'password' => Hash::make('password'),
                'status'   => 'active',
            ]
        );

        $this->command->info('✅ Superadmin seeded: admin@hris-pertamina.com / password');
    }
}
