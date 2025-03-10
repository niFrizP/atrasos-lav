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
                    <form method="GET" action="{{ route('estudiantes.index') }}"
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
                    <!-- Botón para agregar estudiante -->
                    <div class="mt-6 mb-2 flex justify-start">
                        <a href="{{ route('estudiantes.create') }}"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                            {{ __('Crear Estudiante') }}
                        </a>
                    </div>
                    <!-- Fin Botón para agregar estudiante -->

                    <!-- Tabla de estudiantes -->
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300 dark:border-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Nombre') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('RUT') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Curso') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">
                                        {{ __('Total Atrasos') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Acciones') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($estudiantes as $estudiante)
                                    <tr class="border border-gray-300 dark:border-gray-700">
                                        <td class="p-2">{{ $estudiante->nomape }}</td>
                                        <td class="p-2">{{ $estudiante->rut_formatted }}</td>
                                        <td class="p-2">
                                            {{ $estudiante->curso ? $estudiante->curso->codigo . ' - ' . $estudiante->curso->grado->nombre : __('Sin curso') }}
                                        </td>
                                        <td class="p-2 text-center">{{ $estudiante->atrasos_count }}</td>
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
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center p-4">
                                            {{ __('No se encontraron estudiantes que coincidan con la búsqueda.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- Mostrar enlaces de paginación -->
                        <div class="mt-4">
                            {{ $estudiantes->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
