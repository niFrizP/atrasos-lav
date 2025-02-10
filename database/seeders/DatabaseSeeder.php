<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Insertar roles antes de los usuarios
        DB::table('roles')->insert([
            ['id' => 1, 'nombre' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Inspector', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nombre' => 'Profesor', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Crear usuario predeterminado después de los roles
        Usuario::factory()->create([
            'nomape' => 'Administrador',
            'rut' => '11111111-1',
            'telefono' => '123456789',
            'correo' => 'admin@a.com',
            'password' => bcrypt('root'), // Cambia la contraseña si lo deseas
            'rol_id' => 1, // Asegurando que el rol de administrador existe
        ]);
    }
}
