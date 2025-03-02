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
                            <p><strong>{{ __('Nombre Completo:') }}</strong>
                                <br>
                                {{ $estudiante->nomape }}
                            </p>
                            <br>
                            @if ($estudiante->extranjero)
                                <p><strong>{{ __('RUT Extranjero:') }}</strong>
                                    <br>
                                    {{ $estudiante->rut_extranjero }}
                                </p>
                            @else
                                <p><strong>{{ __('RUT:') }}</strong>
                                    <br>
                                    {{ $estudiante->rut_formatted }}
                                </p>
                            @endif
                            <br>
                            <p><strong>{{ __('Fecha de Nacimiento:') }}</strong>
                                <br>
                                {{ $estudiante->fec_naci ?? __('No registrada') }}
                            </p>
                            <br>
                            <p><strong>{{ __('Correo Electrónico:') }}</strong>
                                <br>
                                {{ $estudiante->correo ?? __('No registrado') }}
                            </p>
                            <br>
                            <p><strong>{{ __('Teléfono:') }}</strong>
                                <br>
                                {{ $estudiante->telefono ?? __('No registrado') }}
                            </p>
                            <br>
                            <p><strong>{{ __('Curso:') }}</strong>
                                <br>
                                @if ($estudiante->curso)
                                    {{ $estudiante->curso->codigo }} - {{ $estudiante->curso->grado->nombre }}
                                @else
                                    {{ __('Sin curso asignado') }}
                                @endif
                            </p>

                            <!-- Mostrar motivo de retiro si está retirado -->
                            <br>
                            <p><strong>{{ __('Motivo de Retiro:') }}</strong>
                                <br>
                                @if (!empty($motivoRetiro))
                                    {{ $motivoRetiro->motivo_cambio }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Sección de QR -->
                    <div class="mt-8">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex justify-center">
                            {{ __('QR del Estudiante') }}
                        </h2>

                        <div class="flex flex-col items-center">
                            @if ($estudiante->qr)
                                <!-- Mostrar el QR si existe -->
                                <img src="data:image/png;base64,{{ base64_encode($estudiante->qr) }}" alt="Código QR"
                                    class="mx-auto mb-4" width="150" height="150">
                            @else
                                <!-- Mostrar mensaje y botón si no hay QR -->
                                <p class="text-red-500 mb-4">{{ __('QR no generado') }}</p>
                                <form action="{{ route('estudiantes.generateQR', $estudiante) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                                        {{ __('Generar QR') }}
                                    </button>
                                </form>
                            @endif
                        </div>
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
