<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Atrasos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Listado de Atrasos') }}</h1>

                    <!-- Buscador -->
                    <form method="GET" action="{{ route('atrasos.index') }}"
                        class="mb-4 flex items-center justify-center">
                        <div class="relative w-3/4">
                            <input type="text" name="search" placeholder="🔎 Buscar por Nombre o Curso"
                                class="w-full p-2 pl-10 border rounded-md dark:bg-gray-700 dark:text-white">
                        </div>
                        <button type="submit" value="Search"
                            class="ml-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                            Buscar
                        </button>
                    </form>

                    <!-- Botón para agregar un nuevo atraso -->
                    <div class="mt-6 mb-2 flex justify-start">
                        <a href="{{ route('atrasos.create') }}"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                            {{ __('Registrar Atraso') }}
                        </a>
                    </div>

                    <!-- Tabla de atrasos -->
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300 dark:border-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Estudiante') }}
                                    </th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Curso') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Fecha') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Razón') }}</th>
                                    <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Acciones') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($atrasos as $atraso)
                                    <tr class="border border-gray-300 dark:border-gray-700">
                                        <td class="p-2">{{ $atraso->estudiante->nomape }}</td>
                                        <td class="p-2">{{ $atraso->estudiante->curso->codigo ?? 'Sin curso' }}</td>
                                        <td class="p-2">{{ $atraso->fecha_atraso }}</td>
                                        <td class="p-2">{{ $atraso->razon }}</td>
                                        <td class="p-2 flex space-x-2">
                                            <a href="{{ route('atrasos.show', $atraso->id) }}"
                                                class="px-2 py-1 bg-blue-500 text-white rounded">
                                                Ver
                                            </a>
                                            <a href="{{ route('atrasos.edit', $atraso->id) }}"
                                                class="px-2 py-1 bg-yellow-500 text-white rounded">
                                                Editar
                                            </a>
                                            <form action="{{ route('atrasos.destroy', $atraso->id) }}" method="POST"
                                                onsubmit="return confirm('¿Seguro que quieres eliminar este atraso?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center p-4">
                                            {{ __('No se encontraron atrasos que coincidan con la búsqueda.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- Mostrar enlaces de paginación -->
                        <div class="mt-4">
                            {{ $atrasos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
