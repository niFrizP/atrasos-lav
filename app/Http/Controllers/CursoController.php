<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Grado;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Muestra los grados disponibles con sus cursos.
     */
    public function index()
    {
        $grados = Grado::with('cursos.profesorActual.usuario')->get();
        return view('cursos.index', compact('grados'));
    }

    /**
     * Muestra un curso con sus estudiantes y profesor jefe actual.
     */
    public function show($id)
    {
        $curso = Curso::with(['estudiantes', 'profesorActual.usuario'])->findOrFail($id);
        return view('cursos.show', compact('curso'));
    }
}
