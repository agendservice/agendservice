<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComissoesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'numero_solicitacao' => $this->numero_solicitacao,
            'valor_total' => $this->valor_total,
            'valor_indicacao_direta' => $this->valor_indicacao_direta,
            'valor_subindicacoes' => $this->valor_subindicacoes,
            'quantidade_indicacao_direta' => $this->quantidade_indicacao_direta,
            'quantidade_subindicacoes' => $this->quantidade_subindicacoes,
            'status' => $this->status,
            'status_formatado' => $this->getStatusFormatado(),
            'observacoes' => $this->observacoes,
            'arquivo_nota_fiscal' => $this->arquivo_nota_fiscal ?? null, // Compatibilidade
            'arquivo_comprovante_id' => $this->arquivo_comprovante_id ?? null, // Compatibilidade
            'arquivo_nota' => $this->whenLoaded('arquivoNota', function() {
                return [
                    'id' => $this->arquivoNota->id,
                    'nome_original' => $this->arquivoNota->nome_original,
                    'public_url' => $this->arquivoNota->public_url,
                ];
            }),
            'arquivo_comprovante' => $this->whenLoaded('arquivoComprovante', function() {
                return [
                    'id' => $this->arquivoComprovante->id,
                    'nome_original' => $this->arquivoComprovante->nome_original,
                    'public_url' => $this->arquivoComprovante->public_url,
                ];
            }),
            'data_solicitacao' => $this->data_solicitacao ? $this->data_solicitacao->format('d/m/Y H:i') : null,
            'data_pagamento' => $this->data_pagamento ? $this->data_pagamento->format('d/m/Y H:i') : null,
            'data_confirmacao' => $this->data_confirmacao ? $this->data_confirmacao->format('d/m/Y H:i') : null,
            'dados_calculo' => $this->dados_calculo,
            'usuario' => [
                'id' => $this->usuario->id,
                'nome' => $this->usuario->nome,
                'email' => $this->usuario->email,
            ],
            'created_at' => $this->created_at ? $this->created_at->format('d/m/Y H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('d/m/Y H:i:s') : null,
        ];
    }

    private function getStatusFormatado()
    {
        $statusMap = [
            'nota_pendente' => 'Nota Fiscal Pendente',
            'pendente' => 'Aguardando Aprovação',
            'paga' => 'Paga',
            'confirmada' => 'Confirmada',
            'rejeitada' => 'Rejeitada'
        ];

        return $statusMap[$this->status] ?? $this->status;
    }
}
