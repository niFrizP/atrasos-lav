<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atraso extends Model
{
    protected $table = 'atrasos';
    protected $fillable = [
        'estudiante_id',
        'fecha_atraso',
        'fecha_creacion',
        'inspector_id',
        'razon'
        // Puedes incluir 'evidencia' si la manejas.
    ];

    // Relación: Un atraso pertenece a un estudiante.
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    // Relación: Un atraso pertenece a un inspector.
    public function inspector()
    {
        return $this->belongsTo(Usuario::class, 'inspector_id');
    }
    
}
