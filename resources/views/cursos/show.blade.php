<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cursos de ') . $grado->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Cursos en ') . $grado->nombre }}</h1>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($grado->cursos as $curso)
                            <a href="{{ route('cursos.curso', $curso->id) }}"
                                class="p-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg block text-center">
                                <!-- Mostrar código del curso y el nombre del grado -->
                                {{ $curso->codigo }} -
                                {{ $curso->grado->nombre ?? 'Sin grado' }}
                            </a>
                        @endforeach
                    </div>

                    @if ($grado->cursos->isEmpty())
                        <p class="text-red-500 mt-4">No hay cursos registrados en este grado.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
