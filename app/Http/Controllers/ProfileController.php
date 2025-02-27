<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Muestra el formulario de perfil del usuario.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualiza la información del perfil del usuario.
     */
    public function update(Request $request): RedirectResponse
    {
        // Valida los campos permitidos
        $validatedData = $request->validate([
            'nomape'           => 'required|string|max:350',
            'correo'           => 'required|email|max:255',
            'rut'              => 'nullable|string|max:15',
            'rut_extranjero'   => 'nullable|integer',
            'telefono'         => 'nullable|string|max:9',
            'extranjero'       => 'nullable|boolean',
        ]);

        $user = $request->user();
        $user->nomape   = $validatedData['nomape'];
        $user->correo   = $validatedData['correo'];
        $user->telefono = $validatedData['telefono'] ?? null;

        // Verifica si el usuario es extranjero
        if (isset($validatedData['extranjero']) && $validatedData['extranjero']) {
            $user->extranjero    = 1;
            $user->rut_extranjero = $validatedData['rut_extranjero'];
            $user->rut           = null;
        } else {
            $user->extranjero    = 0;
            $user->rut           = $validatedData['rut'];
            $user->rut_extranjero = null;
        }

        $user->save();

        // Genera el código QR después de guardar los cambios
        $user->generateQR();

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Elimina la cuenta del usuario.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function dashboard()
    {
        $usuario = Auth::user();
        return view('dashboard', compact('usuario'));
    }

    public function showProfile()
    {
        $usuario = Auth::user();
        return view('profile.edit', compact('usuario'));
    }
}
