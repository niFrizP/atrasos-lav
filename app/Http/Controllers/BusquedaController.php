<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Atraso;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    /**
     * Buscar estudiantes por nombre, curso o RUT.
     */
    public function buscarEstudiante(Request $request)
    {
        $query = Usuario::where('rol_id', 2); // rol_id 2 es el rol de estudiante, ajusta si es diferente

        if ($request->filled('nombre')) {
            $query->where('nomape', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('rut')) {
            $query->where('rut', 'like', '%' . $request->rut . '%');
        }

        if ($request->filled('curso')) {
            $query->whereHas('curso', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->curso . '%');
            });
        }

        $estudiantes = $query->get();

        return view('busqueda.resultados', compact('estudiantes'));
    }

    /**
     * Buscar atrasos filtrados por criterios (fecha, motivo, estudiante).
     */
    public function buscarAtraso(Request $request)
    {
        $query = Atraso::query();

        if ($request->filled('estudiante_id')) {
            $query->where('estudiante_id', $request->estudiante_id);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->filled('motivo')) {
            $query->where('motivo', 'like', '%' . $request->motivo . '%');
        }

        $atrasos = $query->get();

        return view('busqueda.atrasos', compact('atrasos'));
    }
}
