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
        $grados = Grado::with('cursos')->get();
        return view('cursos.index', compact('grados'));
    }

    /**
     * Muestra los cursos de un grado específico.
     */
    public function show($id)
    {
        $grado = Grado::with('cursos')->findOrFail($id);
        return view('cursos.show', compact('grado'));
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
