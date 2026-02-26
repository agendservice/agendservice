<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromocaoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $status = 'ativo';
        if (date('Y-m-d') < $this->data_inicio || date('Y-m-d') > $this->data_fim) {
            $status = 'inativo';
        }
        return [
            'id' => $this->id,
            'empresa_id' => $this->empresa_id,
            'categoria_id' => $this->categoria_id,
            'categoria_nome' => $this->categoria ? $this->categoria->nome : null,
            'produto_nome' => $this->produto ? $this->produto->nome : null,
            'produto_id' => $this->produto_id,
            'valor_desconto' => $this->formatarPreco($this->valor_desconto),
            'data_inicio' => date('d/m/Y', strtotime($this->data_inicio)),
            'data_fim' => date('d/m/Y', strtotime($this->data_fim)),
            'limite_por_usuario' => $this->limite_por_usuario,
            'status' => $status,
        ];
    }
    private function formatarPreco($preco)
    {
        return number_format($preco, 2, ',', '.');
    }
}
