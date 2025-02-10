<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfesoresCurso extends Model
{
    protected $table = 'profesores_cursos';
    protected $fillable = ['profesor_id', 'curso_id'];

    // Relación: Un profesor puede tener muchos cursos.
    public function profesor()
    {
        return $this->belongsTo(Usuario::class, 'profesor_id');
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
}
