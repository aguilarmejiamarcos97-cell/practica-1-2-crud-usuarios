<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Administrador', 'password' => 'Admin12345', 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'empleado@example.com'],
            ['name' => 'Empleado Prueba', 'password' => 'Empleado12345', 'role' => 'empleado']
        );

        // 12 usuarios de ejemplo para que se vea la paginación
        for ($i = 1; $i <= 12; $i++) {
            User::updateOrCreate(
                ['email' => 'usuario'.$i.'@example.com'],
                ['name' => 'Usuario '.$i, 'password' => 'Usuario12345', 'role' => 'empleado']
            );
        }
    }
}
