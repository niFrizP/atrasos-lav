<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfesoresCurso extends Model
{
    protected $table = 'profesores_cursos';
    protected $fillable = [
        'curso_id',
        'usuario_id',
        'anio',
        'activo',
    ];
    // Relación: Un profesor puede tener muchos cursos.
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Relación: Un curso puede tener muchos profesores.
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    // Relación: si el profesor es jefe de curso.
    public function isJefeCurso()
    {
        return $this->curso->jefe_curso_id === $this->profesor_id;
    }

    public function profesor()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
