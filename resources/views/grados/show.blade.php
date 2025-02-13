@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalles del Atraso</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Estudiante: {{ $atraso->estudiante->nombre }}</h5>
            <p class="card-text"><strong>Curso:</strong> {{ $atraso->estudiante->curso->nombre }}</p>
            <p class="card-text"><strong>Fecha:</strong> {{ $atraso->fecha }}</p>
            <p class="card-text"><strong>Motivo:</strong> {{ $atraso->motivo }}</p>
            <a href="{{ route('atrasos.index') }}" class="btn btn-secondary">Volver al listado</a>
        </div>
    </div>
</div>
@endsection
