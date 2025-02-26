<?php

namespace App\Http\Controllers;

use App\Models\Usuario;      // Para estudiantes y profesores
use App\Models\Atraso;       // Para los atrasos
use App\Models\Curso;        // Para los cursos
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    /**
     * Búsqueda Global.
     */
    public function buscar(Request $request)
    {
        $query = $request->get('query');

        // Si el query contiene '@', se asume que es correo y se redirige a búsqueda de estudiantes.
        if (strpos($query, '@') !== false) {
            return redirect()->route('buscar.estudiante')->with('query', $query);
        }

        // Redirigir a la ruta de resultados generales.
        return redirect()->route('buscar.general.resultados', ['query' => $query]);
    }

    /**
     * Mostrar resultados generales.
     */
    public function mostrarResultados(Request $request)
    {
        $query = $request->get('query');

        // Buscar profesores (rol_id 4)
        $profesores = Usuario::where('rol_id', 4)
            ->where(function ($q) use ($query) {
                $q->where('nomape', 'like', '%' . $query . '%')
                    ->orWhere('rut', 'like', '%' . $query . '%');
            })
            ->get();

        // Buscar atrasos por "razon"
        $atrasos = Atraso::where('razon', 'like', '%' . $query . '%')
            ->orWhereHas('estudiante', function ($q) use ($query) {
                $q->where('nomape', 'like', '%' . $query . '%');
            })
            ->get();

        // Buscar cursos por código
        $cursos = Curso::where('codigo', 'like', '%' . $query . '%')
            ->get();

        // Devolver los resultados a la vista "busqueda.index"
        return view('busquedas.index', compact('estudiantes', 'profesores', 'atrasos', 'cursos', 'query'));
    }

    /**
     * Buscar estudiantes por nombre o RUT.
     */
    public function buscarEstudiante(Request $request)
    {
        $query = $request->get('query');

        $estudiantes = Usuario::where('rol_id', 2)
            ->where('nomape', 'like', '%' . $query . '%')
            ->orWhere('rut', 'like', '%' . $query . '%')
            ->get();

        // Otras variables definidas como colecciones vacías
        $profesores = collect();
        $atrasos = collect();
        $cursos = collect();

        return view('busquedas.index', compact('estudiantes', 'profesores', 'atrasos', 'cursos', 'query'));
    }

    /**
     * Buscar profesores por nombre o RUT.
     */
    public function buscarProfesor(Request $request)
    {
        $query = $request->get('query');

        $profesores = Usuario::where('rol_id', 4)
            ->where(function ($q) use ($query) {
                $q->where('nomape', 'like', '%' . $query . '%');
            })
            ->get();

        // Otras variables definidas como colecciones vacías
        $estudiantes = collect();
        $atrasos = collect();
        $cursos = collect();

        return view('busquedas.index', compact('estudiantes', 'profesores', 'atrasos', 'cursos', 'query'));
    }

    /**
     * Buscar atrasos filtrados por razón o por estudiante.
     */
    public function buscarAtraso(Request $request)
    {
        $query = $request->get('query');

        $atrasos = Atraso::where('razon', 'like', '%' . $query . '%')
            ->orWhereHas('estudiante', function ($q) use ($query) {
                $q->where('nomape', 'like', '%' . $query . '%');
            })
            ->get();

        // Otras variables definidas como colecciones vacías
        $estudiantes = collect();
        $profesores = collect();
        $cursos = collect();

        return view('busquedas.index', compact('estudiantes', 'profesores', 'atrasos', 'cursos', 'query'));
    }

    /**
     * Buscar cursos por nombre o código.
     */
    public function buscarCurso(Request $request)
    {
        $query = $request->get('query');

        $cursos = Curso::Where('codigo', 'like', '%' . $query . '%')
            ->get();

        // Otras variables definidas como colecciones vacías
        $estudiantes = collect();
        $profesores = collect();
        $atrasos = collect();

        return view('busquedas.index', compact('estudiantes', 'profesores', 'atrasos', 'cursos', 'query'));
    }
}
