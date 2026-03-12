<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FormasPagamentoResource extends JsonResource
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
            'chave_pix' => $this->chave_pix,
            'dinheiro' => $this->dinheiro,
            'cartao_credito' => $this->cartao_credito,
            'cartao_debito' => $this->cartao_debito,
            'pix' => $this->pix,
        ];
    }
}
