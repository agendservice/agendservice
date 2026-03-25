<?php

declare(strict_types=1);

namespace App\Services;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

class AgendamentoService
{
    public function calcularFim(DateTimeInterface $inicio, int $duracaoMinutos): DateTimeImmutable
    {
        if ($duracaoMinutos < 1) {
            throw new InvalidArgumentException('A duração deve ser maior ou igual a 1 minuto.');
        }

        $inicioImmutable = $inicio instanceof DateTimeImmutable
            ? $inicio
            : DateTimeImmutable::createFromInterface($inicio);

        return $inicioImmutable->add(new DateInterval('PT' . $duracaoMinutos . 'M'));
    }

    public function existeSobreposicao(DateTimeInterface $novoInicio, DateTimeInterface $novoFim, array $intervalos): bool
    {
        $novoInicioTs = $novoInicio->getTimestamp();
        $novoFimTs = $novoFim->getTimestamp();

        foreach ($intervalos as $intervalo) {
            if (! isset($intervalo['inicio'], $intervalo['fim'])) {
                throw new InvalidArgumentException('Cada intervalo deve conter as chaves inicio e fim.');
            }

            if (! $intervalo['inicio'] instanceof DateTimeInterface || ! $intervalo['fim'] instanceof DateTimeInterface) {
                throw new InvalidArgumentException('Os valores de inicio e fim devem implementar DateTimeInterface.');
            }

            if ($intervalo['inicio']->getTimestamp() < $novoFimTs && $intervalo['fim']->getTimestamp() > $novoInicioTs) {
                return true;
            }
        }

        return false;
    }
}
