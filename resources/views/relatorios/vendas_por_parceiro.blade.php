<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas por Parceiro</title>
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
        .parceiro-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
            margin-bottom: 30px;
        }
        .parceiro-nome {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .parceiro-id {
            font-size: 14px;
            color: #666;
        }
        .metrics-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
        }
        .metric-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .metric-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .metric-value {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }
        .currency {
            color: #28a745;
        }
        .highlight {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }
        .highlight .metric-value {
            color: white;
        }
        .highlight .metric-label {
            color: #e3f2fd;
        }
        .details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .details h3 {
            margin-top: 0;
            color: #007bff;
        }
        .details p {
            margin: 8px 0;
            font-size: 14px;
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
        <h1>Relatório Individual de Vendas</h1>
        <p>Desempenho detalhado do parceiro</p>
    </div>

    <div class="parceiro-info">
        <div class="parceiro-nome">{{ $parceiro->nome }}</div>
        <div class="parceiro-id">ID do Parceiro: {{ $parceiro->id }}</div>
    </div>

    <div class="metrics-grid">
        <div class="metric-card highlight">
            <div class="metric-label">Total de Indicações</div>
            <div class="metric-value">{{ number_format($quantidade, 0, ',', '.') }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Valor Total Gerado</div>
            <div class="metric-value currency">R$ {{ number_format($valor_total, 2, ',', '.') }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Valor por Indicação</div>
            <div class="metric-value currency">R$ {{ number_format($valor_por_indicacao, 2, ',', '.') }}</div>
        </div>
    </div>

    <div class="details">
        <h3>Informações Adicionais</h3>
        <p><strong>Nome do Parceiro:</strong> {{ $parceiro->nome }}</p>
        <p><strong>Email:</strong> {{ $parceiro->email ?? 'Não informado' }}</p>
        <p><strong>Telefone:</strong> {{ $parceiro->telefone ?? 'Não informado' }}</p>
        <p><strong>Status:</strong> {{ $parceiro->status ?? 'Ativo' }}</p>
        <p><strong>Data de Cadastro:</strong> {{ $parceiro->created_at ? $parceiro->created_at->format('d/m/Y') : 'Não informado' }}</p>
    </div>

    <div class="footer">
        <p>Relatório gerado em {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        <p>MaisCredito - Sistema de Gestão de Indicações</p>
    </div>
</body>
</html>
