<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContAtrasos;
use App\Models\Estudiante;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContAtrasosController extends Controller
{
    // Mostrar los atrasos de un estudiante en un año específico
    public function index($estudiante_id, $anio = null)
    {
        $anio = $anio ?? Carbon::now()->year;

        try {
            $contAtrasos = ContAtrasos::where('estudiante_id', $estudiante_id)
                ->where('anio', $anio)
                ->firstOrFail(); // Usa firstOrFail para lanzar una excepción si no se encuentra el registro
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Contador de atrasos no encontrado para este estudiante en el año especificado.'], 404);
        }

        return response()->json($contAtrasos);
    }

    // Incrementar el total de atrasos
    public function incrementarAtraso($estudiante_id)
    {
        $anio_actual = Carbon::now()->year;

        try {
            $contAtrasos = ContAtrasos::firstOrCreate(
                ['estudiante_id' => $estudiante_id, 'anio' => $anio_actual],
                ['total_atrasos' => 0]
            );

            $contAtrasos->incrementarAtraso();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al incrementar el atraso: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Atraso registrado', 'total_atrasos' => $contAtrasos->total_atrasos]);
    }

    // Reiniciar el contador al cambiar de año
    public function reiniciarContador($estudiante_id)
    {
        $nuevo_anio = Carbon::now()->year;

        try {
            // Verifica si ya existe un contador para el nuevo año
            $contAtrasos = ContAtrasos::where('estudiante_id', $estudiante_id)
                ->where('anio', $nuevo_anio)
                ->first();

            if ($contAtrasos) {
                $contAtrasos->update(['total_atrasos' => 0]); // Si ya existe, reinicia el contador
            } else {
                // Si no existe, crea uno nuevo
                $contAtrasos = ContAtrasos::create([
                    'estudiante_id' => $estudiante_id,
                    'total_atrasos' => 0,
                    'anio' => $nuevo_anio
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al reiniciar el contador: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Contador reiniciado', 'anio' => $nuevo_anio]);
    }
}
