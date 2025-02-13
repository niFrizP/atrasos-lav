@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($atraso) ? 'Editar Atraso' : 'Registrar Atraso' }}</h1>

    <form method="POST" action="{{ isset($atraso) ? route('atrasos.update', $atraso->id) : route('atrasos.store') }}">
        @csrf
        @if(isset($atraso))
            @method('PUT')
        @endif

        <!-- Selección de Estudiante -->
        <div class="mb-3">
            <label for="estudiante_id" class="form-label">Estudiante</label>
            <select name="estudiante_id" class="form-control" required>
                <option value="">Seleccione un estudiante</option>
                @foreach ($estudiantes as $estudiante)
                    <option value="{{ $estudiante->id }}" {{ isset($atraso) && $atraso->estudiante_id == $estudiante->id ? 'selected' : '' }}>
                        {{ $estudiante->nombre }} ({{ $estudiante->curso->nombre }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Fecha -->
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" value="{{ isset($atraso) ? $atraso->fecha : old('fecha') }}" required>
        </div>

        <!-- Motivo -->
        <div class="mb-3">
            <label for="motivo" class="form-label">Motivo</label>
            <textarea name="motivo" class="form-control" required>{{ isset($atraso) ? $atraso->motivo : old('motivo') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($atraso) ? 'Actualizar' : 'Registrar' }}</button>
        <a href="{{ route('atrasos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
