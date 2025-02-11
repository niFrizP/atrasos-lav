<?php

namespace App\Http\Controllers;

use App\Models\Atraso;
use App\Models\Estudiante;
use Illuminate\Http\Request;

class AtrasoController extends Controller
{
    /**
     * Mostrar la lista de atrasos.
     */
    public function index(Request $request)
    {
        // Búsqueda por nombre, curso o RUT
        $query = Atraso::with('estudiante.curso')->orderBy('fecha_atraso', 'desc');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('estudiante', function ($q) use ($search) {
                $q->where('nomape', 'like', "%$search%")
                  ->orWhere('rut', 'like', "%$search%")
                  ->orWhereHas('curso', function ($c) use ($search) {
                      $c->where('codigo', 'like', "%$search%");
                  });
            });
        }

        $atrasos = $query->paginate(10);

        return view('atrasos.index', compact('atrasos'));
    }

    /**
     * Mostrar formulario para crear un nuevo atraso.
     */
    public function create()
    {
        $estudiantes = Estudiante::with('curso')->get();
        return view('atrasos.form', compact('estudiantes'));
    }

    /**
     * Guardar un nuevo atraso en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'fecha_atraso' => 'required|date',
            'razon' => 'required|string|max:500',
        ]);

        Atraso::create([
            'estudiante_id' => $request->estudiante_id,
            'fecha_atraso' => $request->fecha_atraso,
            'fecha_creacion' => now(),
            'inspector_id' => \Illuminate\Support\Facades\Auth::user()->id, // ID del usuario actual
            'razon' => $request->razon,
        ]);

        return redirect()->route('atrasos.index')->with('success', 'Atraso registrado correctamente.');
    }

    /**
     * Mostrar detalles de un atraso.
     */
    public function show(Atraso $atraso)
    {
        return view('atrasos.show', compact('atraso'));
    }

    /**
     * Mostrar formulario para editar un atraso.
     */
    public function edit(Atraso $atraso)
    {
        $estudiantes = Estudiante::with('curso')->get();
        return view('atrasos.form', compact('atraso', 'estudiantes'));
    }

    /**
     * Actualizar un atraso.
     */
    public function update(Request $request, Atraso $atraso)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'fecha_atraso' => 'required|date',
            'razon' => 'required|string|max:500',
        ]);

        $atraso->update([
            'estudiante_id' => $request->estudiante_id,
            'fecha_atraso' => $request->fecha_atraso,
            'razon' => $request->razon,
        ]);

        return redirect()->route('atrasos.index')->with('success', 'Atraso actualizado correctamente.');
    }

    /**
     * Eliminar un atraso.
     */
    public function destroy(Atraso $atraso)
    {
        $atraso->delete();
        return redirect()->route('atrasos.index')->with('success', 'Atraso eliminado correctamente.');
    }
}
