@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Cursos</h1>
    
    @foreach ($grados as $grado)
        <div class="mb-6">
            <h2 class="text-xl font-semibold">{{ $grado->nombre }}</h2>
            <ul class="list-disc pl-6">
                @foreach ($grado->cursos as $curso)
                    <li class="flex justify-between">
                        <a href="{{ route('cursos.show', $curso->id) }}" class="text-blue-500 hover:underline">
                            Curso {{ $curso->codigo }}
                        </a>
                        <span class="text-gray-700">
                            Prof. Jefe: {{ optional($curso->profesorActual->usuario)->nomape ?? 'No asignado' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
@endsection
