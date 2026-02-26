<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RegrasFactory extends Factory
{
    public function definition()
    {
        return [
            'titulo' => 'REGRA ' . $this->faker->word(),
            'descricao' => $this->faker->paragraph(3),
            'instituicao_bancaria_nome' => $this->faker->randomElement(['BANCO DO BRASIL', 'CAIXA', 'ITAU', 'BRADESCO', 'SANTANDER']),
        ];
    }
}