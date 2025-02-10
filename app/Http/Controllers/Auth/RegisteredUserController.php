<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Role;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */

    public function create(): View
    {
        $roles = Role::all(); // Obtiene todos los roles de la base de datos
        return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nomape' => ['required', 'string', 'max:255'],
            'rut' => ['required', 'string', 'max:255', 'unique:usuarios'],
            'telefono' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:usuarios'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'rol_id' => ['required', 'exists:roles,id'],
        ]);

        $usuario = new Usuario();
        $usuario->nomape = $request->nomape;
        $usuario->rut = $request->rut; // Asegúrate de recibir este campo
        $usuario->telefono = $request->telefono;
        $usuario->correo = $request->correo;
        $usuario->password = Hash::make($request->password);
        $usuario->rol_id = $request->rol_id; // Ajusta según el rol predeterminado
        $usuario->save();


        event(new Registered($usuario));

        Auth::login($usuario);

        return redirect(route('dashboard', absolute: false));
    }
}
