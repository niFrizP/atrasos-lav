<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\HisUser;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    // Especifica los campos que se pueden asignar en masa.
    protected $fillable = [
        'nomape',
        'rut',
        'telefono',
        'correo',
        'password',
        'rol_id'
    ];

    // Especifica el campo que se usará como nombre de usuario.
    public function getAuthIdentifierName()
    {
        return 'correo';
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
}
