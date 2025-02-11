<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ProfesorController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $usuario = Auth::user(); // Obtener el usuario autenticado
    return view('dashboard', compact('usuario'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('profesores', ProfesorController::class)->middleware('auth');
    Route::resource('cursos', CursoController::class);
});

// Cursos
Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
Route::get('/cursos/{id}', [CursoController::class, 'show'])->name('cursos.show');

// Profesores
Route::get('/profesores', [ProfesorController::class, 'index'])->name('profesores.index');
Route::get('/profesores/{id}', [ProfesorController::class, 'show'])->name('profesores.show');
Route::post('/profesores/asignar', [ProfesorController::class, 'asignar'])->name('profesores.asignar');

require __DIR__ . '/auth.php';
