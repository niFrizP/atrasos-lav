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
        // Obtener profesores con rol_id 3 (Profesor) y que están activos
        $profesores = Usuario::where('rol_id', 3)
            ->where('activo', 1)
            ->with('cursos.grado')
            ->orderBy('created_at', 'desc')
            ->get();

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

    /**
     * Muestra el formulario para crear un nuevo profesor.
     */
    public function create()
    {
        // Obtener los cursos que no tienen profesor jefe asignado aún
        $cursos = Curso::whereNull('profesor_jefe_id')->get();

        // Pasamos los cursos a la vista
        return view('profesores.create', compact('cursos'));
    }

    /**
     * Guarda un nuevo profesor y opcionalmente lo asigna como jefe de curso.
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
            'curso_id' => 'nullable|exists:cursos,id', // Validar el curso seleccionado
        ]);

        // Crear el usuario (Profesor)
        $profesor = Usuario::create([
            'nomape' => $request->nomape,
            'rut' => $request->rut,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'rol_id' => 3, // Profesor
        ]);

        // Si se selecciona un curso, asignar al profesor como jefe de curso
        if ($request->curso_id) {
            $curso = Curso::find($request->curso_id);
            $curso->profesor_jefe_id = $profesor->id;
            $curso->save();
        }

        return redirect()->route('profesores.index')->with('success', 'Profesor creado y curso asignado correctamente.');
    }


    // Muestra el formulario de edición de un profesor
    public function edit($id)
    {
        $profesor = Usuario::findOrFail($id);
        $cursos = Curso::whereNull('profesor_jefe_id')->orWhere('profesor_jefe_id', $profesor->id)->get(); // Cursos disponibles o asignados al profesor
        return view('profesores.edit', compact('profesor', 'cursos'));
    }

    // Actualiza los datos de un profesor
    public function update(Request $request, $id)
    {
        $profesor = Usuario::findOrFail($id);

        // Validar los datos
        $request->validate([
            'nomape' => 'required|string|max:350',
            'rut' => 'required|string|max:15|unique:usuarios,rut,' . $profesor->id,
            'correo' => 'required|email|max:255|unique:usuarios,correo,' . $profesor->id,
            'telefono' => 'nullable|string|max:9',
            'curso_id' => 'nullable|exists:cursos,id',
        ]);

        // Actualizar los datos del profesor
        $profesor->update([
            'nomape' => $request->nomape,
            'rut' => $request->rut,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
        ]);

        // Asignar curso si se selecciona
        if ($request->curso_id) {
            $curso = Curso::find($request->curso_id);
            $curso->profesor_jefe_id = $profesor->id;
            $curso->save();
        }

        return redirect()->route('profesores.index')->with('success', 'Profesor actualizado correctamente.');
    }

    // Desactivar un profesor
    public function destroy($id)
    {
        $profesor = Usuario::findOrFail($id);

        // Desactivar el profesor sin eliminarlo
        $profesor->activo = false;
        $profesor->save();

        return redirect()->route('profesores.index')->with('success', 'Profesor desactivado correctamente.');
    }
}
