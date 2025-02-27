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

                    <!-- Buscador -->
                    <form method="GET" action="{{ route('profesores.index') }}"
                        class="mb-4 flex items-center justify-center">
                        <div class="relative w-3/4">
                            <input type="text" name="search" placeholder="🔎 Buscar por Nombre o RUT "
                                class="w-full p-2 pl-10 border rounded-md dark:bg-gray-700 dark:text-white">
                        </div>
                        <button type="submit" value="Search"
                            class="ml-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                            Buscar
                        </button>
                    </form>
                    <!-- Fin Buscador -->

                    <!-- Botón para agregar un nuevo profesor -->
                    <div class="mt-6 mb-2 flex justify-start">
                        <a href="{{ route('profesores.create') }}"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                            {{ __('Agregar Profesor') }}
                        </a>
                    </div>


                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300 dark:border-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Nombre') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">
                                        {{ __('Curso Asignado') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                                        {{ __('Acciones') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($profesores as $profesor)
                                    <tr class="border border-gray-300 dark:border-gray-700">
                                        <td class="p-2">{{ $profesor->nomape }}</td>
                                        <td class="p-2">
                                            @if ($profesor->cursoActual)
                                                {{ $profesor->cursoActual->codigo }}
                                                ({{ $profesor->cursoActual->grado->nombre }})
                                            @else
                                                <span class="text-gray-500">{{ __('Sin curso asignado') }}</span>
                                            @endif
                                        </td>
                                        <td class="p-2 text-center">
                                            <!-- Botón Editar -->
                                            <a href="{{ route('profesores.edit', $profesor->id) }}"
                                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg mr-2">
                                                {{ __('Editar') }}
                                            </a>

                                            <!-- Botón Eliminar -->
                                            <form action="{{ route('profesores.destroy', $profesor->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('¿Estás seguro de que deseas eliminar este profesor?') }}');"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg">
                                                    {{ __('Eliminar') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center p-4">
                                            {{ __('No se encontraron profesores que coincidan con la búsqueda.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $profesores->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
