<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UsuarioFactory extends Factory
{
    public function definition()
    {
        return [
            'nome' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'cpf' => $this->faker->unique()->numerify('###########'),
            'acesso_id' => 1,
            'password' => bcrypt('password'), // Senha padrão para todos
            'status' => 'pendente',
            // 'tempo_atendimento' => '01:00:00',
            // Adicione outros campos com valores padrão
            'rg' => $this->faker->numerify('########'),
            'endereco' => $this->faker->streetAddress(),
            'cep' => $this->faker->postcode(),
            'data_nascimento' => $this->faker->date(),
            'nome_mae' => $this->faker->name('female'),
            'profissao' => $this->faker->jobTitle(),
        ];
    }

    // --- DEFININDO ESTADOS PARA TIPOS DE USUÁRIO ---
    public function parceiro()
    {
        return $this->state(fn (array $attributes) => ['acesso_id' => 1]);
    }

    public function admin()
    {
        return $this->state(fn (array $attributes) => ['acesso_id' => 2]);
    }

    public function mentor()
    {
        return $this->state(fn (array $attributes) => ['acesso_id' => 3]);
    }
}