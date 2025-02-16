<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Grados y Cursos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Grados Disponibles') }}</h1>

                    <!-- Buscador -->
                    <form method="GET" action="{{ route('cursos.index') }}" class="mb-4">
                        <input type="text" name="search" placeholder="Buscar por nombre, curso o RUT"
                            class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                        <button type="submit"
                            class="mt-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                            Buscar
                        </button>
                    </form>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($grados as $grado)
                            <a href="{{ route('cursos.show', $grado->id) }}"
                                class="p-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg block text-center">
                                {{ $grado->nombre }}
                            </a>
                        @endforeach
                    </div>

                    @if ($grados->isEmpty())
                        <p class="text-red-500 mt-4">No hay grados registrados.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
