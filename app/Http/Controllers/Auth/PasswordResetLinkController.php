<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar el correo
        $request->validate(['correo' => 'required|email']);

        // Intentar enviar el enlace de restablecimiento de contraseña
        $status = Password::sendResetLink(
            $request->only('correo')
        );

        // Retornar la respuesta con el estado del envío del correo
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('correo'))
            ->withErrors(['correo' => __($status)]);
    }
}
