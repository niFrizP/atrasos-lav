<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Grado;
use App\Models\Estudiante;
use App\Models\Atraso;

class Curso extends Model
{
    protected $table = 'cursos';
    protected $fillable = ['grado_id', 'codigo', 'profesor_jefe_id'];

    // Un curso pertenece a un grado.
    public function grado()
    {
        return $this->belongsTo(Grado::class, 'grado_id');
    }

    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'profesores_cursos',
            'curso_id',
            'usuario_id'
        );
    }

    // Un curso tiene muchos estudiantes.
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'curso_id');
    }

    // Un curso tiene muchos profesores.
    public function profesoresCursos()
    {
        return $this->hasMany(ProfesoresCurso::class, 'curso_id');
    }

    // En tu modelo Estudiante
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }


    // Un curso tiene un profesor jefe.
    public function profesorCursoActivo()
    {
        return $this->hasOne(ProfesoresCurso::class, 'curso_id')
            ->where('activo', 1);
    }

    public function profesorJefe()
    {
        return $this->belongsTo(Usuario::class, 'profesor_jefe_id');
    }


    // Un curso tiene muchos atrasos.
    public function atrasos()
    {
        return $this->hasManyThrough(Atraso::class, Estudiante::class, 'curso_id', 'estudiante_id');
    }
    
}
