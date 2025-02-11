<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Curso;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    /**
     * Mostrar listado de estudiantes.
     */
    public function index()
    {
        $estudiantes = Estudiante::with('curso')->orderBy('created_at', 'desc')->get();
        return view('estudiantes.index', compact('estudiantes'));
    }


    /**
     * Mostrar formulario de creación de estudiante.
     */
    public function create()
    {
        $cursos = Curso::all();
        return view('estudiantes.create', compact('cursos'));
    }

    /**
     * Guardar nuevo estudiante.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomape' => 'required|string|max:255',
            'rut' => 'required|string|max:15|unique:estudiantes',
            'correo' => 'nullable|email|max:255|unique:estudiantes',
            'telefono' => 'nullable|string|max:9',
            'curso_id' => 'nullable|exists:cursos,id',
        ]);

        Estudiante::create($request->all());

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante creado correctamente.');
    }

    /**
     * Mostrar el perfil del estudiante.
     */
    public function show(Estudiante $estudiante)
    {
        return view('estudiantes.show', compact('estudiante'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Estudiante $estudiante)
    {
        $cursos = Curso::all();
        return view('estudiantes.edit', compact('estudiante', 'cursos'));
    }

    /**
     * Actualizar estudiante.
     */
    public function update(Request $request, Estudiante $estudiante)
    {
        $request->validate([
            'nomape' => 'required|string|max:255',
            'rut' => 'required|string|max:15|unique:estudiantes,rut,' . $estudiante->id,
            'correo' => 'nullable|email|max:255|unique:estudiantes,correo,' . $estudiante->id,
            'telefono' => 'nullable|string|max:9',
            'curso_id' => 'nullable|exists:cursos,id',
        ]);

        $estudiante->update($request->all());

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado correctamente.');
    }

    /**
     * Deshabilitar estudiante (cambiar estado a NULL).
     */
    public function disable(Estudiante $estudiante)
    {
        $estudiante->update(['estado_id' => null]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante deshabilitado.');
    }
}
