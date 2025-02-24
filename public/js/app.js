import $ from 'jquery';
window.$ = $;
window.jQuery = $;

import 'select2/dist/js/select2.min.js';
import 'select2/dist/css/select2.min.css';

$(document).ready(function () {
    $('#estudiante_id').select2({
        placeholder: 'Selecciona un estudiante',
        allowClear: true,
        width: '100%'
    });
});

console.log('app.js cargado');
