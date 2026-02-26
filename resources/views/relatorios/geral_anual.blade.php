<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório Geral Anual - {{ $ano }}</title>
    <style>
        /* Estilos unificados para um visual profissional */
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; margin: 20px; color: #333; }
        h1, h2 { color: #1e3a8a; }
        h1 { font-size: 22px; text-align: center; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px; margin-bottom: 5px; }
        h2 { font-size: 16px; margin-top: 25px; border-bottom: 1px solid #dbeafe; padding-bottom: 5px; }
        p.subtitle { text-align: center; color: #666; margin-top: 5px; font-size: 12px; }
        .section { margin-bottom: 25px; page-break-inside: avoid; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #e5e7eb; padding: 7px; text-align: left; }
        thead { background-color: #eff6ff; font-weight: bold; color: #1e3a8a; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { position: fixed; bottom: -20px; left: 0; right: 0; text-align: center; font-size: 9px; color: #9ca3af; }
        .summary-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-top: 20px; }
        .summary-card { background: #f9fafb; border: 1px solid #e5e7eb; padding: 15px; border-radius: 4px; text-align: center; }
        .summary-card .label { font-size: 10px; color: #6b7280; text-transform: uppercase; margin-bottom: 5px; }
        .summary-card .value { font-size: 20px; font-weight: bold; color: #1e3a8a; }
    </style>
</head>
<body>
    <div class="footer">
        {{ config('app.name') }} | Relatório Anual de {{ $ano }} | Gerado em {{ now()->format('d/m/Y H:i') }}
    </div>

    <h1>Relatório Geral Anual</h1>
    <p class="subtitle">Visão consolidada de todas as métricas do sistema para o ano de <strong>{{ $ano }}</strong></p>

    <div class="section">
        <h2>Resumo Geral do Ano</h2>
        <div class="summary-grid">
            <div class="summary-card">
                <div class="label">Total de Vendas</div>
                <div class="value">{{ number_format($vendas_por_banco['total_quantidade'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <div class="label">Valor Total Gerado</div>
                <div class="value">R$ {{ number_format($vendas_por_banco['total_valor'], 2, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <div class="label">Tempo Médio de Atendimento</div>
                <div class="value">{{ $tempo_medio_atendimento['tma_geral_minutos'] }} min</div>
            </div>
        </div>
    </div>
    
    <div class="section">
        <h2>🏆 Ranking de Mentores (Top 10 Conversores)</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Mentor</th>
                    <th class="text-center">Vendas Convertidas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ranking_mentores as $index => $mentor)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $mentor->nome }}</td>
                        <td class="text-center">{{ $mentor->conversoes }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">Nenhum dado de mentor encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>🥇 Ranking de Parceiros (Top 10 Participação)</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Parceiro</th>
                    <th class="text-center">Ind. Diretas</th>
                    <th class="text-center">Subindicações</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ranking_parceiros as $index => $parceiro)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $parceiro->nome }}</td>
                        <td class="text-center">{{ $parceiro->indicacoes_diretas_count }}</td>
                        <td class="text-center">{{ $parceiro->sub_indicacoes_count }}</td>
                        <td class="text-center"><strong>{{ $parceiro->total_participacoes }}</strong></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Nenhum dado de parceiro encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>🎯 Metas por Parceiro (Ranking)</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Parceiro</th>
                    <th class="text-center">Metas Concluídas</th>
                    <th class="text-center">% do Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($metas_por_parceiro['metas'] as $index => $meta)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $meta['user'] ? $meta['user']['nome'] : 'N/A' }}</td>
                        <td class="text-center">{{ $meta['metas_concluidas'] }}</td>
                        <td class="text-center">{{ $meta['percentual'] }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Nenhuma meta concluída registrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="section">
        <h2>🏦 Vendas por Banco</h2>
        <table>
            <thead>
                <tr>
                    <th>Instituição Bancária</th>
                    <th class="text-center">Total de Vendas</th>
                    <th class="text-right">Valor Total</th>
                    <th class="text-center">% do Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendas_por_banco['vendas'] as $venda)
                    <tr>
                        <td>{{ $venda['banco'] ?: 'Não informado' }}</td>
                        <td class="text-center">{{ number_format($venda['total_vendas'], 0, ',', '.') }}</td>
                        <td class="text-right">R$ {{ number_format($venda['valor_total'], 2, ',', '.') }}</td>
                        <td class="text-center">{{ $venda['percentual'] }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Nenhuma venda por banco registrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>