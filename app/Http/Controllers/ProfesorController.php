<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\ProfesoresCurso;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class ProfesorController extends Controller
{
    /**
     * Lista todos los profesores.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $profesores = Usuario::where('rol_id', 4)  // Solo profesores
            ->where('activo', 1)  // Profesores activos
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('nomape', 'like', '%' . $search . '%')
                        ->orWhere('rut', 'like', '%' . $search . '%');
                });
            })
            ->with('cursoActual.grado')  // Relación curso y grado
            ->orderBy('nomape', 'asc')
            ->paginate(5);
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

        DB::transaction(function () use ($request) {
            // Desactivar profesor anterior
            ProfesoresCurso::where('curso_id', $request->curso_id)->update(['activo' => false]);

            // Asignar nuevo profesor jefe
            ProfesoresCurso::create([
                'usuario_id' => $request->usuario_id,
                'curso_id' => $request->curso_id,
                'anio' => date('Y'),
                'activo' => true,
            ]);

            // Actualizar profesor jefe en la tabla cursos
            Curso::where('id', $request->curso_id)->update(['profesor_jefe_id' => $request->usuario_id]);
        });

        return redirect()->route('cursos.index')->with('success', 'Profesor jefe actualizado correctamente.');
    }

    /**
     * Muestra el formulario para crear un nuevo profesor.
     */
    public function create()
    {
        $cursos = Curso::whereNull('profesor_jefe_id')->get();
        return view('profesores.create', compact('cursos'));
    }

    /**
     * Guarda un nuevo profesor y opcionalmente lo asigna como jefe de curso.
     */
    public function store(Request $request)
    {
        $rules = [
            'nomape'   => 'required|string|max:350',
            'correo'   => 'required|email|max:255|unique:usuarios,correo',
            'telefono' => 'nullable|string|max:9',
            'password' => 'required|string|min:8',
            'curso_id' => 'nullable|exists:cursos,id',
            'extranjero' => 'nullable|boolean',
        ];

        // Validación condicional para 'rut'
        if (!$request->filled('extranjero') || !$request->extranjero) {
            $rules['rut'] = 'required|string|max:15|unique:usuarios,rut';
        } else {
            $rules['rut'] = 'nullable|string|max:15|unique:usuarios,rut';
        }

        $data = $request->validate($rules);

        // Manejo del campo extranjero y rut_extranjero
        if ($request->filled('extranjero') && $request->extranjero) {
            // Si es extranjero, generamos un rut_extranjero
            $lastRutExtranjero = Usuario::whereNotNull('rut_extranjero')->max('rut_extranjero') ?? 0;
            $data['rut_extranjero'] = $lastRutExtranjero + 1;
            $data['rut'] = null;  // Dejar el rut nulo si es extranjero
        }

        // Cifrar contraseña y asignar rol de profesor
        $data['password'] = Hash::make($data['password']);
        $data['rol_id'] = 4;  // Rol Profesor
        $data['extranjero'] = $data['extranjero'] ?? false;  // Asegurarnos que se almacene un booleano

        DB::transaction(function () use ($data, $request) {
            $profesor = Usuario::create($data);  // Aquí debe almacenar extranjero y rut_extranjero

            if ($request->curso_id) {
                // Asignación de profesor jefe a un curso
                Curso::where('id', $request->curso_id)
                    ->update(['profesor_jefe_id' => $profesor->id]);

                ProfesoresCurso::create([
                    'usuario_id' => $profesor->id,
                    'curso_id' => $request->curso_id,
                    'anio' => date('Y'),
                    'activo' => true,
                ]);
            }
        });

        return redirect()->route('profesores.index')
            ->with('success', 'Profesor creado y curso asignado correctamente.');
    }

    /**
     * Muestra el formulario de edición de un profesor.
     */
    public function edit($id)
    {
        $profesor = Usuario::with('cursoActual')->findOrFail($id);
        $cursos = Curso::all();
        return view('profesores.edit', compact('profesor', 'cursos'));
    }

    /**
     * Actualiza los datos de un profesor.
     */
    public function update(Request $request, $id)
    {
        $profesor = Usuario::findOrFail($id);

        $rules = [
            'nomape'   => 'required|string|max:350',
            'correo'   => 'required|email|max:255|unique:usuarios,correo,' . $profesor->id,
            'telefono' => 'nullable|string|max:9',
            'curso_id' => 'nullable|exists:cursos,id',
            'extranjero' => 'nullable|boolean',
        ];

        if (!$request->filled('extranjero') || !$request->extranjero) {
            $rules['rut'] = 'required|string|max:15|unique:usuarios,rut,' . $profesor->id;
        } else {
            $rules['rut'] = 'nullable|string|max:15|unique:usuarios,rut,' . $profesor->id;
        }

        $data = $request->validate($rules);

        // Manejo del campo extranjero y rut_extranjero
        if ($request->filled('extranjero') && $request->extranjero) {
            $lastRutExtranjero = Usuario::whereNotNull('rut_extranjero')->max('rut_extranjero') ?? 0;
            $data['rut_extranjero'] = $lastRutExtranjero + 1;
            $data['rut'] = null;
        } else {
            $data['rut_extranjero'] = null;
        }

        DB::transaction(function () use ($data, $profesor, $request) {
            $profesor->update($data);  // Asegurarnos que los cambios de extranjero y rut_extranjero se guarden

            Curso::where('profesor_jefe_id', $profesor->id)
                ->update(['profesor_jefe_id' => null]);

            if ($request->curso_id) {
                // Asignar como jefe de un nuevo curso
                $curso = Curso::findOrFail($request->curso_id);
                $curso->profesor_jefe_id = $profesor->id;
                $curso->save();

                ProfesoresCurso::create([
                    'curso_id'   => $curso->id,
                    'usuario_id' => $profesor->id,
                    'anio'       => date('Y'),
                    'activo'     => true,
                ]);
            }
        });

        return redirect()->route('profesores.index')
            ->with('success', 'Profesor actualizado correctamente.');
    }
    /**
     * Desactiva un profesor.
     */
    public function destroy($id)
    {
        $profesor = Usuario::findOrFail($id);
        $profesor->update(['activo' => false]);

        return redirect()->route('profesores.index')
            ->with('success', 'Profesor desactivado correctamente.');
    }
}
