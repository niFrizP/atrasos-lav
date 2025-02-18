<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle del Estudiante') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Datos del Estudiante') }}</h1>
                    <div class="flex justify-between items-start">
                        <!-- Datos del Estudiante -->
                        <div class="w-2/3">
                            <p><strong>{{ __('Nombre Completo:') }}</strong> {{ $estudiante->nomape }}</p>
                            <br>
                            <p><strong>{{ __('RUT:') }}</strong> {{ $estudiante->rut_formatted }}</p>
                            <br>
                            <p><strong>{{ __('Correo Electrónico:') }}</strong>
                                {{ $estudiante->correo ?? __('No registrado') }}</p>
                            <br>
                            <p><strong>{{ __('Teléfono:') }}</strong> {{ $estudiante->telefono ?? __('No registrado') }}
                            </p>
                            <br>
                            <p><strong>{{ __('Curso:') }}</strong>
                                {{ $estudiante->curso ? $estudiante->curso->codigo : __('Sin curso asignado') }}</p>
                        </div>
                    </div>
                    <!-- Columna para el QR -->
                    <div class="flex justify-end">
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('QR del Estudiante') }}</p>
                            <br><br>
                        @if ($estudiante->qr)
                            <div class="QRCard">
                                <img src="data:image/png;base64,{{ base64_encode($estudiante->qr) }}" alt="Código QR"
                                    class="mx-auto" width="150" height="150">
                            </div>
                        @endif
                    </div>
                    <!-- Botones de acciones -->
                    <div class="flex justify-end mt-6 space-x-2">
                        <a href="{{ route('estudiantes.edit', $estudiante) }}"
                            class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md">
                            {{ __('Editar') }}
                        </a>
                        <form action="{{ route('estudiantes.disable', $estudiante) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                                {{ __('Deshabilitar') }}
                            </button>
                        </form>
                    </div>

                    <!-- Atrasos registrados -->
                    <h2 class="text-xl font-semibold mt-8">{{ __('Atrasos Registrados') }}</h2>
                    <div class="overflow-x-auto mt-4">
                        @if ($estudiante->atrasos->isEmpty())
                            <p>{{ __('No hay atrasos registrados para este estudiante.') }}</p>
                        @else
                            <table class="w-full border border-gray-300 dark:border-gray-700">
                                <thead class="bg-gray-200 dark:bg-gray-700">
                                    <tr>
                                        <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Fecha') }}
                                        </th>
                                        <th class="border border-gray-300 dark:border-gray-600 p-2">
                                            {{ __('Descripción') }}</th>
                                        <th class="border border-gray-300 dark:border-gray-600 p-2">
                                            {{ __('Acciones') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($estudiante->atrasos as $atraso)
                                        <tr class="border border-gray-300 dark:border-gray-700">
                                            <td class="p-2 text-center">{{ $atraso->fecha_atraso }}</td>
                                            <td class="p-2 text-center">{{ $atraso->razon }}</td>
                                            <td class="p-2 flex end space-x-2">
                                                <a href="{{ route('atrasos.show', $atraso) }}"
                                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                                                    {{ __('Ver') }}
                                                </a>
                                                <a href="{{ route('atrasos.edit', $atraso) }}"
                                                    class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md">
                                                    {{ __('Editar') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
