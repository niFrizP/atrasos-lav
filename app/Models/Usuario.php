<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\HisUser;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected static function boot()
    {
        parent::boot();
        static::created(function ($usuario) {
            $usuario->generateQR();
        });
    }

    // Especifica los campos que se pueden asignar en masa.
    protected $fillable = ['nomape', 'rut', 'telefono', 'correo', 'password', 'activo', 'rol_id'];

    // Especifica el campo que se usará como nombre de usuario.
    public function getAuthIdentifierName()
    {
        return 'correo';
    }

    // Relación: Un usuario puede tener muchos cursos.
    public function profesoresCursos()
    {
        return $this->hasMany(ProfesoresCurso::class, 'usuario_id');
    }


    // Un usuario puede tener un curso activo.
    public function profesorCursoActivo()
    {
        return $this->hasOne(ProfesoresCurso::class, 'usuario_id')
            ->where('activo', 1);
    }

    // Un usuario puede tener un curso activo.
    public function cursoActivo()
    {
        return $this->hasOneThrough(
            Curso::class,            // Modelo final
            ProfesoresCurso::class,  // Modelo intermedio (pivot)
            'usuario_id',            // Foreign key en pivot (profesores_cursos)
            'id',                    // Local key en cursos
            'id',                    // Local key en usuarios
            'curso_id'               // Foreign key en pivot (profesores_cursos)
        )->where('profesores_cursos.activo', 1);
    }

    // Un usuario puede tener muchos cursos.
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'profesores_cursos', 'usuario_id', 'curso_id');
    }

    public function cursoActual()
    {
        return $this->hasOne(Curso::class, 'profesor_jefe_id', 'id');
    }

    // Oculta atributos sensibles.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relación: Un usuario pertenece a un rol.
    public function role()
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    // Relación: Un usuario puede tener muchos errores.
    public function errorLogs()
    {
        // return $this->hasMany(ErrorLog::class, 'usuario_id');
    }

    // Relación: Un usuario puede tener muchos cambios de historial.
    public function historyChanges()
    {
        // return $this->hasMany(HisUser::class, 'usuario_id');
    }

    // Puedes agregar otras relaciones, por ejemplo, si el usuario es inspector o profesor.
    public function isInspector()
    {
        return $this->role->nombre === 'Inspector';
    }

    public function isTeacher()
    {
        return $this->role->nombre === 'Profesor';
    }

    public function isAdmin()
    {
        return $this->role->nombre === 'Admin';
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
            'Correo' => $this->correo,
            'RUT' => $this->rut,
            'Telefono' => $this->telefono,
        ]);

        $qrCode = QrCode::format('png')->size(200)->generate($data);
        $this->qr = $qrCode; // Guardamos el QR en la BD
        $this->save();
    }
}
