<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Estudiantes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Listado de Estudiantes') }}</h1>

                    <!-- Buscador -->
                    <form method="GET" action="{{ route('estudiantes.index') }}" class="mb-4">
                        <input type="text" name="search" placeholder="Buscar por nombre, curso o RUT"
                            class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                        <button type="submit"
                            class="mt-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                            Buscar
                        </button>
                    </form>

                    <!-- Tabla de estudiantes -->
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300 dark:border-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Nombre') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('RUT') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Curso') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Acciones') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estudiantes as $estudiante)
                                    <tr class="border border-gray-300 dark:border-gray-700">
                                        <td class="p-2">{{ $estudiante->nomape }}</td>
                                        <td class="p-2">{{ number_format((int) $estudiante->rut, 0, '.', '-') }}</td>
                                        <td class="p-2">
                                            {{ $estudiante->curso ? $estudiante->curso->codigo . ' - ' . $estudiante->curso->grado->nombre : __('Sin curso') }}
                                        </td>
                                        <td class="p-2 flex space-x-2">
                                            <a href="{{ route('estudiantes.show', $estudiante) }}"
                                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                                                {{ __('Ver') }}
                                            </a>
                                            <a href="{{ route('estudiantes.edit', $estudiante) }}"
                                                class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md">
                                                {{ __('Editar') }}
                                            </a>
                                            <form action="{{ route('estudiantes.disable', $estudiante) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                                                    {{ __('Deshabilitar') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @if ($estudiantes->isEmpty())
                                <p class="text-red-500">No hay estudiantes registrados.</p>
                            @endif
                        </table>
                    </div>

                    <!-- Botón para agregar estudiante -->
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('estudiantes.create') }}"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                            {{ __('Agregar Estudiante') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
