<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório - Ranking de Mentores</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 20px; text-align: center; color: #2563eb; border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 5px; }
        h2 { font-size: 16px; margin-top: 30px; color: #333; }
        p.subtitle { text-align: center; color: #666; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        thead { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: center; font-size: 10px; color: #888; }
        .info-card { background: #f8f9fa; padding: 20px; border-left: 4px solid #2563eb; margin-top: 20px; }
        .info-card strong { font-size: 24px; }
    </style>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 20px; text-align: center; color: #2563eb; border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 5px; }
        p.subtitle { text-align: center; color: #666; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        thead { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: center; font-size: 10px; color: #888; }
    </style>
</head>
<body>
    <div class="footer">
        {{ config('app.name') }} | Gerado em {{ now()->format('d/m/Y H:i') }}
    </div>

    <h1>Ranking de Mentores por Conversões</h1>
    <p class="subtitle">Top 10 mentores com mais mentorias aprovadas.</p>

    <table>
        <thead>
            <tr>
                <th class="text-center">Ranking</th>
                <th>Mentor</th>
                <th class="text-center">Vendas Convertidas</th>
            </tr>
        </thead>
        <tbody>
            {{-- CORREÇÃO: Usamos a variável $ranking diretamente, que foi desempacotada pelo Laravel --}}
            @forelse($ranking as $index => $mentor)
                <tr>
                    <td class="text-center">{{ $index + 1 }}º</td>
                    <td>{{ $mentor->nome }}</td>
                    <td class="text-center">{{ $mentor->conversoes }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Nenhum dado encontrado para este período.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>