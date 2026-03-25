<?php

namespace Tests\Unit;

use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Funcionario;
use App\Models\Servico;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cliente_relaciona_usuario_e_agendamentos()
    {
        $usuario = Usuario::factory()->create();
        $cliente = Cliente::factory()->create(['usuario_id' => $usuario->id]);

        Agendamento::factory()->count(2)->create(['cliente_id' => $cliente->id]);

        $this->assertInstanceOf(Usuario::class, $cliente->usuario);
        $this->assertCount(2, $cliente->agendamentos);
    }

    public function test_empresa_relaciona_servicos_e_funcionarios()
    {
        $empresa = Empresa::factory()->create();
        $servico = Servico::factory()->create(['empresa_id' => $empresa->id]);
        $funcionario = Funcionario::factory()->create(['empresa_id' => $empresa->id]);

        $this->assertTrue($empresa->servicos->contains($servico));
        $this->assertTrue($empresa->funcionarios->contains($funcionario));
    }

    public function test_funcionario_relaciona_empresa_usuario_e_agendamentos()
    {
        $empresa = Empresa::factory()->create();
        $usuario = Usuario::factory()->funcionario()->create();
        $funcionario = Funcionario::factory()->create(['empresa_id' => $empresa->id, 'usuario_id' => $usuario->id]);

        Agendamento::factory()->count(2)->create(['funcionario_id' => $funcionario->id]);

        $this->assertInstanceOf(Empresa::class, $funcionario->empresa);
        $this->assertInstanceOf(Usuario::class, $funcionario->usuario);
        $this->assertCount(2, $funcionario->agendamentos);
    }

    public function test_servico_relaciona_empresa_e_agendamentos()
    {
        $empresa = Empresa::factory()->create();
        $servico = Servico::factory()->create(['empresa_id' => $empresa->id]);

        Agendamento::factory()->count(2)->create(['servico_id' => $servico->id]);

        $this->assertInstanceOf(Empresa::class, $servico->empresa);
        $this->assertCount(2, $servico->agendamentos);
    }
}

