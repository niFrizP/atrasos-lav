<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Resultados de búsqueda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (isset($estudiantes))
                        @if ($estudiantes->isEmpty())
                            <p class="text-red-500">No se encontraron resultados con esos criterios.</p>
                        @else
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th>{{ __('Nombre') }}</th>
                                        <th>{{ __('RUT') }}</th>
                                        <th>{{ __('Curso') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($estudiantes as $estudiante)
                                        <tr>
                                            <td>{{ $estudiante->nomape }}</td>
                                            <td>{{ $estudiante->rut }}</td>
                                            <td>{{ $estudiante->curso ? $estudiante->curso->nombre : 'Sin curso' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @elseif (isset($profesores))
                        @if ($profesores->isEmpty())
                            <p class="text-red-500">No se encontraron profesores con esos criterios.</p>
                        @else
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th>{{ __('Nombre') }}</th>
                                        <th>{{ __('Especialidad') }}</th>
                                        <th>{{ __('RUT') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($profesores as $profesor)
                                        <tr>
                                            <td>{{ $profesor->nombre }}</td>
                                            <td>{{ $profesor->especialidad }}</td>
                                            <td>{{ $profesor->rut }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @elseif (isset($atrasos))
                        @if ($atrasos->isEmpty())
                            <p class="text-red-500">No se encontraron atrasos con esos criterios.</p>
                        @else
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th>{{ __('Motivo') }}</th>
                                        <th>{{ __('Estudiante') }}</th>
                                        <th>{{ __('Fecha') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($atrasos as $atraso)
                                        <tr>
                                            <td>{{ $atraso->motivo }}</td>
                                            <td>{{ $atraso->estudiante->nomape }}</td>
                                            <td>{{ $atraso->fecha }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @elseif (isset($cursos))
                        @if ($cursos->isEmpty())
                            <p class="text-red-500">No se encontraron cursos con esos criterios.</p>
                        @else
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th>{{ __('Nombre') }}</th>
                                        <th>{{ __('Código') }}</th>
                                        <th>{{ __('Grado') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cursos as $curso)
                                        <tr>
                                            <td>{{ $curso->nombre }}</td>
                                            <td>{{ $curso->codigo }}</td>
                                            <td>{{ $curso->grado ? $curso->grado->nombre : 'Sin grado' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @else
                        <p class="text-red-500">No se encontraron resultados con esos criterios.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
