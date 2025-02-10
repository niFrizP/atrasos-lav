<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estados';
    protected $fillable = ['id', 'nombre'];

    // Relación: Un estado puede tener muchos estudiantes.
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id');
    }
}