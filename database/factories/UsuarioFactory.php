<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    protected static ?string $password; // Reutiliza la misma contraseña en todas las instancias

    public function definition(): array
    {
        return [
            'nomape' => $this->faker->name(),
            'rut' => $this->faker->unique()->numerify('########-#'),
            'telefono' => $this->faker->optional()->numerify('9########'),
            'correo' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'rol_id' => 1, // Ajusta esto según los roles que tengas
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
