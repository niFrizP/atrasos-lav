<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Atraso extends Model
{
    protected $table = 'atrasos';
    protected $fillable = [
        'estudiante_id',
        'fecha_atraso',
        'fecha_creacion',
        'inspector_id',
        'razon',
        'evidencia'
    ];

    // Relación inversa con Estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    // Relación: Total de atrasos de un estudiante.
    public function atrasos()
    {
        return $this->hasMany(Atraso::class, 'estudiante_id');
    }


    // Relación: Un atraso pertenece a un inspector.
    public function inspector()
    {
        return $this->belongsTo(Usuario::class, 'inspector_id');
    }

    // Relación: Un atraso pertenece a un curso.
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    // Relación: Un atraso pertenece a un grado.
    public function grado()
    {
        return $this->belongsTo(Grado::class, 'grado_id');
    }

    // obtener la url de la evidencia
    public function getEvidenciaUrlAttribute()
    {
        return $this->evidencia ? Storage::url($this->evidencia) : null;
    }

    protected $dates = ['fecha_atraso'];


    public function getFechaAtrasoAttribute($value)
    {
        return date('d/m/Y H:i', strtotime($value));
    }

    public function getFechaCreacionAttribute($value)
    {
        return date('d/m/Y H:i', strtotime($value));
    }

    public function razon()
    {
        return $this->razon;
    }
}
