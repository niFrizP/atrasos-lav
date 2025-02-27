<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
            Hola, {{ Auth::user()->nomape }}! 👋
        </h2>
        <div class="justify-between items-end">
            @if ($usuario->qr)
                <div class="QRCard">
                    <img src="data:image/png;base64,{{ base64_encode($usuario->qr) }}" alt="Código QR" width="100"
                        height="100">
                </div>
            @endif
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h1 class="mt-4 text-2xl text-center font-bold text-gray-900 dark:text-gray-100 mb-6">📊 Métricas
                    Generales
                </h1>

                <!-- Grid de tarjetas (2 columnas x 2 filas) -->
                <div class="grid grid-cols-2 gap-6">
                    <!-- Tarjeta 1: Total de Atrasos Registrados -->
                    <div class="rounded-lg p-6 shadow-lg bg-yellow-600 text-white w-full justify-center">
                        <i class="fas items-center justify-center fa-clipboard-list text-2xl text-white"></i>
                        <div class="flex flex-col items-end space-x-4">
                            <div>
                                <h2 class="text-lg font-semibold">Total de Atrasos Registrados</h2>
                                <p class="text-4xl font-bold mt-2">{{ $cantidadAtrasos }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta 2: Atrasos de Usuario -->
                    <div class="rounded-lg p-6 shadow-lg bg-green-600 text-white w-full">
                        <i class="fas fa-user-clock text-2xl text-white"></i>
                        <div class="flex justify-items-center space-x-4">
                            <div>
                                <h2 class="text-lg font-semibold">Atrasos de {{ Auth::user()->nomape }}</h2>
                                <p class="text-4xl font-bold mt-2">{{ $cantidadAtrasosUsuario }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta 3: Atrasos por Curso -->
                    <div class="rounded-lg p-6 shadow-lg bg-blue-600 text-white w-full">
                        <i class="fas fa-school text-2xl dark:text-white"></i>
                        <div class="flex justify-items-center space-x-4">
                            <div>
                                <h2 class="text-lg font-semibold">Atrasos por Curso</h2>
                                <p class="text-4xl font-bold mt-2">{{ $cantidadAtrasosCurso }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta 4: Detalle de Atrasos -->
                    <div class="rounded-lg p-6 shadow-lg bg-red-600 text-white w-full">
                        <i class="fas fa-exclamation-triangle text-2xl dark:text-white"></i>
                        <div class="flex justify-items-center space-x-4">
                            <div>
                                <h2 class="text-lg font-semibold">Detalle de Atrasos</h2>
                                <p class="text-4xl font-bold mt-2">{{ $detalleAtrasos->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección Blacklist -->
                <div
                    class="mt-4 mb-6 flex flex-col justify-items-center rounded-lg bg-red-600 p-4 shadow-lg w-auto max-w-xl mx-auto">
                    <h2 class="text-lg text-center font-bold text-white mb-6">🚨 Blacklist de Estudiantes</h2>
                    @if ($blacklist->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">
                            No hay estudiantes con 3 o más atrasos registrados.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table
                                class="w-8 divide-y divide-gray-200 dark:divide-gray-700 border border-gray-300 dark:border-gray-700 mx-auto">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="border border-gray-300 dark:border-gray-600 p-2 text-gray-700 dark:text-gray-200">
                                            Nombre</th>
                                        <th
                                            class="border border-gray-300 dark:border-gray-600 p-2 text-gray-700 dark:text-gray-200">
                                            RUT</th>
                                        <th
                                            class="border border-gray-300 dark:border-gray-600 p-2 text-gray-700 dark:text-gray-200">
                                            Curso</th>
                                        <th
                                            class="border border-gray-300 dark:border-gray-600 p-2 text-gray-700 dark:text-gray-200">
                                            Atrasos</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($blacklist as $estudiante)
                                        <tr class="border border-gray-300 dark:border-gray-700">
                                            <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                                                {{ $estudiante->nomape }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                                                {{ $estudiante->rut }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                                                {{ $estudiante->curso }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                                                {{ $estudiante->atrasos_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
