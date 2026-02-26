<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaResource extends JsonResource
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
            'usuario_id' => $this->usuario_id,
            'nome' => $this->nome,
            'categoria_pai_id' => $this->categoria_pai_id,
            'categoria_pai_nome' => $this->categoriaPai ? $this->categoriaPai['nome'] : null,
            'ordem' => $this->ordem,
            'status' => $this->status,
        ];
    }
}
