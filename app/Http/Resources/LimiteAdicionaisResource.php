<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LimiteAdicionaisResource extends JsonResource
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
            'nome_produto' => $this->produto->nome,
            'nome_categoria_produto' => $this->categoriaProduto->nome,
            'nome_categoria_adicional' => $this->categoriaAdicional->nome_cat_adicional,
            'cat_prod_id' => $this->cat_prod_id,
            'cobrar_no_limite' => $this->cobrar_no_limite,
            'permite_exceder' => $this->permite_exceder,
            'produto_id' => $this->produto_id,
            'cat_adicional_id' => $this->cat_adicional_id,
            'limite' => $this->limite,
        ];
    }
}
