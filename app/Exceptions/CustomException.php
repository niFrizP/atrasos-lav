<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class CustomException extends Exception
{
    /**
     * Constructor de la excepción.
     *
     * @param string     $message  Mensaje de error.
     * @param int        $code     Código de error.
     * @param Exception  $previous Excepción previa, si la hay.
     */
    public function __construct($message = 'Ha ocurrido un error personalizado.', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Reporta o registra la excepción.
     *
     * Puedes personalizar este método para, por ejemplo, enviar el error a un servicio externo
     * o a los logs de Laravel.
     */
    public function report()
    {
        Log::error('Error personalizado: ' . $this->getMessage(), ['exception' => $this]);
    }

    /**
     * Renderiza una respuesta HTTP para el usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        // Si la solicitud espera una respuesta en formato JSON (por ejemplo, en una API)
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $this->getMessage()
            ], 500);
        }

        // Para solicitudes web, puedes redirigir o mostrar una vista personalizada.
        return response()->view('errors.custom', ['message' => $this->getMessage()], 500);
    }
}
