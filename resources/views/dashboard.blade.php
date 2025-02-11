<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bienvenido') }} {{ Auth::user()->nomape }}!
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    Este es su codigo QR
                    @if ($usuario->qr)
                        <div class="border-4 border-sky-500"">
                            <div class="QRCard2">
                                <img src="data:image/png;base64,{{ base64_encode($usuario->qr) }}" alt="Código QR">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
