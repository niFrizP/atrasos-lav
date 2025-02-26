import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

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
