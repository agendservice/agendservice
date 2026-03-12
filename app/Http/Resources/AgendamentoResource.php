<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgendamentoResource extends JsonResource
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
            'indicacao_id' => $this->id,
            'mentorado_id' => $this->usuario->id ?? $this->mentorado->id ?? null,
            'nome' => $this->usuario->nome ?? $this->mentorado->nome ?? null,
            'tempo_espera' => $this->updated_at->locale('pt_BR')->diffForHumans(),
            'horario_inicio' => $this->horario,
            'data' => $this->data,
            'data_formatada' => date('d/m/Y', strtotime($this->data)),
            'horario_formatado' => date('H:i', strtotime($this->horario)),
            'telefone' => $this->usuario->telefone ?? $this->mentorado->telefone ?? null,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'mentor' => new UsuarioResource($this->whenLoaded('mentor')),
            'parceiro' => new UsuarioResource($this->whenLoaded('mentorado')),
            'link_mentoria' => $this->link_mentoria,
        ];
    }
}
