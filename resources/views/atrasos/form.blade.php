<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrar Atraso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Nuevo Atraso') }}</h1>

                    <!-- Asegúrate de incluir el atributo enctype para subir archivos -->
                    <form action="{{ route('atrasos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Seleccionar Estudiante -->
                        <div class="mb-4">
                            <x-input-label for="estudiante_id" :value="__('Estudiante')" />
                            <select id="estudiante_id" name="estudiante_id"
                                class="form-select bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Selecciona un estudiante') }}</option>
                                @foreach ($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}">
                                        {{ $estudiante->nomape }} - {{ $estudiante->curso->grado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('estudiante_id')" class="mt-2" />
                        </div>

                        <!-- Fecha del Atraso -->
                        <div class="mb-4">
                            <x-input-label for="fecha_atraso" :value="__('Fecha y Hora del Atraso')" />
                            <x-text-input id="fecha_atraso"
                                class="form-select bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 rounded-md shadow-sm"
                                type="datetime-local" name="fecha_atraso" required />
                            <x-input-error :messages="$errors->get('fecha_atraso')" class="mt-2" />
                        </div>

                        <!-- Razón del Atraso -->
                        <div class="mb-4">
                            <x-input-label for="razon" :value="__('Razón')" />
                            <textarea id="razon" name="razon"
                                class="form-select block mt-1 w-full bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 rounded-md shadow-sm"
                                required placeholder="Motivo del atraso"></textarea>
                            <x-input-error :messages="$errors->get('razon')" class="mt-2" />
                        </div>

                        <!-- Evidencia (opcional) -->
                        <div class="mb-4">
                            <x-input-label for="evidencia" :value="__('Evidencia (opcional)')" />
                            <x-text-input id="evidencia" class="block mt-1 w-full" type="file" name="evidencia"
                                accept="image/*" />
                            <x-input-error :messages="$errors->get('evidencia')" class="mt-2" />
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end mt-6">
                            <a href="{{ route('atrasos.index') }}"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg mr-2">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Guardar') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#estudiante_id').select2({
                placeholder: 'Selecciona un estudiante',
                allowClear: true,
            });
        });
    </script>
</x-app-layout>
