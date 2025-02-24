<?php
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Exceptions\CustomException;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        // Ejemplo para excepciones de validación
        if ($exception instanceof ValidationException) {
            // Si la petición espera JSON (por ejemplo, en una API)
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => __('errors.validation_failed'), // usa un archivo de traducción
                    'details' => $exception->errors()
                ], 422);
            }

            // Para peticiones web, redirige atrás con los errores en el session flash
            return redirect()->back()
                ->withErrors($exception->errors())
                ->withInput();
        }

        // Ejemplo para una excepción personalizada
        if ($exception instanceof \App\Exceptions\CustomException) {
            return response()->view('errors.custom', [
                'message' => __('errors.custom_message') // mensaje traducido
            ], 500);
        }

        // Para el resto de las excepciones, usar el render por defecto
        return parent::render($request, $exception);
    }
}
