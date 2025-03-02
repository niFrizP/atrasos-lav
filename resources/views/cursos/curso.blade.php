<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Estudiantes del curso ') . $curso->codigo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Estudiantes en ') . $curso->codigo }}</h1>
                    <h1 class="text-2xl font-bold mb-4 flex justify-center">
                        {{ __('Profesor Jefe: ') . ($curso->profesorJefe ? $curso->profesorJefe->nomape : __('No asignado')) }}
                    </h1>

                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300 dark:border-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Nombre') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('RUT') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Acciones') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($curso->estudiantes as $estudiante)
                                    <tr class="border border-gray-300 dark:border-gray-700">
                                        <td class="p-2">{{ $estudiante->nomape }}</td>
                                        <td class="p-2">{{ $estudiante->rut }}</td>
                                        <td class="p-2">
                                            <a href="{{ route('estudiantes.show', $estudiante->id) }}"
                                                class="px-2 py-1 bg-blue-500 hover:bg-blue-700 text-white rounded">
                                                Ver Perfil
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($curso->estudiantes->isEmpty())
                        <p class="text-red-500 mt-4">No hay estudiantes registrados en este curso.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
