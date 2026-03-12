<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdicionaisResource extends JsonResource
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
            'preco' => $this->preco,
            'categoria_adicional' => $this->categoriaAdicional->nome_cat_adicional ?? null,
            'cat_add_id' => $this->cat_add_id,
            'cat_prod_id' => $this->cat_prod_id ?? null,
            'segunda' => $this->segunda ?? false,
            'terca' => $this->terca ?? false,
            'quarta' => $this->quarta ?? false,
            'quinta' => $this->quinta ?? false,
            'sexta' => $this->sexta ?? false,
            'sabado' => $this->sabado ?? false,
            'domingo' => $this->domingo ?? false,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
