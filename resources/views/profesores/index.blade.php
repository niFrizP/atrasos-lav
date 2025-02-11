<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profesores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Listado de Profesores') }}</h1>

                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300 dark:border-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Nombre') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">
                                        {{ __('Curso Asignado') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($profesores as $profesor)
                                    <tr class="border border-gray-300 dark:border-gray-700">
                                        <td class="p-2">{{ $profesor->nomape }}</td>
                                        <td class="p-2">
                                            @if ($profesor->curso)
                                                {{ $profesor->curso->codigo }} ({{ $profesor->curso->grado->nombre }})
                                            @else
                                                <span class="text-gray-500">{{ __('Sin curso asignado') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @if ($profesores->isEmpty())
                                <p class="text-red-500">No hay profesores registrados.</p>
                            @else
                                <pre>{{ print_r($profesores->toArray(), true) }}</pre>
                            @endif
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('profesores.create') }}"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                            {{ __('Agregar Profesor') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
