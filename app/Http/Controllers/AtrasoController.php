<?php

namespace App\Http\Controllers;

use App\Models\Atraso;
use App\Models\Estudiante;
use App\Models\Curso;
use App\Models\ContAtrasos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Exception;

class AtrasoController extends Controller
{
    /**
     * Mostrar la lista de atrasos.
     */
    public function index(Request $request)
    {
        // Obtener el término de búsqueda desde el formulario
        $search = $request->input('search');

        // Subconsulta para contar atrasos por estudiante
        $subQuery = Atraso::select('estudiante_id', DB::raw('COUNT(*) as total_atrasos'))
            ->groupBy('estudiante_id');

        // Obtener atrasos de estudiantes con la relación de curso y cantidad de atrasos
        $atrasos = Atraso::with(['estudiante.curso'])
            ->leftJoinSub($subQuery, 'sub', function ($join) {
                $join->on('atrasos.estudiante_id', '=', 'sub.estudiante_id');
            })
            ->when($search, function ($query, $search) {
                return $query->whereHas('estudiante', function ($query) use ($search) {
                    $query->where('nomape', 'like', '%' . $search . '%')
                        ->orWhere('rut', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('estudiante.curso', function ($query) use ($search) {
                        $query->where('codigo', 'like', '%' . $search . '%');
                    });
            })
            ->orderBy('sub.total_atrasos', 'desc') // Ordenar por cantidad de atrasos
            ->paginate(5); // Paginación de los resultados

        // Pasar los resultados a la vista
        return view('atrasos.index', compact('atrasos'));
    }



    /**
     * Mostrar formulario para crear un nuevo atraso.
     */
    public function create()
    {
        $estudiantes = Estudiante::with('curso')->get(); // Obtener estudiantes con sus cursos
        return view('atrasos.form', compact('estudiantes'));
    }

    /**
     * Buscar estudiantes por nombre, curso o RUT.
     */
    public function buscar(Request $request)
    {
        // Obtener el término de búsqueda
        $search = $request->input('search');

        // Realizar la búsqueda en la base de datos
        $estudiantes = Estudiante::where('nomape', 'like', '%' . $search . '%')
            ->orWhere('rut', 'like', '%' . $search . '%')
            ->with('curso.grado')
            ->limit(10)
            ->get();

        // Retornar los resultados como JSON para Select2
        return response()->json($estudiantes->map(function ($estudiante) {
            return [
                'id' => $estudiante->id,
                'text' => $estudiante->nomape . ' - ' . $estudiante->curso->codigo . ' (' . $estudiante->curso->grado->nombre . ')'
            ];
        }));
    }

    /**
     * Guardar un nuevo atraso en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de la entrada
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'fecha_atraso' => 'required|date',
            'razon' => 'required|string|max:500',
            'evidencia' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Obtener el id del estudiante y el curso del estudiante
        $estudiante_id = $request->estudiante_id;
        $curso_id = Estudiante::find($estudiante_id)->curso_id;
        $anio_actual = Carbon::now()->year;

        // Verificar si se sube una evidencia
        $evidencia = $request->file('evidencia')
            ? $request->file('evidencia')->storeAs('public/evidencias', $request->file('evidencia')->getClientOriginalName())
            : null;

        // Crear el nuevo atraso en la base de datos
        Atraso::create([
            'estudiante_id' => $estudiante_id,
            'fecha_atraso' => $request->fecha_atraso,
            'fecha_creacion' => now(),
            'inspector_id' => Auth::user()->id,
            'razon' => $request->razon,
            'evidencia' => $evidencia,
        ]);

        // Actualizar el total de atrasos del curso
        DB::table('t_atr_curso')
            ->updateOrInsert(
                ['curso_id' => $curso_id],
                ['total_atrasos' => Atraso::whereHas('estudiante', function ($q) use ($curso_id) {
                    $q->where('curso_id', $curso_id);
                })->count()]
            );

        // Actualizar o crear el registro de atrasos por estudiante
        $contAtrasos = ContAtrasos::firstOrCreate(
            ['estudiante_id' => $estudiante_id, 'anio' => $anio_actual],
            ['total_atrasos' => 0]
        );

        // Incrementar el total de atrasos
        $contAtrasos->increment('total_atrasos');

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
        $estudiantes = Estudiante::with('curso')->get(); // Obtener estudiantes con sus cursos
        return view('atrasos.edit', compact('atraso', 'estudiantes'));
    }

    /**
     * Actualizar un atraso.
     */
    public function update(Request $request, Atraso $atraso)
    {
        // Validación de la entrada
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'fecha_atraso' => 'required|date',
            'razon' => 'required|string|max:500',
            'evidencia' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Verificar si se sube una nueva evidencia
        $evidencia = $request->file('evidencia')
            ? $request->file('evidencia')->store('evidencias', 'public')
            : $atraso->evidencia;

        // Actualizar el atraso
        $atraso->update([
            'estudiante_id' => $request->estudiante_id,
            'fecha_atraso' => $request->fecha_atraso,
            'razon' => $request->razon,
            'evidencia' => $evidencia,
        ]);

        return redirect()->route('atrasos.index')->with('success', 'Atraso actualizado correctamente.');
    }

    /**
     * Eliminar un atraso.
     */
    public function destroy(Atraso $atraso)
    {
        $estudiante_id = $atraso->estudiante_id;
        $curso_id = $atraso->estudiante->curso_id;
        $anio_atraso = Carbon::parse($atraso->fecha_atraso)->year;

        // Eliminar el atraso
        $atraso->delete();

        // Recalcular los atrasos para el estudiante
        $nuevo_total = Atraso::where('estudiante_id', $estudiante_id)
            ->whereYear('fecha_atraso', $anio_atraso)
            ->count();

        // Actualizar el total de atrasos del curso
        DB::table('t_atr_curso')
            ->updateOrInsert(
                ['curso_id' => $curso_id],
                ['total_atrasos' => Atraso::whereHas('estudiante', function ($q) use ($curso_id) {
                    $q->where('curso_id', $curso_id);
                })->count()]
            );

        // Actualizar el total de atrasos del estudiante
        DB::table('t_atr_estudiante')
            ->updateOrInsert(
                ['estudiante_id' => $estudiante_id, 'anio' => $anio_atraso],
                ['total_atrasos' => $nuevo_total]
            );

        return redirect()->route('atrasos.index')->with('success', 'Atraso eliminado correctamente.');
    }
}
