<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CupomResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'tipo_desconto' => $this->tipo_desconto,
            'valor_minimo' => $this->formatarPreco($this->valor_minimo),
            'valor_desconto' => $this->formatarPreco($this->valor_desconto),
            'data_inicio' => date('d/m/Y', strtotime($this->data_inicio)),
            'data_fim' => date('d/m/Y', strtotime($this->data_fim)),
            'qtd_utilizacao' => $this->qtd_utilizacao,
            'limitar_uso_por_cliente' => $this->limitar_uso_por_cliente,
            'visibilidade' => $this->visibilidade,
        ];
    }
    private function formatarPreco($preco)
    {
        return number_format($preco, 2, ',', '.');
    }
}
