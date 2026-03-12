<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AcessosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'metas' => $this->metas,
            'vendas' => $this->vendas,
            'contabilidade' => $this->contabilidade,
            'treinamento' => $this->treinamento,
            'informativos' => $this->informativos,
            'regras' => $this->regras,
            'suporte' => $this->suporte,
            'cadastro' => $this->cadastro,
            'clientes' => $this->clientes,
        ];
    }
}
