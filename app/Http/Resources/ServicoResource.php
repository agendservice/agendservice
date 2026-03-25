<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServicoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'empresa_id' => $this->empresa_id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'duracao_minutos' => $this->duracao_minutos,
            'preco' => $this->preco,
            'empresa' => new EmpresaResource($this->whenLoaded('empresa')),
            'criado_em' => $this->created_at,
            'atualizado_em' => $this->updated_at,
        ];
    }
}
