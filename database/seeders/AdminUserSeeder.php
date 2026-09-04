<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = config('admin.name');
        $email = config('admin.email');
        $password = config('admin.password');

        if (! $name || ! $email || ! $password) {
            $this->command?->warn(
                'ADMIN_NAME, ADMIN_EMAIL o ADMIN_PASSWORD no están configurados.'
            );

            return;
        }

        User::updateOrCreate(
            [
                'email' => $email,
            ],
            [
                'name' => $name,
                'password' => $password,
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}