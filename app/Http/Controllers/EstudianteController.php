<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        // Obtener todos los cursos para el select
        $cursos = Curso::all();
        return view('estudiantes.create', [
            'cursos' => $cursos,
            'estudiante' => new Estudiante() // Esto evita el error en la vista
        ]);
    }

    /**
     * Guardar nuevo estudiante.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomape'     => 'required|string|max:255',
            'rut'        => 'nullable|string|max:15|unique:estudiantes',
            'correo'     => 'nullable|email|max:255|unique:estudiantes',
            'telefono'   => 'nullable|string|max:9',
            'curso_id'   => 'nullable|exists:cursos,id',
            'extranjero' => 'nullable|boolean',
            'fec_naci'   => 'nullable|date',
        ]);

        // Determinar el valor del checkbox basado en el valor enviado
        $extranjero = $request->input('extranjero') == 1 ? 1 : 0;

        if ($extranjero) {
            $ultimoRutEx = Estudiante::whereNotNull('rut_extranjero')->max('rut_extranjero');
            $rut_extranjero = $ultimoRutEx ? $ultimoRutEx + 1 : 1; // Inicia en 1 si no hay registros
            $rut = null;
        } else {
            $rut = $request->rut;
            $rut_extranjero = null;
        }

        // Crear el estudiante con los datos procesados
        $estudiante = Estudiante::create([
            'nomape'         => $request->nomape,
            'rut'            => $rut,
            'rut_extranjero' => $rut_extranjero,
            'correo'         => $request->correo,
            'telefono'       => $request->telefono,
            'curso_id'       => $request->curso_id,
            'extranjero'     => $extranjero,
            'fec_naci'       => $request->fec_naci,
        ]);

        // Generar el QR en el registro recién creado
        $estudiante->generateQR();

        return redirect()->route('estudiantes.index')
            ->with('success', 'Estudiante agregado correctamente.');
    }


    /**
     * Mostrar perfil de un estudiante.
     */
    public function show(Estudiante $estudiante)
    {
        // Cargar relaciones necesarias
        $estudiante->load('curso', 'atrasos', 'curso.historial');

        // Filtrar el historial para encontrar el registro con curso_id = 82
        $motivoRetiro = optional($estudiante->curso)->historial
            ->where('curso_id', 82) // Filtra por curso_id específico
            ->first();

        // Pasar motivo de retiro a la vista
        return view('estudiantes.show', compact('estudiante', 'motivoRetiro'));
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
     * Actualizar estudiante y registrar cambios de curso en his_cursos si es necesario.
     */
    public function update(Request $request, Estudiante $estudiante)
    {
        $rules = [
            'nomape'          => 'required|string|max:255',
            'rut'             => 'nullable|string|max:15|unique:estudiantes,rut,' . $estudiante->id,
            'rut_extranjero'  => 'nullable|string|max:15|unique:estudiantes,rut_extranjero,' . $estudiante->id,
            'correo'          => 'nullable|email|max:255|unique:estudiantes,correo,' . $estudiante->id,
            'telefono'        => 'nullable|string|max:9',
            'curso_id'        => 'nullable|exists:cursos,id',
            'motivo_cambio'   => 'nullable|string|max:500',
            'extranjero'      => 'nullable|boolean',
            'fec_naci'        => 'nullable|date',
        ];

        $data = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Verificar si hay cambio de curso para actualizar el histórico
            if ($estudiante->curso_id != $request->curso_id) {
                DB::table('his_cursos')
                    ->where('estudiante_id', $estudiante->id)
                    ->whereNull('fecha_fin')
                    ->update(['fecha_fin' => now()]);

                DB::table('his_cursos')->insert([
                    'estudiante_id' => $estudiante->id,
                    'curso_id'      => $request->curso_id,
                    'fecha_inicio'  => now(),
                    'motivo_cambio' => $request->motivo_cambio,
                ]);
            }

            // Determinar el valor de extranjero usando el valor enviado (no solo su existencia)
            $extranjero = $request->input('extranjero') == 1 ? 1 : 0;

            if ($extranjero) {
                // Si es extranjero:
                // 1. Si el usuario envía un valor manual para 'rut_extranjero', se usa ese valor.
                // 2. De lo contrario, se verifica si ya tenía un rut_extranjero para mantenerlo o se genera uno autoincrementable.
                if ($request->filled('rut_extranjero')) {
                    $data['rut_extranjero'] = $request->rut_extranjero;
                } else {
                    if (!$estudiante->extranjero || is_null($estudiante->rut_extranjero)) {
                        $ultimoRutEx = Estudiante::whereNotNull('rut_extranjero')->max('rut_extranjero') ?? 0;
                        $data['rut_extranjero'] = $ultimoRutEx + 1;
                    } else {
                        $data['rut_extranjero'] = $estudiante->rut_extranjero;
                    }
                }
                // Al ser extranjero, se anula el RUT chileno.
                $data['rut'] = null;
            } else {
                // Si no es extranjero, se borra cualquier valor en rut_extranjero.
                $data['rut_extranjero'] = null;
            }

            // Se actualiza el valor del campo 'extranjero'
            $data['extranjero'] = $extranjero;

            // Preparamos los datos del nuevo registro
            $data = [
                'estudiante_id' => $estudiante->id,
                'curso_id' => $request->curso_id,
                'fecha_inicio' => now(),
                'motivo_cambio' => $request->motivo_cambio,
            ];

            // Actualizar el registro y regenerar el QR
            $estudiante->update($data);
            $estudiante->generateQR();

            // Verificamos si las columnas curso_id_antes y curso_id_despues existen
            if (Schema::hasColumn('his_cursos', 'curso_id_antes')) {
                $data['curso_id_antes'] = $estudiante->curso_id;
            }

            if (Schema::hasColumn('his_cursos', 'curso_id_despues')) {
                $data['curso_id_despues'] = $request->curso_id;
            }

            // Registrar el nuevo curso en `his_cursos`
            DB::table('his_cursos')->insert($data);

            // Actualizar curso en la tabla `estudiantes`
            $estudiante->update($request->except(['motivo_cambio']));
            $estudiante->generateQR();

            DB::commit();
            return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            // Loggear el error para revisión
            Log::error('Error al actualizar estudiante: ' . $e->getMessage());
            // Redirigir con mensaje de error
            return redirect()->route('estudiantes.index')->with('error', 'Error al actualizar estudiante.');
        }
    }

    /**
     * Generar código QR para un estudiante manualmente.
     */
    public function generarQR(Estudiante $estudiante)
    {
        $estudiante->generateQR(); // Llama a la función existente en el modelo
        return redirect()->back()->with('success', 'Código QR generado con éxito.');
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
