@props(['disabled' => false, 'type' => 'text'])

<div class="relative">
    <input @disabled($disabled)
        {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm', 'type' => $type, 'onfocus' => "showEyeIcon('{$attributes['id']}', event)", 'onblur' => "hideEyeIcon('{$attributes['id']}', event)"]) }}>
    @if ($type === 'password')
        <button type="button" class="absolute inset-y-0 right-0 pr-3 items-center text-sm leading-5 hidden"
            id="eye-button-{{ $attributes['id'] }}" onclick="togglePassword('{{ $attributes['id'] }}')">
            <i class="fa fa-eye" id="eye-icon-{{ $attributes['id'] }}"></i>
        </button>
    @endif
</div>

@if ($type === 'password')
    <script>
        function togglePassword(inputId) {
            const passwordField = document.getElementById(inputId);
            const eyeIcon = document.getElementById(`eye-icon-${inputId}`);
            if (!passwordField || !eyeIcon) return;

            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            eyeIcon.className = type === 'password' ? 'fa fa-eye' : 'fa fa-eye-slash';
        }

        function showEyeIcon(inputId, event) {
            event.stopPropagation();
            const eyeButton = document.getElementById(`eye-button-${inputId}`);
            if (eyeButton) {
                eyeButton.classList.remove('hidden');
            }
        }

        function hideEyeIcon(inputId, event) {
            event.stopPropagation();
            const eyeButton = document.getElementById(`eye-button-${inputId}`);
            if (eyeButton) {
                eyeButton.classList.add('hidden');
            }
        }
    </script>
@endif
