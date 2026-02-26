<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Metas por Parceiro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            text-align: center;
        }
        .summary-item {
            border-left: 4px solid #28a745;
            padding-left: 15px;
        }
        .summary-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        .text-center {
            text-align: center;
        }
        .ranking {
            background: linear-gradient(135deg, #ffc107, #ffb300);
            color: white;
            font-weight: bold;
        }
        .ranking-1 {
            background: linear-gradient(135deg, #ffd700, #ffb300);
        }
        .ranking-2 {
            background: linear-gradient(135deg, #c0c0c0, #a9a9a9);
        }
        .ranking-3 {
            background: linear-gradient(135deg, #cd7f32, #8b4513);
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Metas por Parceiro</h1>
        <p>Ranking de metas concluídas</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Total de Metas Concluídas</div>
            <div class="summary-value">{{ number_format($total_metas, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Parceiros Ativos</div>
            <div class="summary-value">{{ number_format($total_parceiros, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Média por Parceiro</div>
            <div class="summary-value">{{ $total_parceiros > 0 ? number_format($total_metas / $total_parceiros, 1, ',', '.') : '0,0' }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">Ranking</th>
                <th>Nome do Parceiro</th>
                <th class="text-center">Metas Concluídas</th>
                <th class="text-center">% do Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($metas as $index => $meta)
            <tr class="{{ $index < 3 ? 'ranking ranking-' . ($index + 1) : '' }}">
                <td class="text-center">
                    @if($index == 0)
                        🥇 1º
                    @elseif($index == 1)
                        🥈 2º
                    @elseif($index == 2)
                        🥉 3º
                    @else
                        {{ $index + 1 }}º
                    @endif
                </td>
                <td>{{ $meta->user->nome ?? 'Usuário não encontrado' }}</td>
                <td class="text-center">{{ number_format($meta->metas_concluidas, 0, ',', '.') }}</td>
                <td class="text-center">{{ $total_metas > 0 ? number_format(($meta->metas_concluidas / $total_metas) * 100, 1, ',', '.') : '0,0' }}%</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #e9ecef;">
                <td colspan="2" class="text-center">TOTAL GERAL</td>
                <td class="text-center">{{ number_format($total_metas, 0, ',', '.') }}</td>
                <td class="text-center">100,0%</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Relatório gerado em {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        <p>MaisCredito - Sistema de Gestão de Indicações</p>
    </div>
</body>
</html>
