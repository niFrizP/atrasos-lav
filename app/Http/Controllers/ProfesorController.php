<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\ProfesoresCurso;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfesorController extends Controller
{
    /**
     * Lista todos los profesores.
     */
    public function index()
    {
        $profesores = Usuario::where('rol_id', 2)->with('cursos.curso')->get(); // 2 = Profesor
        return view('profesores.index', compact('profesores'));
    }

    /**
     * Muestra los cursos donde ha sido profesor jefe.
     */
    public function show($id)
    {
        $profesor = Usuario::with('cursos.curso')->findOrFail($id);
        return view('profesores.show', compact('profesor'));
    }

    /**
     * Asigna o cambia un profesor jefe en un curso.
     */
    public function asignar(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'usuario_id' => 'required|exists:usuarios,id',
        ]);

        // Desactivar profesor anterior
        ProfesoresCurso::where('curso_id', $request->curso_id)->update(['activo' => false]);

        // Asignar nuevo profesor jefe
        ProfesoresCurso::create([
            'usuario_id' => $request->usuario_id,
            'curso_id' => $request->curso_id,
            'anio' => date('Y'),
            'activo' => true,
        ]);

        return redirect()->route('cursos.index')->with('success', 'Profesor jefe actualizado correctamente.');
    }

    public function create()
    {
        return view('profesores.create');
    }

    /**
     * Muestra el formulario para crear un nuevo profesor.
     */

    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'nomape' => 'required|string|max:350',
            'rut' => 'required|string|max:15|unique:usuarios,rut',
            'correo' => 'required|email|max:255|unique:usuarios,correo',
            'telefono' => 'nullable|string|max:9',
            'password' => 'required|string|min:8',
        ]);

        // Crear el usuario (Profesor)
        Usuario::create([
            'nomape' => $request->nomape,
            'rut' => $request->rut,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'rol_id' => 3, // Profesor
        ]);


        return redirect()->route('profesores.index')->with('success', 'Profesor creado exitosamente.');
    }
}
