<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Collection;

class EstudianteController extends Controller
{
    /**
     * Mostrar listado de estudiantes.
     */
    public function index(Request $request)
    {
        // Obtener el término de búsqueda desde el formulario
        $search = $request->input('search');

        // Obtener estudiantes con el conteo de atrasos, aplicando el filtro de búsqueda
        $estudiantes = Estudiante::with('curso') // Cargar la relación con el curso
            ->withCount('atrasos') // Cargar el conteo de atrasos
            ->whereNotNull('estado_id') // Solo estudiantes activos
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('nomape', 'like', '%' . $search . '%') // Buscar por nombre
                        ->orWhere('rut', 'like', '%' . $search . '%'); // Buscar por RUT
                });
            })
            ->orderBy('nomape', 'asc')
            // Paginar los resultados
            ->paginate(5);
        // Pasar los estudiantes a la vista
        return view('estudiantes.index', compact('estudiantes'));
    }

    /**
     * Buscar estudiantes por nombre o RUT.
     */
    public function buscar(Request $request)
    {
        // Obtener el término de búsqueda desde el parámetro 'q' que envía Select2
        $search = $request->input('q'); // 'q' es el término de búsqueda que envía Select2

        // Realizar la búsqueda en la base de datos
        $estudiantes = Estudiante::where('nomape', 'like', '%' . $search . '%')
            ->with('curso.grado') // Cargar las relaciones necesarias
            ->limit(10) // Limitar el número de resultados para optimizar
            ->get();

        // Retornar los resultados formateados para Select2
        return response()->json($estudiantes->map(function ($estudiante) {
            return [
                'id' => $estudiante->id,
                'text' => $estudiante->nomape . ' - ' . $estudiante->curso->codigo . ' (' . $estudiante->curso->grado->nombre . ')'
            ];
        }));
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

        // Crear el estudiante
        $estudiante = Estudiante::create($request->all());

        // Generar el QR y guardarlo en la base de datos
        $estudiante->generateQR();

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante creado correctamente.');
    }

    /**
     * Mostrar el perfil del estudiante.
     */
    public function show(Estudiante $estudiante)
    {
        // Cargar los atrasos del estudiante
        $estudiante->load('atrasos');  // Asegúrate de que esta relación exista

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
     * Actualizar estudiante y registrar cambios en `his_cursos` si cambia de curso.
     */
    public function update(Request $request, Estudiante $estudiante)
    {
        // Validar la entrada
        $request->validate([
            'nomape' => 'required|string|max:255',
            'rut' => 'required|string|max:15|unique:estudiantes,rut,' . $estudiante->id,
            'correo' => 'nullable|email|max:255|unique:estudiantes,correo,' . $estudiante->id,
            'telefono' => 'nullable|string|max:9',
            'curso_id' => 'nullable|exists:cursos,id',
            'motivo_cambio' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Verificar si el estudiante cambia de curso
            if ($estudiante->curso_id != $request->curso_id) {
                // Cerrar el curso anterior en `his_cursos`
                DB::table('his_cursos')
                    ->where('estudiante_id', $estudiante->id)
                    ->whereNull('fecha_fin')
                    ->update(['fecha_fin' => now()]);

                // Preparamos los datos del nuevo registro
                $data = [
                    'estudiante_id' => $estudiante->id,
                    'curso_id' => $request->curso_id,
                    'fecha_inicio' => now(),
                    'motivo_cambio' => $request->motivo_cambio,
                ];

                // Verificamos si las columnas curso_id_antes y curso_id_despues existen
                if (Schema::hasColumn('his_cursos', 'curso_id_antes')) {
                    $data['curso_id_antes'] = $estudiante->curso_id;
                }

                if (Schema::hasColumn('his_cursos', 'curso_id_despues')) {
                    $data['curso_id_despues'] = $request->curso_id;
                }

                // Registrar el nuevo curso en `his_cursos`
                DB::table('his_cursos')->insert($data);
            }

            // Actualizar curso en la tabla `estudiantes`
            $estudiante->update($request->except(['motivo_cambio']));
            $estudiante->generateQR();

            DB::commit();
            return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado correctamente.');
        } catch (Exception $e) {
            DB::rollback();

            // Registro de errores en el archivo de log de Laravel
            Log::error('Error al actualizar estudiante: ' . $e->getMessage());

            // Enviar un mensaje detallado a la vista
            return redirect()->route('estudiantes.index')->with('error', 'Error al actualizar estudiante. ' . $e->getMessage());
        }
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
