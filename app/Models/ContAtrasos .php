<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContAtrasos extends Model
{
    protected $table = 'cont_atrasos';
    protected $fillable = ['estudiante_id', 'total_atrasos'];

    // Relación: Un estudiante tiene muchos atrasos.
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    // Relación: Un estudiante tiene muchos atrasos.
    public function atrasos()
    {
        return $this->hasMany(Atraso::class, 'cont_atraso_id');
    }

    // Relación: Un estudiante tiene muchos atrasos.
    public function totalAtrasos()
    {
        return $this->hasMany(Atraso::class, 'cont_atraso_id');
    }
}