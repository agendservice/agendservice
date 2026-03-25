<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UsuarioFactory extends Factory
{
    public function definition()
    {
        return [
            'nome' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'telefone' => $this->faker->phoneNumber(),
            'tipo' => 'cliente',
            'status' => 'ativo',
        ];
    }

    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'admin',
        ]);
    }

    public function funcionario()
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'funcionario',
        ]);
    }
}
