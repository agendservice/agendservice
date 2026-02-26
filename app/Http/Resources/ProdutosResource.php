<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProdutosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'categoria_nome' => $this->categoria ? $this->categoria->nome : null,
            'nome' => $this->nome,  
            'imagem_id' => $this->imagem_id,
            'imagem_url' => $this->imagemProduto ? $this->imagemProduto->url : null,
            'status' => $this->status,
        ];
    }
    private function formatarPreco($preco)
    {
        return number_format($preco, 2, ',', '.');
    }
    private function formatarValorInicial($valor_inicial)
    {
        return number_format($valor_inicial, 0, ',', '.');
    }
}
