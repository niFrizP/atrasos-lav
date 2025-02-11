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

    // Un curso tiene muchos estudiantes.
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'curso_id');
    }

    // Un curso tiene muchos profesores.
    public function cursos()
    {
        return $this->hasMany(ProfesoresCurso::class, 'usuario_id');
    }
    
    // Un curso tiene un profesor jefe.
    public function cursoActual()
    {
        return $this->hasOne(ProfesoresCurso::class, 'usuario_id')->where('activo', true);
    }
    
    
    // Un curso tiene muchos atrasos.
    public function atrasos()
    {
        return $this->hasManyThrough(Atraso::class, Estudiante::class, 'curso_id', 'estudiante_id');
    }
    
}
