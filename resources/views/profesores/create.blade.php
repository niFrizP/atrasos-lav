<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Agregar Profesor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Nuevo Profesor') }}</h1>

                    <form action="{{ route('profesores.store') }}" method="POST">
                        @csrf

                        <!-- Nombre Completo -->
                        <div class="mb-4">
                            <x-input-label for="nomape" :value="__('Nombre Completo')" />
                            <x-text-input id="nomape" class="block mt-1 w-full" type="text" name="nomape"
                                required placeholder="Ej: John Doe" />
                            <x-input-error :messages="$errors->get('nomape')" class="mt-2" />
                        </div>

                        <!-- RUT -->
                        <div class="mb-4">
                            <x-input-label for="rut" :value="__('RUT')" />
                            <x-text-input id="rut" class="block mt-1 w-full" type="text" name="rut"
                                required placeholder="Ej: 9.999.999-9" />
                            <x-input-error :messages="$errors->get('rut')" class="mt-2" />
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="mb-4">
                            <x-input-label for="correo" :value="__('Correo Electrónico')" />
                            <x-text-input id="correo" class="block mt-1 w-full" type="email" name="correo"
                                required placeholder="Ej: johndoe@email.com" />
                            <x-input-error :messages="$errors->get('correo')" class="mt-2" />
                        </div>

                        <!-- Teléfono -->
                        <div class="mb-4">
                            <x-input-label for="telefono" :value="__('Teléfono')" />
                            <x-text-input id="telefono" class="block mt-1 w-full" type="tel" name="telefono"
                                required placeholder="Ej: 9 99999999" />
                            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Contraseña')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Selección de Curso (Asignación como Jefe de Curso) -->
                        <div class="mb-4">
                            <x-input-label for="curso_id" :value="__('Asignar Curso (Jefe de Curso)')" />
                            <select id="curso_id" name="curso_id"
                                class="form-select bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Selecciona un curso') }}</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id }}">
                                        {{ $curso->codigo }} - {{ $curso->grado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('curso_id')" class="mt-2" />
                        </div>

                        <!-- Rol (Oculto, siempre será Profesor) -->
                        <input type="hidden" name="rol_id" value="3">

                        <!-- Botones -->
                        <div class="flex justify-end mt-6">
                            <a href="{{ route('profesores.index') }}"
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
</x-app-layout>
