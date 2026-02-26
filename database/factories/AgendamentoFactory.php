<?php

// database/factories/AgendamentoFactory.php
namespace Database\Factories;

use App\Models\Indicacao;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgendamentoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'indicacao_id' => Indicacao::factory(),
            'mentor_id' => Usuario::factory()->mentor(),
            'mentorado_id' => Usuario::factory()->parceiro(),
            'horario' => $this->faker->time('H:i'),
            'data' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'status' => 'pendente', // Status padrão
        ];
    }

    // STATE para criar um agendamento já aprovado
    public function aprovado(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aprovado',
        ]);
    }
}