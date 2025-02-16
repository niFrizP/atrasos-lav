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

    public function getRutFormattedAttribute()
    {
        $rut = (string) $this->rut;
        $rut = preg_replace('/[^0-9kK]/', '', $rut); // Elimina cualquier caracter no numérico
        $rut = strtoupper($rut); // Convierte a mayúsculas

        if (strlen($rut) === 9) {
            // Formato 99.999.999-9
            return preg_replace('/^(\d{2})(\d{3})(\d{3})(\d{1})$/', '$1.$2.$3-$4', $rut);
        } else {
            // Formato 9.999.999-9
            return preg_replace('/^(\d{1})(\d{3})(\d{3})(\d{1})$/', '$1.$2.$3-$4', $rut);
        }
    }
    
}
