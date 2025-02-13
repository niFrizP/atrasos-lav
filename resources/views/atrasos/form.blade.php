<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($atraso) ? __('Editar Atraso') : __('Registrar Atraso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ isset($atraso) ? 'Editar Atraso' : 'Registrar Atraso' }}</h1>

                    <form method="POST"
                        action="{{ isset($atraso) ? route('atrasos.update', $atraso->id) : route('atrasos.store') }}">
                        @csrf
                        @if (isset($atraso))
                            @method('PUT')
                        @endif

                        <!-- Selección de Estudiante -->
                        <!-- Estudiante -->
                        <div class="mb-4">
                            <x-input-label for="estudiante_id" :value="__('Estudiante')" />
                            <select name="estudiante_id" id="estudiante_id" class="block mt-1 w-full" required>
                                <option value="">{{ __('Seleccione un estudiante') }}</option>
                                @foreach ($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}"
                                        {{ isset($atraso) && $atraso->estudiante_id == $estudiante->id ? 'selected' : '' }}>
                                        {{ $estudiante->nomape }} @if ($estudiante->curso)
                                            ({{ $estudiante->curso->nombre }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('estudiante_id')" class="mt-2" />
                        </div>


                        <!-- Fecha -->
                        <div class="mb-4">
                            <x-input-label for="fecha" :value="__('Fecha')" />
                            <x-text-input id="fecha" class="block mt-1 w-full" type="date" name="fecha"
                                value="{{ isset($atraso) ? $atraso->fecha : old('fecha') }}" required />
                            <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
                        </div>

                        <!-- Motivo -->
                        <div class="mb-4">
                            <x-input-label for="motivo" :value="__('Motivo')" />
                            <textarea id="motivo" name="motivo" class="block mt-1 w-full" required>{{ isset($atraso) ? $atraso->motivo : old('motivo') }}</textarea>
                            <x-input-error :messages="$errors->get('motivo')" class="mt-2" />
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end mt-6">
                            <a href="{{ route('atrasos.index') }}"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg mr-2">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ isset($atraso) ? __('Actualizar') : __('Registrar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
