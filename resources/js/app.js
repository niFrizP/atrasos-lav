import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Sentry
Sentry.onLoad(function () {
    if (!Sentry.getCurrentHub().getClient()) {
        // Solo inicializa si no está inicializado ya
        Sentry.init({
            integrations: [
                Sentry.replayIntegration(),
            ],
            replaysSessionSampleRate: 0.1, // Cambia al 100% en desarrollo si es necesario
            replaysOnErrorSampleRate: 1.0, // Muestra el 100% de las sesiones donde hay errores
        });
    }
});

// Limpiador de búsqueda
function clearSearch() {
    document.getElementById('searchInput').value = '';
}

// Formateador de RUT
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