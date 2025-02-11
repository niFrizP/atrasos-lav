@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Curso {{ $curso->codigo }}</h1>

    <p><strong>Profesor Jefe:</strong> {{ optional($curso->profesorActual->usuario)->nomape ?? 'No asignado' }}</p>

    <h2 class="text-xl font-semibold mt-4">Estudiantes</h2>
    <ul class="list-disc pl-6">
        @foreach ($curso->estudiantes as $estudiante)
            <li>
                {{ $estudiante->nomape }} (RUT: {{ $estudiante->rut }})
            </li>
        @endforeach
    </ul>
</div>
@endsection
