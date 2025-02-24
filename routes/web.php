<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ProfesorController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AtrasoController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BusquedaController;


// Redicción a inicio
Route::get('/', function () {
    return redirect()->route('login');
});

// Ruta de inicio
Route::get('/dashboard', function () {
    $usuario = Auth::user(); // Obtener el usuario autenticado
    return view('dashboard', compact('usuario'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/profesores', ProfesorController::class)->middleware('auth');
});


// Rutas de curso protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
    Route::get('/cursos/{grado}', [CursoController::class, 'show'])->name('cursos.show');
    Route::get('/curso/{curso}', [CursoController::class, 'showCurso'])->name('cursos.curso');
});
// Profesores
Route::get('/profesores', [ProfesorController::class, 'index'])->name('profesores.index');
Route::get('/profesores/create', [ProfesorController::class, 'create'])->name('profesores.create');
Route::get('/profesores/{id}/edit', [ProfesorController::class, 'edit'])->name('profesores.edit');
Route::put('/profesores/{id}', [ProfesorController::class, 'update'])->name('profesores.update');
Route::delete('/profesores/{id}', [ProfesorController::class, 'destroy'])->name('profesores.destroy');
Route::get('/profesores/{id}', [ProfesorController::class, 'show'])->name('profesores.show');
Route::post('/profesores/asignar', [ProfesorController::class, 'asignar'])->name('profesores.asignar');

// Atrasos
Route::resource('/atrasos', AtrasoController::class)->middleware('auth');

// Estudiantes
Route::get('estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index'); // Listado de estudiantes
Route::get('estudiantes/create', [EstudianteController::class, 'create'])->name('estudiantes.create'); // Formulario de creación
Route::post('estudiantes', [EstudianteController::class, 'store'])->name('estudiantes.store'); // Guardar estudiante
Route::get('estudiantes/{estudiante}', [EstudianteController::class, 'show'])->name('estudiantes.show'); // Mostrar detalle de un estudiante
Route::get('estudiantes/{estudiante}/edit', [EstudianteController::class, 'edit'])->name('estudiantes.edit'); // Formulario de edición
Route::match(['put', 'patch'], 'estudiantes/{estudiante}', [EstudianteController::class, 'update'])->name('estudiantes.update'); // Actualizar estudiante
Route::patch('estudiantes/{estudiante}/disable', [EstudianteController::class, 'disable'])->name('estudiantes.disable'); // Deshabilitar estudiante

// Busquedas
// Búsqueda global
Route::get('/buscar', [BusquedaController::class, 'buscar'])->name('buscar.general');

// Resultados generales
Route::get('/buscar/resultados', [BusquedaController::class, 'mostrarResultados'])->name('buscar.general.resultados');

// Búsqueda específica
Route::get('/buscar-estudiante', [BusquedaController::class, 'buscarEstudiante'])->name('buscar.estudiante');
Route::get('/buscar-profesor', [BusquedaController::class, 'buscarProfesor'])->name('buscar.profesor');
Route::get('/buscar-atraso', [BusquedaController::class, 'buscarAtraso'])->name('buscar.atraso');
Route::get('/buscar-curso', [BusquedaController::class, 'buscarCurso'])->name('buscar.curso');




// Rutas de autenticación
require __DIR__ . '/auth.php';
