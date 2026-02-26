<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArquivosResource extends JsonResource
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
            'nome_original' => $this->nome_original,
            'public_url' => $this->public_url,
            'tipo_documento' => $this->tipo_documento,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'status' => $this->status,
            'rejeicao_motivo' => $this->rejeicao_motivo,
        ];
    }
}
