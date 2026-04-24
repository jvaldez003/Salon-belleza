<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Crear admin si no existe
        User::firstOrCreate(
            ['email' => 'admin@ejemplo.com'],
            [
                'name' => 'Administrador',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // Crear editor
        User::firstOrCreate(
            ['email' => 'editor@ejemplo.com'],
            [
                'name' => 'Editor',
                'role' => 'editor',
                'password' => Hash::make('editor123'),
            ]
        );
    }
}
