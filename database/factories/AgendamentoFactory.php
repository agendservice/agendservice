<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Servico;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgendamentoFactory extends Factory
{
    public function definition()
    {
        $inicio = $this->faker->dateTimeBetween('now', '+1 month');
        return [
            'cliente_id' => Cliente::factory(),
            'funcionario_id' => Funcionario::factory(),
            'servico_id' => Servico::factory(),
            'data_hora_inicio' => $inicio,
            'data_hora_fim' => (clone $inicio)->modify('+1 hour'),
            'status' => 'pendente',
            'observacao' => $this->faker->text(),
        ];
    }
}
