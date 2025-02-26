<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle del Atraso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h1 class="text-2xl font-bold mb-6">{{ __('Detalle del Atraso') }}</h1>

                    <!-- Estudiante -->
                    <div class="mb-4">
                        <strong>{{ __('Estudiante:') }}</strong>
                        {{ $atraso->estudiante->nomape }} ({{ $atraso->estudiante->rut }})
                    </div>

                    <!-- Si deseas mostrar el curso asociado al estudiante -->
                    @if ($atraso->estudiante->relationLoaded('curso') && $atraso->estudiante->curso)
                        <div class="mb-4">
                            <strong>{{ __('Curso:') }}</strong>
                            {{ $atraso->estudiante->curso->nombre ?? __('No definido') }}
                        </div>
                    @endif

                    <!-- Fecha del Atraso -->
                    <div class="mb-4">
                        <strong>{{ __('Fecha y Hora del Atraso:') }}</strong>
                        {{ $atraso->fecha_atraso }}
                    </div>

                    <!-- Inspector -->
                    <div class="mb-4">
                        <strong>{{ __('Inspector:') }}</strong>
                        {{ $atraso->inspector->nomape ?? __('No definido') }}
                    </div>


                    <!-- Razón del Atraso -->
                    <div class="mb-4">
                        <strong>{{ __('Razón:') }}</strong>
                        <p>{{ $atraso->razon }}</p>
                    </div>

                    <!-- Evidencia -->
                    <div class="mb-4">
                        <strong>{{ __('Evidencia:') }}</strong>
                        @if ($atraso->evidencia_url)
                            <div class="mt-2">
                                <a href="{{ $atraso->evidencia_url }}" target="_blank"
                                    class="text-blue-500 hover:text-blue-700">
                                    {{ __('Ver imagen actual') }}
                                </a>
                            </div>
                    </div>
                @else
                    <p>{{ __('No hay evidencia') }}</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
    </div>
</x-app-layout>
