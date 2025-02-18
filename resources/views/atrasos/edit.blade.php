<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Atraso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Editar Atraso') }}</h1>

                    <form action="{{ route('atrasos.update', $atraso->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Estudiante -->
                        <div class="mb-4">
                            <x-input-label for="estudiante_id" class="form-label">{{ __('Estudiante') }}</x-input-label>
                            <select name="estudiante_id" id="estudiante_id"
                                class="form-select bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 rounded-md shadow-smt @error('estudiante_id') is-invalid @enderror">
                                @foreach ($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}"
                                        @if ($atraso->estudiante_id == $estudiante->id) selected @endif>
                                        {{ $estudiante->nomape }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estudiante_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fecha del Atraso -->
                        <div class="mb-4">
                            <x-input-label for="fecha_atraso"
                                class="form-label">{{ __('Fecha y Hora Atraso') }}</x-input-label>
                            <input
                                class="form-select bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 rounded-md shadow-sm"
                                type="datetime-local" name="fecha_atraso"
                                value="{{ old('fecha_atraso', \Carbon\Carbon::createFromFormat('d/m/Y H:i', $atraso->fecha_atraso)->format('Y-m-d\TH:i')) }}">
                            @error('fecha_atraso')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Razón del Atraso -->
                        <div class="mb-4">
                            <x-input-label for="razon" class="form-label">{{ __('Razón') }}</x-input-label>
                            <textarea name="razon" id="razon"
                                class="form-select block mt-1 w-full bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 rounded-md shadow-sm"
                                required placeholder="Motivo del atraso" @error('razon') is-invalid @enderror">{{ old('razon', $atraso->razon) }}</textarea>
                            @error('razon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Evidencia -->
                        <div class="mb-4">
                            <x-input-label for="evidencia" class="form-label">{{ __('Evidencia') }}</x-input-label>
                            @if ($atraso->evidencia_url)
                                <div class="mt-2">
                                    <a href="{{ $atraso->evidencia_url }}" target="_blank"
                                        class="text-blue-500 hover:text-blue-700">
                                        {{ __('Ver imagen actual') }}
                                    </a>
                                </div>
                            @endif
                            <x-text-input id="evidencia" class="block mt-1 w-full" type="file" name="evidencia"
                                accept="image/*" @error('evidencia')>
                                    <x-input-error :messages="$errors->get('evidencia')" class="mt-2" />
                                </x-text-input>
                            </div>

                            <!-- Botón de Enviar -->
                            <div class="flex justify-end mt-6">
                                <x-primary-button>
                                    {{ __('Actualizar Atraso') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </x-app-layout>
