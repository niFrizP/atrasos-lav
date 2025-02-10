<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    public function run()
    {
        DB::table('estados')->insert([
            ['id' => 1, 'nombre' => 'Activo', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Egresado', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nombre' => 'Retirado', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nombre' => 'Sin Curso', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
