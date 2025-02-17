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
                                value="{{ $estudiante->nomape }}" required />
                            <x-input-error :messages="$errors->get('nomape')" class="mt-2" />
                        </div>

                        <!-- RUT -->
                        <x-input-label for="rut" :value="__('RUT')" />
                        <x-text-input id="rut" class="block mt-1 w-full" type="text" name="rut"
                            maxlength="12" value="{{ old('rut', $estudiante->rut) }}" required
                            oninput="formatRut(this)" />
                        <x-input-error :messages="$errors->get('rut')" class="mt-2" />

                        <!-- Correo -->
                        <div class="mb-4">
                            <x-input-label for="correo" :value="__('Correo Electrónico')" />
                            <x-text-input id="correo" class="block mt-1 w-full" type="email" name="correo"
                                value="{{ $estudiante->correo }}" />
                            <x-input-error :messages="$errors->get('correo')" class="mt-2" />
                        </div>

                        <!-- Teléfono -->
                        <div class="mb-4">
                            <x-input-label for="telefono" :value="__('Teléfono')" />
                            <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono"
                                maxlength="9" value="{{ $estudiante->telefono }}" />
                            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                        </div>

                        <!-- Curso -->
                        <div class="mb-4">
                            <x-input-label for="curso_id" :value="__('Curso')" />
                            <select id="curso_id" name="curso_id" onchange="checkCursoChange()"
                                class="form-select bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Selecciona un curso') }}</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id }}"
                                        {{ $estudiante->curso && $estudiante->curso->id == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->codigo }} - {{ $curso->grado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('curso_id')" class="mt-2" />
                        </div>

                        <!-- Campo de motivo de cambio, solo aparece si el curso cambia -->
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

                    <script>
                        function checkCursoChange() {
                            const cursoOriginal = "{{ $estudiante->curso ? $estudiante->curso->id : '' }}";
                            const cursoSeleccionado = document.getElementById('curso_id').value;
                            const motivoContainer = document.getElementById('motivo_cambio_container');

                            if (cursoSeleccionado && cursoSeleccionado !== cursoOriginal) {
                                motivoContainer.style.display = 'block';
                            } else {
                                motivoContainer.style.display = 'none';
                            }
                        }

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
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
