<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Agendamento;
use Carbon\Carbon;

class AgendamentoIntervalosRepository
{
    public function listarDoFuncionarioNoDia(int $funcionarioId, Carbon $inicio): array
    {
        $inicioDia = $inicio->copy()->startOfDay();
        $fimDia = $inicio->copy()->endOfDay();

        return Agendamento::query()
            ->where('funcionario_id', $funcionarioId)
            ->whereBetween('data_hora_inicio', [$inicioDia, $fimDia])
            ->get(['data_hora_inicio', 'data_hora_fim'])
            ->map(static fn (Agendamento $agendamento) => [
                'inicio' => $agendamento->data_hora_inicio,
                'fim' => $agendamento->data_hora_fim,
            ])
            ->all();
    }
}

