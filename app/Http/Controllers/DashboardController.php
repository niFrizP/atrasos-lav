<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Atraso;

class DashboardController extends Controller
{
    // Mostrar el dashboard del usuario
    public function index()
    {
        $usuario = Auth::user();

        // Obtener métricas, por ejemplo, la cantidad de atrasos
        $cantidadAtrasos = Atraso::where('estudiante_id', $usuario->id)->count();

        return view('dashboard', compact('usuario', 'cantidadAtrasos'));
    }

    // Mostrar el perfil del usuario
    public function profile()
    {
        $usuario = Auth::user();
        return view('profile', compact('usuario'));
    }

    
}
