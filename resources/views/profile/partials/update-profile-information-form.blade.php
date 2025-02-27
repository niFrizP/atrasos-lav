<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Información del perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Actualice la información de perfil de su cuenta') }}
        </p>
    </header>

    <!-- Formulario para reenviar el correo de verificación -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Formulario de actualización de perfil -->
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Nombre Completo -->
        <div class="mb-4">
            <x-input-label for="nomape" :value="__('Nombre Completo')" />
            <x-text-input id="nomape" class="block mt-1 w-full" type="text" name="nomape" :value="old('nomape', $user->nomape)"
                required autofocus />
            <x-input-error :messages="$errors->get('nomape')" class="mt-2" />
        </div>

        <!-- Correo -->
        <div class="mb-4">
            <x-input-label for="correo" :value="__('Correo')" />
            <x-text-input id="correo" name="correo" type="email" class="mt-1 block w-full" :value="old('correo', $user->correo)"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('correo')" class="mt-2" />
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Una vez actualice su correo deberá usar sus nuevas credenciales para ingresar') }}
            </p>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Tu dirección de correo no ha sido verificada.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu correo.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sección para campos de RUT según si es extranjero o no -->
        <div x-data="{ extranjero: @json((bool) old('extranjero', $user->extranjero)) }">
            <!-- Checkbox para indicar si el usuario es extranjero -->
            <div class="mb-4">
                <label for="extranjero" class="inline-flex items-center">
                    <input id="extranjero" type="checkbox" class="form-checkbox mr-2" name="extranjero" value="1"
                        x-model="extranjero">
                    <span
                        class="ml-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">{{ __('¿Es Extranjero?') }}</span>
                </label>
                <x-input-error :messages="$errors->get('extranjero')" class="mt-2" />
            </div>

            <!-- Campo para RUT Extranjero (visible solo si es extranjero) -->
            <div class="mb-4" x-show="extranjero">
                <x-input-label for="rut_extranjero" :value="__('RUT Extranjero')" />
                <x-text-input id="rut_extranjero" class="block mt-1 w-full" type="number" name="rut_extranjero"
                    :value="old('rut_extranjero', $user->rut_extranjero)" />
                <x-input-error :messages="$errors->get('rut_extranjero')" class="mt-2" />
            </div>

            <!-- Campo para RUT (visible solo si NO es extranjero) -->
            <div class="mb-4" x-show="!extranjero">
                <x-input-label for="rut" :value="__('RUT')" />
                <x-text-input id="rut" class="block mt-1 w-full" type="text" name="rut" maxlength="12"
                    required oninput="formatRut(this)" :value="old('rut', $user->rut)" />
                <x-input-error :messages="$errors->get('rut')" class="mt-2" />
            </div>
        </div>

        <!-- Teléfono -->
        <div class="mb-4">
            <x-input-label for="telefono" :value="__('Teléfono')" />
            <x-text-input id="telefono" maxlength="9" class="block mt-1 w-full" type="text" name="telefono"
                :value="old('telefono', $user->telefono)" />
            <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
        </div>

        <!-- Botón de guardar -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Guardado.') }}
                </p>
            @endif
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
    </script>
</section>
