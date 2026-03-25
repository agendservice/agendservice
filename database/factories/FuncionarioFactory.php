<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class FuncionarioFactory extends Factory
{
    public function definition()
    {
        return [
            'empresa_id' => Empresa::factory(),
            'usuario_id' => null,
            'nome' => $this->faker->name(),
            'especialidade' => $this->faker->word(),
        ];
    }
}
