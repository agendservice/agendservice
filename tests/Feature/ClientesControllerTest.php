<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class ClientesControllerTest extends TestCase
{
    public function test_pode_buscar_historico_do_cliente_com_tipos_corretos()
    {
        $clienteId = 1;
        $response = $this->getJson("/api/clientes/{$clienteId}/historico");

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->each(fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'id' => 'integer',
                        'servico' => 'array',
                        'empresa' => 'array',
                        'dataHoraInicio' => 'string',
                        'status' => 'string',
                        'observacao' => 'string'
                    ])
                )
            );
    }
}
