<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HisCurso extends Model
{
    use HasFactory;

    protected $table = 'his_cursos';

    // Relación con Estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    // Relación con Curso
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
