<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        return $this->hasMany(Atraso::class);
    }



    public function getRutFormattedAttribute()
    {
        // Convertir a string y dejar solo dígitos o K
        $rut = preg_replace('/[^0-9kK]/', '', (string) $this->rut);
        $rut = strtoupper($rut);

        // Manejar 8 dígitos + dígito verificador (9 caracteres)
        if (strlen($rut) === 9) {
            // Ejemplo: 12.345.678-9
            return preg_replace('/^(\d{2})(\d{3})(\d{3})([\dkK])$/', '$1.$2.$3-$4', $rut);
        }
        // Manejar 7 dígitos + dígito verificador (8 caracteres)
        elseif (strlen($rut) === 8) {
            // Ejemplo: 1.234.567-K
            return preg_replace('/^(\d{1})(\d{3})(\d{3})([\dkK])$/', '$1.$2.$3-$4', $rut);
        }

        // Fallback si no coincide con 8 o 9 caracteres
        return $rut;
    }

    public function generateQR()
    {
        $data = json_encode([
            'Nombre' => $this->nomape,
            'RUT' => $this->rut,
            'Curso' => $this->curso->codigo . ' - ' . $this->curso->grado->nombre,
            'Correo' => $this->correo,
            'Telefono' => $this->telefono,
        ]);

        $qrCode = QrCode::format('png')->size(200)->generate($data);
        $this->qr = $qrCode; // Guardamos el QR en la BD
        $this->save();
    }
}
