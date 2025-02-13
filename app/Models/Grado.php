<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    protected $table = 'grados';
    protected $fillable = ['nombre'];

    // Un grado tiene muchos cursos.
    public function cursos()
    {
        return $this->hasMany(Curso::class, 'grado_id');
    }

    // Un grado tiene muchos estudiantes.
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'grado_id');
    }

    // Un grado tiene muchos profesores.
    public function profesores()
    {
        //return $this->hasMany(Profesor::class, 'grado_id');
    }

    // Un grado tiene muchos jefes de curso.
    public function jefesCurso()
    {
        //return $this->hasMany(JefeCurso::class, 'grado_id');
    }

    // Un grado tiene muchos inspectores.
    public function inspectores()
    {
        //return $this->hasMany(Inspector::class, 'grado_id');
    }
    

}
