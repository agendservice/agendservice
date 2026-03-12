<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TamanhoResource extends JsonResource
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
            'usuario_id' => $this->usuario_id,
            'cat_prod_id' => $this->cat_prod_id,
            'nome' => $this->nome,
            'grupo_tamanhos_id' => $this->grupo_tamanhos_id,
            'grupo_tamanhos_nome' => $this->grupoTamanhos->nome ?? 'Não definido',
            'preco' => $this->formatarPreco($this->preco),
            'descricao' => $this->descricao,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function formatarPreco($preco)
    {
        return number_format($preco, 2, ',', '.');
    }
}
