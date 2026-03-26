<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\AgendamentoService;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AgendamentoServiceTest extends TestCase
{
    public function test_calcular_fim_soma_minutos()
    {
        $service = new AgendamentoService();
        $inicio = new DateTimeImmutable('2026-01-01 10:00:00');

        $fim = $service->calcularFim($inicio, 45);

        $this->assertSame('2026-01-01 10:45:00', $fim->format('Y-m-d H:i:s'));
    }

    public function test_calcular_fim_rejeita_duracao_invalida()
    {
        $service = new AgendamentoService();
        $inicio = new DateTimeImmutable('2026-01-01 10:00:00');

        $this->expectException(InvalidArgumentException::class);
        $service->calcularFim($inicio, 0);
    }

    public function test_existe_sobreposicao_retorna_false_quando_apenas_encosta()
    {
        $service = new AgendamentoService();

        $novoInicio = new DateTimeImmutable('2026-01-01 10:00:00');
        $novoFim = new DateTimeImmutable('2026-01-01 11:00:00');

        $intervalos = [
            [
                'inicio' => new DateTimeImmutable('2026-01-01 09:00:00'),
                'fim' => new DateTimeImmutable('2026-01-01 10:00:00'),
            ],
            [
                'inicio' => new DateTimeImmutable('2026-01-01 11:00:00'),
                'fim' => new DateTimeImmutable('2026-01-01 12:00:00'),
            ],
        ];

        $this->assertFalse($service->existeSobreposicao($novoInicio, $novoFim, $intervalos));
    }

    public function test_existe_sobreposicao_retorna_true_quando_sobrepoe()
    {
        $service = new AgendamentoService();

        $novoInicio = new DateTimeImmutable('2026-01-01 10:00:00');
        $novoFim = new DateTimeImmutable('2026-01-01 11:00:00');

        $intervalos = [
            [
                'inicio' => new DateTimeImmutable('2026-01-01 10:30:00'),
                'fim' => new DateTimeImmutable('2026-01-01 11:30:00'),
            ],
        ];

        $this->assertTrue($service->existeSobreposicao($novoInicio, $novoFim, $intervalos));
    }

    public function test_existe_sobreposicao_rejeita_intervalo_sem_chaves()
    {
        $service = new AgendamentoService();

        $novoInicio = new DateTimeImmutable('2026-01-01 10:00:00');
        $novoFim = new DateTimeImmutable('2026-01-01 11:00:00');

        $this->expectException(InvalidArgumentException::class);
        $service->existeSobreposicao($novoInicio, $novoFim, [['inicio' => new DateTimeImmutable('2026-01-01 10:30:00')]]);
    }

    public function test_existe_sobreposicao_rejeita_intervalo_com_tipos_invalidos()
    {
        $service = new AgendamentoService();

        $novoInicio = new DateTimeImmutable('2026-01-01 10:00:00');
        $novoFim = new DateTimeImmutable('2026-01-01 11:00:00');

        $this->expectException(InvalidArgumentException::class);
        $service->existeSobreposicao($novoInicio, $novoFim, [['inicio' => '2026-01-01 10:30:00', 'fim' => '2026-01-01 11:30:00']]);
    }
}
