@vite(['resources/js/app.js'])
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

                    <!-- Formulario para registrar el atraso -->
                    <form action="{{ route('atrasos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Seleccionar Estudiante -->
                        <div class="mb-4">
                            <x-input-label for="estudiante_id" :value="__('Estudiante')" />
                            <select id="estudiante_id" name="estudiante_id"
                                class="w-full h-12 bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 rounded-md shadow-sm"></select>
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
                                placeholder="Motivo del atraso"></textarea>
                            <x-input-error :messages="$errors->get('razon')" class="mt-2" />
                        </div>

                        <!-- Evidencia (opcional) -->
                        <div class="mb-4">
                            <x-input-label for="evidencia" :value="__('Evidencia (opcional)')" />
                            <x-text-input id="evidencia" class="block mt-1 w-full" type="file" name="evidencia"
                                accept="image/*" />
                            <x-input-error :messages="$errors->get('evidencia')" class="mt-2" />
                        </div>

                        <div class="text-sm text-gray-500 mt-1">
                            <p>{{ __('Tamaño máximo: 2 MB') }}</p>
                            <p>{{ __('Formatos permitidos: jpeg, png, jpg') }}</p>
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

    <!-- Incluir jQuery desde CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Incluir Select2 CSS y JS desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Ajustar estilos de select2 para que coincidan con Tailwind */
        .select2-container .select2-selection--single {
            @apply bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 rounded-md shadow-sm p-2;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            @apply text-gray-900 dark:text-gray-100;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            @apply text-gray-500;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#estudiante_id').select2({
                placeholder: 'Busque un estudiante por nombre o apellido',
                ajax: {
                    url: '{{ route('buscar.estudiantes') }}', // Ruta en Laravel
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // Término de búsqueda
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1,
                language: {
                    inputTooShort: function() {
                        return 'Ingresa al menos un carácter';
                    },
                    errorLoading: function() {
                        return 'No se pudieron cargar los resultados';
                    },
                    loadingMore: function() {
                        return 'Cargando más resultados...';
                    },
                    noResults: function() {
                        return 'No se encontraron resultados';
                    },
                    searching: function() {
                        return 'Buscando...';
                    }
                }
            });
        });
    </script>

</x-app-layout>
