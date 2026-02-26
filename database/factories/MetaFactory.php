<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class MetaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // Associa a um usuário existente ou cria um novo
            'user_id' => Usuario::factory(),
            
            // Gera um número de meta de exemplo
            'numero_meta' => 'META.' . $this->faker->numerify('#####.##.##/##'),
            
            'data_inicio' => now()->startOfMonth()->format('Y-m-d'),
            'status' => 'ativa',
            
            // Valores padrão baseados nos seus campos fillable
            'meta_cotas' => $this->faker->numberBetween(5, 20),
            'valor_cota_padrao' => $this->faker->randomElement([1500, 2000, 2500]),
            'comissao_venda_direta' => $this->faker->randomFloat(2, 5, 10),
        ];
    }
}