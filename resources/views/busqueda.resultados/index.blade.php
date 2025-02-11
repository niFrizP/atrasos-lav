<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Resultados de búsqueda de Estudiantes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($estudiantes->isEmpty())
                        <p class="text-red-500">No se encontraron estudiantes con esos criterios.</p>
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
