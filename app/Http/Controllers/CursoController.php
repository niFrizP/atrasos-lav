<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Grado;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Muestra la lista de grados disponibles.
     */
    public function index()
    {
        // Obtener grados con cursos que tienen al menos un estudiante
        $grados = Grado::whereHas('cursos.estudiantes') // Filtra los grados con cursos que tengan estudiantes
            ->with('cursos') // Cargar la relación de cursos
            ->get();

        return view('cursos.index', compact('grados'));
    }


    /**
     * Muestra los cursos de un grado específico.
     */
    public function show($id)
    {
        // Obtener el grado con los cursos que tienen estudiantes
        $grado = Grado::with(['cursos' => function ($query) {
            $query->whereHas('estudiantes'); // Solo los cursos que tienen estudiantes
        }])->findOrFail($id);

        // Obtener el curso con estudiantes y sus motivos de cambio
        $curso = Curso::with(['estudiantes.motivoCambio'])->findOrFail($id);

        // Pasar tanto el grado como el curso a la vista
        return view('cursos.show', compact('grado', 'curso'));
    }


    /**
     * Muestra los estudiantes de un curso específico.
     */
    public function showCurso($id)
    {
        $curso = Curso::with('estudiantes')->findOrFail($id);
        return view('cursos.curso', compact('curso'));
    }
}
