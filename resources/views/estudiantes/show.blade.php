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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
