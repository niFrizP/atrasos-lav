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
                            <x-text-input id="nomape" class="block mt-1 w-full" type="text" name="nomape" value="{{ $estudiante->nomape }}" required />
                            <x-input-error :messages="$errors->get('nomape')" class="mt-2" />
                        </div>

                        <!-- RUT -->
                        <div class="mb-4">
                            <x-input-label for="rut" :value="__('RUT')" />
                            <x-text-input id="rut" class="block mt-1 w-full" type="text" name="rut" value="{{ $estudiante->rut }}" required />
                            <x-input-error :messages="$errors->get('rut')" class="mt-2" />
                        </div>

                        <!-- Correo -->
                        <div class="mb-4">
                            <x-input-label for="correo" :value="__('Correo Electrónico')" />
                            <x-text-input id="correo" class="block mt-1 w-full" type="email" name="correo" value="{{ $estudiante->correo }}" />
                            <x-input-error :messages="$errors->get('correo')" class="mt-2" />
                        </div>

                        <!-- Teléfono -->
                        <div class="mb-4">
                            <x-input-label for="telefono" :value="__('Teléfono')" />
                            <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" value="{{ $estudiante->telefono }}" />
                            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                        </div>

                        <!-- Curso -->
                        <div class="mb-4">
                            <x-input-label for="curso_id" :value="__('Curso')" />
                            <select name="curso_id" id="curso_id"
                                class="block mt-1 w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
                                <option value="">{{ __('Sin curso') }}</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ $estudiante->curso_id == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->codigo }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('curso_id')" class="mt-2" />
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

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
