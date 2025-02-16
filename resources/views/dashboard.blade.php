<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bienvenido') }} {{ Auth::user()->nomape }}!
        </h2>
        <div class="flex justify-end">
            @if ($usuario->qr)
                <div class="QRCard">
                    <img src="data:image/png;base64,{{ base64_encode($usuario->qr) }}" alt="Código QR" width="100"
                        height="100">
                </div>
            @endif
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Buscador -->
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Buscador') }}
                </h2>
                <form method="GET" action="{{ route('profesores.index') }}" class="mb-4">
                    <input type="text" name="search" placeholder="Buscar por nombre, curso o RUT"
                        class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                    <button type="submit"
                        class="mt-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                        Buscar
                    </button>
                </form>
                
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl mb-4">Métricas</h1>
                    <p>Cantidad de atrasos: {{ $cantidadAtrasos }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
