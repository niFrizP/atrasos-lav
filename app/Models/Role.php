<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['nombre'];

    // Relación: Un rol tiene muchos usuarios.
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'rol_id');
    }

    // Relación: Un rol puede tener muchos permisos.
    public function permissions()
    {
       // return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }
}
