<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServicoFactory extends Factory
{
    public function definition()
    {
        return [
            'empresa_id' => Empresa::factory(),
            'nome' => $this->faker->word(),
            'descricao' => $this->faker->sentence(),
            'duracao_minutos' => $this->faker->randomElement([30, 40, 60, 90]),
            'preco' => $this->faker->randomFloat(2, 20, 200),
        ];
    }
}
