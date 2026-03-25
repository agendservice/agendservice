<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Cliente;
use App\Models\Agendamento;

class ClientesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_listar_clientes()
    {
        Cliente::factory()->create();
        $response = $this->getJson('/api/clientes');
        $response->assertStatus(200)->assertJsonCount(1, 'data');
    }

    public function test_pode_buscar_historico_do_cliente_com_tipos_corretos()
    {
        $agendamento = Agendamento::factory()->create();
        $clienteId = $agendamento->cliente_id;
        
        $response = $this->getJson("/api/clientes/{$clienteId}/historico");

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 1, fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'id' => 'integer',
                        'cliente_id' => 'integer',
                        'data_hora_inicio' => 'string',
                        'status' => 'string'
                    ])->etc()
                )
            );
    }
}
