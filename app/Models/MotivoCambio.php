<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivoCambio extends Model
{
    use HasFactory;

    protected $fillable = ['estudiante_id', 'razon'];

    /**
     * Relación con el estudiante.
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
}
