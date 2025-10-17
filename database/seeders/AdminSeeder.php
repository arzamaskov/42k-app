<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Users\Infrastructure\Database\Eloquent\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Администратор',
            'email' => 'admin@42k.app',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }
}
