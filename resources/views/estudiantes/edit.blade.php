<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Estudiante') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Editar Estudiante') }}</h1>

                    <form action="{{ route('estudiantes.update', $estudiante) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <!-- Nombre -->
                        <div class="mb-4">
                            <x-input-label for="nomape" :value="__('Nombre Completo')" />
                            <x-text-input id="nomape" class="block mt-1 w-full" type="text" name="nomape"
                                value="{{ old('nomape', $estudiante->nomape) }}" required />
                            <x-input-error :messages="$errors->get('nomape')" class="mt-2" />
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="mb-4">
                            <x-input-label for="fec_naci" :value="__('Fecha de Nacimiento')" />
                            <x-text-input id="fec_naci"
                                class="form-select bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 rounded-md shadow-sm"
                                type="date" name="fec_naci" value="{{ old('fec_naci', $estudiante->fec_naci) }}" />
                            <x-input-error :messages="$errors->get('fec_naci')" class="mt-2" />
                        </div>

                        <!-- Campo RUT (chileno) -->
                        <div class="mb-4" id="rut_container">
                            <x-input-label for="rut" :value="__('RUT')" />
                            <x-text-input id="rut" class="block mt-1 w-full" type="text" name="rut"
                                maxlength="12" value="{{ old('rut', $estudiante->rut) }}" oninput="formatRut(this)" />
                            <x-input-error :messages="$errors->get('rut')" class="mt-2" />
                        </div>

                        <!-- Campo RUT Extranjero -->
                        <div class="mb-4" id="rut_extranjero_container">
                            <x-input-label for="rut_extranjero" :value="__('RUT Extranjero')" />
                            <x-text-input id="rut_extranjero" class="block mt-1 w-full" type="text"
                                name="rut_extranjero" maxlength="15"
                                value="{{ old('rut_extranjero', $estudiante->rut_extranjero) }}" />
                            <x-input-error :messages="$errors->get('rut_extranjero')" class="mt-2" />
                        </div>

                        <!-- Checkbox Extranjero -->
                        <div class="mb-4">
                            <x-input-label for="extranjero" :value="__('¿Es extranjero?')" />
                            <input type="hidden" name="extranjero" value="0">
                            <input type="checkbox" id="extranjero" name="extranjero" value="1"
                                {{ old('extranjero', $estudiante->extranjero) == 1 ? 'checked' : '' }}
                                onclick="toggleRutFields()" />
                            <x-input-error :messages="$errors->get('extranjero')" class="mt-2" />
                        </div>

                        <!-- Correo -->
                        <div class="mb-4">
                            <x-input-label for="correo" :value="__('Correo Electrónico')" />
                            <x-text-input id="correo" class="block mt-1 w-full" type="email" name="correo"
                                value="{{ old('correo', $estudiante->correo) }}" />
                            <x-input-error :messages="$errors->get('correo')" class="mt-2" />
                        </div>

                        <!-- Teléfono -->
                        <div class="mb-4">
                            <x-input-label for="telefono" :value="__('Teléfono')" />
                            <x-text-input id="telefono" class="block mt-1 w-full" type="text" maxlength="9"
                                name="telefono" value="{{ old('telefono', $estudiante->telefono) }}" />
                            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                        </div>

                        <!-- Curso -->
                        <div class="mb-4">
                            <x-input-label for="curso_id" :value="__('Curso')" />
                            <select name="curso_id" id="curso_id"
                                class="block mt-1 w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                                <option value="">{{ __('Selecciona un curso') }}</option>

                                <!-- Sin Curso -->
                                <optgroup label="Sin Asignar" style="font-weight: bold;">
                                    @foreach ($cursos as $curso)
                                        @if ($curso->grado_id == 5)
                                            <option value="{{ $curso->id }}">{{ $curso->codigo }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>

                                <!-- Pre-Básica -->
                                <optgroup label="Pre-Básica" style="font-weight: bold;">
                                    @foreach ($cursos as $curso)
                                        @if ($curso->grado_id == 1)
                                            <option value="{{ $curso->id }}">{{ $curso->codigo }} -
                                                {{ $curso->grado->nombre }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>

                                <!-- Básica -->
                                <optgroup label="Básica" style="font-weight: bold;">
                                    @foreach ($cursos as $curso)
                                        @if ($curso->grado_id == 2)
                                            <option value="{{ $curso->id }}">{{ $curso->codigo }} -
                                                {{ $curso->grado->nombre }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>

                                <!-- Media -->
                                <optgroup label="Media" style="font-weight: bold;">
                                    @foreach ($cursos as $curso)
                                        @if ($curso->grado_id == 3)
                                            <option value="{{ $curso->id }}">{{ $curso->codigo }} -
                                                {{ $curso->grado->nombre }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>

                                <!-- Finalizado -->
                                <optgroup label="Finalizado" style="font-weight: bold;">
                                    @foreach ($cursos as $curso)
                                        @if ($curso->grado_id == 4)
                                            <option value="{{ $curso->id }}">{{ $curso->codigo }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            </select>
                            <x-input-error :messages="$errors->get('curso_id')" class="mt-2" />
                        </div>

                        <!-- Motivo de cambio (si el curso cambia) -->
                        <div id="motivo_cambio_container" class="mb-4" style="display: none;">
                            <x-input-label for="motivo_cambio" :value="__('Motivo de cambio')" />
                            <x-text-input id="motivo_cambio" class="block mt-1 w-full" type="text"
                                name="motivo_cambio" />
                            <x-input-error :messages="$errors->get('motivo_cambio')" class="mt-2" />
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end mt-6">
                            <a href="{{ route('estudiantes.index') }}"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg mr-2">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Guardar Cambios') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- JavaScript -->
                    <script>
                        function formatRut(input) {
                            let value = input.value.toUpperCase().replace(/[^0-9K]/g, '');
                            if (value.length === 9) {
                                value = value.replace(/^(\d{2})(\d{3})(\d{3})([\dkK])$/, '$1.$2.$3-$4');
                            } else if (value.length === 8) {
                                value = value.replace(/^(\d{1})(\d{3})(\d{3})([\dkK])$/, '$1.$2.$3-$4');
                            }
                            input.value = value;
                        }

                        // Mostrar u ocultar los campos según el checkbox en edición
                        function toggleRutFields() {
                            const isExtranjero = document.getElementById('extranjero').checked;
                            const rutContainer = document.getElementById('rut_container');
                            const rutExContainer = document.getElementById('rut_extranjero_container');

                            if (isExtranjero) {
                                rutContainer.style.display = 'none';
                                rutExContainer.style.display = 'block';
                            } else {
                                rutContainer.style.display = 'block';
                                rutExContainer.style.display = 'none';
                            }
                        }

                        // Control de curso y motivo de cambio (opcional)
                        function checkCursoChange() {
                            const cursoOriginal = "{{ $estudiante->curso_id }}";
                            const cursoSeleccionado = document.getElementById('curso_id').value;
                            const motivoContainer = document.getElementById('motivo_cambio_container');

                            if (cursoSeleccionado && cursoSeleccionado !== cursoOriginal) {
                                motivoContainer.style.display = 'block';
                            } else {
                                motivoContainer.style.display = 'none';
                            }
                        }

                        window.onload = function() {
                            toggleRutFields();
                            checkCursoChange();
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
