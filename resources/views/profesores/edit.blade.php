<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Profesor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Editar Profesor') }}</h1>

                    <form action="{{ route('profesores.update', $profesor->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nombre Completo -->
                        <div class="mb-4">
                            <x-input-label for="nomape" :value="__('Nombre Completo')" />
                            <x-text-input id="nomape" class="block mt-1 w-full" type="text" name="nomape"
                                value="{{ old('nomape', $profesor->nomape) }}" required />
                            <x-input-error :messages="$errors->get('nomape')" class="mt-2" />
                        </div>

                        <!-- Extranjero -->
                        <div class="mb-4">
                            <x-input-label for="extranjero" :value="__('¿Es extranjero?')" />
                            <input type="checkbox" id="extranjero" name="extranjero"
                                :checked="old('extranjero', $profesor - > rut_extranjero ? true : false)"
                                onclick="toggleRutField()" />
                            <x-input-error :messages="$errors->get('extranjero')" class="mt-2" />
                        </div>

                        <!-- RUT -->
                        <div class="mb-4">
                            <x-input-label for="rut" :value="__('RUT')" />
                            <x-text-input id="rut" class="block mt-1 w-full" type="text" name="rut"
                                maxlength="12" value="{{ old('rut', $profesor->rut) }}" required
                                oninput="formatRut(this)"
                                {{ old('extranjero', $profesor->rut_extranjero) ? 'disabled' : '' }} />
                            <x-input-error :messages="$errors->get('rut')" class="mt-2" />
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="mb-4">
                            <x-input-label for="correo" :value="__('Correo Electrónico')" />
                            <x-text-input id="correo" class="block mt-1 w-full" type="email" name="correo"
                                value="{{ old('correo', $profesor->correo) }}" required />
                            <x-input-error :messages="$errors->get('correo')" class="mt-2" />
                        </div>

                        <!-- Teléfono -->
                        <div class="mb-4">
                            <x-input-label for="telefono" :value="__('Teléfono')" />
                            <x-text-input id="telefono" class="block mt-1 w-full" type="tel" name="telefono"
                                maxlength="9" value="{{ old('telefono', $profesor->telefono) }}" />
                            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                        </div>

                        <!-- Curso -->
                        <div class="mb-4">
                            <x-input-label for="curso_id" :value="__('Curso Asignado')" />
                            <select id="curso_id" name="curso_id"
                                class="form-select bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Selecciona un curso (opcional)') }}</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id }}"
                                        {{ $profesor->cursoActual && $profesor->cursoActual->id == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->codigo }} - {{ $curso->grado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('curso_id')" class="mt-2" />
                        </div>
                        <!-- Botones -->
                        <div class="flex justify-end mt-6">
                            <a href="{{ route('profesores.index') }}"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg mr-2">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Guardar Cambios') }}
                            </x-primary-button>
                        </div>
                    </form>
                    <script>
                        function formatRut(input) {
                            let value = input.value.toUpperCase().replace(/[^0-9K]/g, ''); // Limpiamos el valor
                            // Verificamos la longitud
                            if (value.length === 9) {
                                value = value.replace(/^(\d{2})(\d{3})(\d{3})([\dkK])$/, '$1.$2.$3-$4');
                            } else if (value.length === 8) {
                                value = value.replace(/^(\d{1})(\d{3})(\d{3})([\dkK])$/, '$1.$2.$3-$4');
                            }

                            input.value = value;
                        }

                        function toggleRutField() {
                            const isChecked = document.getElementById('extranjero').checked;
                            document.getElementById('rut').disabled = isChecked;
                        }
                        // Llamar a la función al cargar la página para que se aplique el estado inicial
                        window.onload = function() {
                            toggleRutField();
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
