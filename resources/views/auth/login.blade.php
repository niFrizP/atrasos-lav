<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('login') }}">

        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="correo" :value="__('Correo')" />
            <span class="icon is-small is-left">
                <i class="fa-solid fa-user"></i>
            </span>
            <x-text-input id="correo" class="block mt-1 w-full" type="email" name="correo" :value="old('correo')" required
                autofocus autocomplete="username" placeholder=" ej: john.doe@lav.cl" />
            <x-input-error :messages="$errors->get('correo')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Recordarme') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Iniciar Sesión') }}
            </x-primary-button>
        </div>
    </form>
    <footer class="mt-8 text-center text-gray-600 dark:text-gray-400 text-sm">
        &copy; {{ now()->year }} Liceo Antonio Varas de Cauquenes.
        <br><br>
        <p>Proyecto desarrollado por <a href="https://www.linkedin.com/in/nicolasfrizpereira/" target="_blank">
                niFrizP</a>
            en
            colaboración con el <a href="https://web.facebook.com/enlaces.lav.3">Departamento de Innovación LAV</a></p>
    </footer>
</x-guest-layout>
