<?php

namespace App\Http\Controllers;

use App\Models\Atraso;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use App\Models\ProfesoresCurso;
use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use App\Models\ContAtrasos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class AtrasoController extends Controller
{
    /**
     * Mostrar la lista de atrasos.
     */
    public function index(Request $request)
    {
        // Búsqueda por nombre, curso o RUT
        $query = Atraso::with('estudiante.curso')->orderBy('fecha_creacion', 'desc');

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

        $atrasos = $query->paginate(5);

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
     * Buscar estudiantes por nombre, curso o RUT.
     */
    public function buscar(Request $request)
    {
        // Obtener el término de búsqueda
        $search = $request->input('search');

        // Realizar la búsqueda en la base de datos
        $estudiantes = Estudiante::where('nomape', 'like', '%' . $search . '%')
            ->orWhere('fecha_atraso', 'like', '%' . $search . '%')
            ->with('curso.grado')
            ->limit(10) // Puedes limitar el número de resultados
            ->get();

        // Retornar los resultados como JSON
        return response()->json($estudiantes);
    }


    /**
     * Guardar un nuevo atraso en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'fecha_atraso' => 'required|date',
            'razon' => 'required|string|max:500',
            'evidencia' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Obtener el id del estudiante
        $estudiante_id = $request->estudiante_id;
        // Obtener el curso_id del estudiante
        $curso_id = Estudiante::find($estudiante_id)->curso_id;
        // Obtener el año actual
        $anio_actual = Carbon::now()->year;

        // Verificar si se sube una evidencia y almacenarla en 'public/evidencias'
        $evidencia = $request->file('evidencia')
            ? $request->file('evidencia')->storeAs('public/evidencias', $request->file('evidencia')->getClientOriginalName()) // Usamos storeAs para definir la carpeta y el nombre del archivo
            : null;

        // Obtener la URL para la evidencia
        $evidencia_url = $evidencia ? asset('storage/evidencias/' . basename($evidencia)) : null;

        // Crear el nuevo atraso en la tabla t_atr_estudiante
        Atraso::create([
            'estudiante_id' => $estudiante_id,
            'fecha_atraso' => $request->fecha_atraso,
            'fecha_creacion' => now(),
            'inspector_id' => Auth::user()->id,
            'razon' => $request->razon,
            'evidencia' => $evidencia,  // Usamos la variable $evidencia que contiene la ruta
        ]);

        // Actualizar el contador de atrasos del curso en la tabla t_atr_curso
        DB::table('t_atr_curso')
            ->updateOrInsert(
                ['curso_id' => $curso_id],
                ['total_atrasos' => Atraso::whereHas('estudiante', function ($q) use ($curso_id) {
                    $q->where('curso_id', $curso_id);
                })->count()]
            );

        // Actualizar o crear el registro en la tabla t_atr_estudiante
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
        // Asegúrate de que puedes obtener el listado de estudiantes y el atraso específico
        $estudiantes = Estudiante::with('curso')->get();
        return view('atrasos.edit', compact('atraso', 'estudiantes'));
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
            'evidencia' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Verificar si se sube una nueva evidencia
        $evidencia = $request->file('evidencia')
            ? $request->file('evidencia')->store('evidencias', 'public')
            : $atraso->evidencia;

        $atraso->update([
            'estudiante_id' => $request->estudiante_id,
            'fecha_atraso' => $request->fecha_atraso,
            'razon' => $request->razon,
            'evidencia' => $request->file('evidencia') ? $request->file('evidencia')->store('evidencias', 'public') : null,
        ]);

        return redirect()->route('atrasos.index')->with('success', 'Atraso actualizado correctamente.');
    }

    /**
     * Eliminar un atraso.
     */
    public function destroy(Atraso $atraso)
    {
        // Especificar el formato correcto de la fecha
        $anio_atraso = Carbon::createFromFormat('d/m/Y H:i', $atraso->fecha_atraso)->year;

        // Guardamos el estudiante, curso y el año del atraso a eliminar
        $estudiante_id = $atraso->estudiante_id;
        $curso_id = $atraso->estudiante->curso_id;

        // Eliminamos el atraso
        $atraso->delete();

        // Recalculamos el total de atrasos para ese estudiante en el año correspondiente
        $nuevo_total = Atraso::where('estudiante_id', $estudiante_id)
            ->whereYear('fecha_atraso', $anio_atraso)
            ->count();

        // 📌 **Actualizar el total de atrasos del curso**
        DB::table('t_atr_curso')
            ->updateOrInsert(
                ['curso_id' => $curso_id],
                ['total_atrasos' => Atraso::whereHas('estudiante', function ($q) use ($curso_id) {
                    $q->where('curso_id', $curso_id);
                })->count()]
            );

        // Actualizamos o creamos el registro en la tabla t_atr_estudiante
        DB::table('t_atr_estudiante')
            ->updateOrInsert(
                ['estudiante_id' => $estudiante_id, 'anio' => $anio_atraso],
                ['total_atrasos' => $nuevo_total]
            );

        $atraso->delete();
        return redirect()->route('atrasos.index')->with('success', 'Atraso eliminado correctamente.');
    }
}
