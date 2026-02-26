<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas do Mês</title>
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
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
        }
        .info-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .currency {
            color: #28a745;
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
        <h1>Relatório de Vendas do Mês</h1>
        <p>Período: {{ \Carbon\Carbon::parse($mes)->locale('pt_BR')->isoFormat('MMMM [de] YYYY') }}</p>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <div class="info-label">Total de Indicações</div>
            <div class="info-value">{{ number_format($quantidade, 0, ',', '.') }}</div>
        </div>

        <div class="info-card">
            <div class="info-label">Valor Total</div>
            <div class="info-value currency">R$ {{ number_format($valor_total, 2, ',', '.') }}</div>
        </div>

        <div class="info-card">
            <div class="info-label">Valor por Indicação</div>
            <div class="info-value currency">R$ {{ number_format($valor_por_indicacao, 2, ',', '.') }}</div>
        </div>

        <div class="info-card">
            <div class="info-label">Mês de Referência</div>
            <div class="info-value">{{ $mes }}</div>
        </div>
    </div>

    <div class="footer">
        <p>Relatório gerado em {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        <p>MaisCredito - Sistema de Gestão de Indicações</p>
    </div>
</body>
</html>
