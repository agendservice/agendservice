<?php
// database/factories/IndicacaoFactory.php
namespace Database\Factories;

use App\Models\Meta;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class IndicacaoFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Por padrão, cria uma nova Meta (que por sua vez cria um Parceiro)
            'meta_id' => Meta::factory(),
            'indicador_id' => Usuario::factory(),
            'indicado_id' => Usuario::factory(),
            'status' => 'ativa', // ou um status padrão
        ];
    }
}