<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'estudiantes';
    protected $fillable = ['nomape', 'rut', 'telefono', 'correo', 'qr', 'curso_id', 'fotografia', 'estado_id'];
    protected $attributes = [
        'estado_id' => 1, // Asumiendo que 1 corresponde a "Activo" en la tabla estados.
    ];


    // Relación: Un estudiante pertenece a un curso.
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    //Relación: Un estudiante tiene muchos atrasos.
    public function atrasos()
    {
        return $this->hasMany(Atraso::class, 'estudiante_id');
    }
}
