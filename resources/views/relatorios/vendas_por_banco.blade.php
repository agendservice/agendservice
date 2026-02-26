<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas por Banco</title>
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
            border-left: 4px solid #007bff;
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
        .currency {
            color: #28a745;
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
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        .text-right {
            text-align: right;
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
        <h1>Relatório de Vendas por Banco</h1>
        <p>Distribuição de indicações por instituição bancária</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Total de Indicações</div>
            <div class="summary-value">{{ number_format($total_quantidade, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Valor Total</div>
            <div class="summary-value currency">R$ {{ number_format($total_valor, 2, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Bancos Ativos</div>
            {{-- A variável $vendas é uma coleção, então ->count() está correto aqui --}}
            <div class="summary-value">{{ $vendas->count() }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Instituição Bancária</th>
                <th class="text-right">Quantidade</th>
                <th class="text-right">Valor Total</th>
                <th class="text-right">% do Total</th>
            </tr>
        </thead>
        <tbody>
            {{-- O loop agora acessa os dados como um array --}}
            @foreach($vendas as $venda)
            <tr>
                <td>{{ $venda['banco'] ?: 'Não informado' }}</td>
                <td class="text-right">{{ number_format($venda['total_vendas'], 0, ',', '.') }}</td>
                <td class="text-right currency">R$ {{ number_format($venda['valor_total'], 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($venda['percentual'], 1, ',', '.') }}%</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #e9ecef;">
                <td>TOTAL GERAL</td>
                <td class="text-right">{{ number_format($total_quantidade, 0, ',', '.') }}</td>
                <td class="text-right currency">R$ {{ number_format($total_valor, 2, ',', '.') }}</td>
                <td class="text-right">100,0%</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Relatório gerado em {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        <p>MaisCredito - Sistema de Gestão de Indicações</p>
    </div>
</body>
</html>
