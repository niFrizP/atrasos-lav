<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'cursos';
    protected $fillable = ['grado_id', 'codigo', 'profesor_jefe_id'];

    // Un curso pertenece a un grado.
    public function grado()
    {
        return $this->belongsTo(Grado::class, 'grado_id');
    }

    // Un curso tiene muchos estudiantes.
    public function profesorJefe()
    {
        return $this->belongsTo(Usuario::class, 'profesor_jefe_id');
    }

    // Un curso tiene muchos estudiantes.
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'curso_id');
    }

    // Un curso tiene muchos profesores.
    public function profesores()
    {
        return $this->belongsToMany(Usuario::class, 'profesores_cursos', 'curso_id', 'profesor_id');
    }
    
    // Un curso tiene muchos atrasos.
    public function atrasos()
    {
        return $this->hasManyThrough(Atraso::class, Estudiante::class, 'curso_id', 'estudiante_id');
    }
    
}
