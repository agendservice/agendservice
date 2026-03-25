<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaFactory extends Factory
{
    public function definition()
    {
        return [
            'nome' => $this->faker->company(),
            'cnpj' => $this->faker->numerify('##.###.###/0001-##'),
            'endereco' => $this->faker->address(),
            'telefone' => $this->faker->phoneNumber(),
        ];
    }
}
