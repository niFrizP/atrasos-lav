<x-app-layout>
    <x-slot name="header">
        <h2 class="justify-end font-bold text-2xl text-gray-800 dark:text-gray-200">
            {{ __('Hola') }}, {{ Auth::user()->nomape }}! 👋
        </h2>
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                @if ($usuario->qr)
                    <div class="QRCard">
                        <img src="data:image/png;base64,{{ base64_encode($usuario->qr) }}" alt="Código QR" width="100"
                            height="100">
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">📊 Métricas</h1>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <!-- Tarjeta de cantidad de atrasos -->
                    <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md flex flex-col items-center">
                        <h2 class="text-lg font-semibold">Total de Atrasos hechos</h2>
                        <p class="text-4xl font-bold mt-2">{{ $cantidadAtrasos }}</p>
                    </div>

                    <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-md flex flex-col items-center">
                        <h2 class="text-lg font-semibold">ejemplo</h2>
                        <p class="text-4xl font-bold mt-2">10</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
