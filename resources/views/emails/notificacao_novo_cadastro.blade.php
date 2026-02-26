<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Cadastro - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #2563eb;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .info-block {
            background-color: #f9fafb;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
        }
        .info-row {
            margin: 10px 0;
        }
        .label {
            font-weight: bold;
            color: #374151;
        }
        .value {
            color: #6b7280;
            margin-left: 10px;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Novo Cadastro Realizado</h1>
        </div>
        
        <div class="content">
            <p>Olá, Administrador!</p>

            <p>Um novo usuário se cadastrou na plataforma <strong>{{ config('app.name') }}</strong>.</p>

            <div class="info-block">
                <h3 style="margin-top: 0; color: #2563eb;">Dados do Novo Usuário</h3>
                
                <div class="info-row">
                    <span class="label">Nome:</span>
                    <span class="value">{{ $usuario->nome }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">E-mail:</span>
                    <span class="value">{{ $usuario->email }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">CPF:</span>
                    <span class="value">{{ $usuario->cpf }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Telefone:</span>
                    <span class="value">{{ $usuario->telefone }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Data do Cadastro:</span>
                    <span class="value">{{ \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $usuario->created_at)->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>
                        
            <p style="color: #6b7280; font-size: 14px;">
                Este é um e-mail automático. Por favor, acesse o sistema para revisar e aprovar o cadastro.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
            <p>Este é um e-mail automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html>