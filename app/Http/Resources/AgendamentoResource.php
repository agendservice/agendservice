<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgendamentoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cliente_id' => $this->cliente_id,
            'funcionario_id' => $this->funcionario_id,
            'servico_id' => $this->servico_id,
            'data_hora_inicio' => $this->data_hora_inicio,
            'data_hora_fim' => $this->data_hora_fim,
            'status' => $this->status,
            'observacao' => $this->observacao,
            'cliente' => new ClienteResource($this->whenLoaded('cliente')),
            'funcionario' => new FuncionarioResource($this->whenLoaded('funcionario')),
            'servico' => new ServicoResource($this->whenLoaded('servico')),
            'criado_em' => $this->created_at,
            'atualizado_em' => $this->updated_at,
        ];
    }
}
