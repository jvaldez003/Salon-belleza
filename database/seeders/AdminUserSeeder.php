<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $usuarios = [
            [
                'email' => 'admin@ejemplo.com',
                'name' => 'Administrador',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ],
            [
                'email' => 'editor@ejemplo.com',
                'name' => 'Editor',
                'role' => 'editor',
                'password' => Hash::make('editor123'),
            ],
            [
                'email' => 'cliente@ejemplo.com',
                'name' => 'Cliente Demo',
                'role' => 'usuario',
                'telefono' => '3001234567',
                'password' => Hash::make('cliente123'),
            ],
        ];

        foreach ($usuarios as $datos) {
            User::updateOrCreate(
                ['email' => $datos['email']],
                array_merge($datos, ['email_verified_at' => now(), 'activo' => true])
            );
        }
    }
}
