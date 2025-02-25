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
    public function index(Request $request)
    {
        // Obtener los datos de búsqueda
        $nombre = $request->input('nombre');

        // Crear la query base
        $query = Usuario::where('rol_id', 4)  // Filtrar solo a profesores
            ->where('activo', 1)  // Profesores activos
            ->with('cursoActual.grado');  // Cargar curso y grado actual

        // Si el usuario ingresa un nombre, agregar el filtro
        if (!empty($nombre)) {
            $query->where('nomape', 'like', '%' . $nombre . '%')
                ->orWhere('rut', 'like', '%' . $nombre . '%');
        }

        // Ejecutar la query
        $profesores = $query->orderBy('created_at', 'desc')->get();

        // Retornar la vista con los resultados
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
            'extranjero' => 'nullable|boolean', // Validar si es extranjero
        ]);

        // Si el profesor es extranjero, asignar un rut extranjero
        if ($request->has('extranjero') && $request->extranjero) {
            // Asignar un valor autoincrementable a rut_extranjero
            $lastRutExtranjero = Usuario::whereNotNull('rut_extranjero')->max('rut_extranjero');
            $data['rut_extranjero'] = $lastRutExtranjero + 1; // Asignamos el siguiente número
            $data['rut'] = null; // Dejamos el RUT en null
        }

        // Crear el usuario (Profesor)
        $profesor = Usuario::create([
            'nomape' => $request->nomape,
            'rut' => $request->rut,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'rol_id' => 4, // Profesor
            'extranjero' => $request->extranjero ?? false,
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
        // Obtener el profesor que queremos editar
        $profesor = Usuario::with('cursoActual')->findOrFail($id);
        // Obtener todos los cursos
        $cursos = Curso::all();
        return view('profesores.edit', compact('profesor', 'cursos'));
    }


    // Actualiza los datos de un profesor
    public function update(Request $request, $id)
    {
        // Obtener el profesor
        $profesor = Usuario::findOrFail($id);

        // Validar los datos
        $request->validate([
            'nomape' => 'required|string|max:350',
            'rut' => 'nullable|string|max:15|unique:usuarios,rut,' . $profesor->id,
            'correo' => 'required|email|max:255|unique:usuarios,correo,' . $profesor->id,
            'telefono' => 'nullable|string|max:9',
            'curso_id' => 'nullable|exists:cursos,id',
            'extranjero' => 'nullable|boolean',

        ]);

        // Si el profesor es extranjero, asignar un rut extranjero
        if ($request->has('extranjero') && $request->extranjero) {
            // Asignar un valor autoincrementable a rut_extranjero
            $lastRutExtranjero = Usuario::whereNotNull('rut_extranjero')->max('rut_extranjero');
            $data['rut_extranjero'] = $lastRutExtranjero + 1; // Asignamos el siguiente número
            $data['rut'] = null; // Dejamos el RUT en null
        }

        // Actualizar los datos del profesor
        $profesor->update([
            'nomape' => $request->nomape,
            'rut' => $request->rut,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'extranjero' => $request->extranjero ?? false,
        ]);

        // Limpiar el profesor_jefe_id de su curso anterior si tenía asignado
        if ($profesor->cursoActivo()) {
            Curso::where('profesor_jefe_id', $profesor->id)->update(['profesor_jefe_id' => null]);
        }

        // Asignar el nuevo curso, si se seleccionó
        if ($request->curso_id) {
            // Obtener el curso seleccionado
            $curso = Curso::findOrFail($request->curso_id);

            // Asignar el profesor jefe al curso
            $curso->profesor_jefe_id = $profesor->id;
            $curso->save();

            // Crear el registro en la tabla profesores_cursos
            ProfesoresCurso::create([
                'curso_id' => $curso->id,
                'usuario_id' => $profesor->id,
                'anio' => date('Y'),  // Usamos el año actual
                'activo' => 1,  // Indicar que este registro está activo
            ]);
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
