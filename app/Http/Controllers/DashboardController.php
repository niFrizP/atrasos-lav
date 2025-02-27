<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Mostrar el dashboard del usuario
    public function index()
    {
        $usuario = Auth::user();

        // Total de atrasos de la tabla t_atr_estudiante
        $cantidadAtrasos = DB::table('t_atr_estudiante')->count();

        // Total general de atrasos por curso de la tabla t_atr_curso
        $cantidadAtrasosCurso = DB::table('t_atr_curso')->count();

        // Total de atrasos hechos por el usuario autenticado
        $cantidadAtrasosUsuario = DB::table('atrasos')
            ->where('inspector_id', $usuario->id)
            ->count();

        // Total general de atrasos en la tabla atrasos
        $cantidadAtrasosTotal = DB::table('atrasos')->count();

        // Detalle de atrasos desde la tabla atrasos
        $detalleAtrasos = DB::table('atrasos')->get();

        // Detalle de estudiantes desde la tabla estudiantes
        $detalleEstudiantes = DB::table('estudiantes')->get();

        // Blacklist: estudiantes con 3 o más atrasos registrados.
        // Se agregan los campos 'rut' y el 'curso' (usando el campo 'codigo' de la tabla cursos)
        $blacklist = DB::table('estudiantes')
            ->join('atrasos', 'estudiantes.id', '=', 'atrasos.estudiante_id')
            ->leftJoin('cursos', 'estudiantes.curso_id', '=', 'cursos.id')
            ->select(
                'estudiantes.id',
                'estudiantes.nomape',
                'estudiantes.rut',
                'cursos.codigo as curso',
                DB::raw('COUNT(atrasos.id) as atrasos_count')
            )
            ->groupBy('estudiantes.id', 'estudiantes.nomape', 'estudiantes.rut', 'cursos.codigo')
            ->having('atrasos_count', '>=', 3)
            ->get()
            ->take(10);

        return view('dashboard', compact(
            'usuario',
            'cantidadAtrasos',
            'cantidadAtrasosCurso',
            'cantidadAtrasosUsuario',
            'cantidadAtrasosTotal',
            'detalleAtrasos',
            'detalleEstudiantes',
            'blacklist'
        ));
    }

    // Mostrar el perfil del usuario
    public function profile()
    {
        $usuario = Auth::user();
        return view('profile', compact('usuario'));
    }
}
