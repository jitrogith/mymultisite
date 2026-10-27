<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $username = config('admin.username');
        $password = config('admin.password');

        if (! is_string($username) || $username === '' || ! is_string($password) || strlen($password) < 8) {
            throw new RuntimeException('Set ADMIN_USERNAME and an ADMIN_PASSWORD of at least 8 characters in .env before seeding the admin account.');
        }

        User::query()->updateOrCreate(
            ['username' => $username],
            [
                'name' => 'Administrator',
                'email' => $username.'@admin.local',
                'password' => $password,
                'is_admin' => true,
            ],
        );
    }
}
