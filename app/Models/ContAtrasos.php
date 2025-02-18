<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContAtrasos extends Model
{
    // Definir la tabla correspondiente
    protected $table = 't_atr_estudiante';

    // Los campos que pueden ser asignados masivamente
    protected $fillable = ['estudiante_id', 'total_atrasos', 'anio'];

    // Deshabilitar el manejo automático de created_at y updated_at
    public $timestamps = false;

    // Relación: Un registro de contAtrasos pertenece a un estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    // Relación: Un estudiante tiene muchos atrasos en un año específico
    public function atrasos()
    {
        return $this->hasMany(Atraso::class, 'cont_atraso_id');
    }

    /**
     * Incrementar el total de atrasos.
     *
     * @return void
     */
    public function incrementarAtraso()
    {
        // Incrementar el contador de atrasos para este registro
        $this->increment('total_atrasos');
    }

    /**
     * Reiniciar el contador de atrasos cuando cambia de año.
     *
     * @param int $estudiante_id
     * @param int $nuevo_anio
     * @return \App\Models\ContAtrasos
     */
    public static function reiniciarContador($estudiante_id, $nuevo_anio)
    {
        // Crear un nuevo registro con contador reiniciado
        return self::create([
            'estudiante_id' => $estudiante_id,
            'total_atrasos' => 0,
            'anio' => $nuevo_anio
        ]);
    }
}
