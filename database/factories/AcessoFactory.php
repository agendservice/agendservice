<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AcessoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->unique()->word(),
            'nivel_acesso' => $this->faker->unique()->numberBetween(1, 10),
            'metas' => $this->faker->randomElement(['S', 'N']),
            'vendas' => $this->faker->randomElement(['S', 'N']),
            'contabilidade' => $this->faker->randomElement(['S', 'N']),
            'treinamento' => $this->faker->randomElement(['S', 'N']),
            'informativos' => $this->faker->randomElement(['S', 'N']),
            'regras' => $this->faker->randomElement(['S', 'N']),
            'suporte' => $this->faker->randomElement(['S', 'N']),
            'cadastro' => $this->faker->randomElement(['S', 'N']),
            'clientes' => $this->faker->randomElement(['S', 'N']),
        ];
    }

    /**
     * Define um estado para o acesso de Administrador.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'id' => 2,  // ID do acesso de administrador
                'nome' => 'Administrador',
                'nivel_acesso' => 10,
                'metas' => 'S',
                'vendas' => 'S',
                'contabilidade' => 'S',
                'treinamento' => 'S',
                'informativos' => 'S',
                'regras' => 'S',
                'suporte' => 'S',
                'cadastro' => 'S',
                'clientes' => 'S',
            ];
        });
    }
}
