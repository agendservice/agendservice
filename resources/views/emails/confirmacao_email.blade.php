<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de E-mail - {{ config('app.name') }}</title>
    <style>
        /* Reset básico para garantir consistência entre clientes de email */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #eeefef; /* Sua cor 'branca' */
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            color: #333333;
        }
        .wrapper {
            width: 100%;
            background-color: #eeefef; /* Sua cor 'branca' */
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .header {
            background-color: #163c66; /* Sua cor 'azul' */
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
            text-align: left;
            line-height: 1.6;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            background-color: #ea5f4a; /* Sua cor 'laranja' */
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #d14d3a; /* Um laranja levemente mais escuro para hover */
        }
        .info-block {
            background-color: #f8f9fa;
            border-left: 4px solid #163c66; /* Azul na borda */
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
            font-size: 14px;
        }
        .info-row {
            margin-bottom: 8px;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }
        .label {
            font-weight: bold;
            color: #163c66; /* Azul no texto do label */
        }
        .footer {
            background-color: #f3f4f6;
            padding: 20px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
        }
        .link-fallback {
            font-size: 12px;
            color: #6b7280;
            margin-top: 20px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Cabeçalho Azul -->
            <div class="header">
                <!-- Se tiver logo, pode descomentar a linha abaixo -->
                <!-- <img src="https://seusite.com/logo-branca.png" alt="Logo" style="height: 40px; margin-bottom: 10px;"> -->
                <h1>Bem-vindo ao {{ config('app.name') }}!</h1>
            </div>
            
            <div class="content">
                <p style="font-size: 16px;">Olá, <strong>{{ $usuario->nome }}</strong>!</p>
                
                <p>Estamos muito felizes em ter você conosco. Seu cadastro foi recebido com sucesso.</p>
                
                <p>Para garantir a segurança da sua conta e liberar seu acesso, por favor, clique no botão abaixo para confirmar seu endereço de e-mail:</p>
                
                <!-- Botão Laranja -->
                <div class="btn-container">
                    <a href="{!! $link !!}" class="button" target="_blank">Confirmar meu E-mail</a>
                </div>

                <div class="info-block">
                    <div class="info-row">
                        <span class="label">Nome da Empresa:</span>
                        <span>{{ $empresa->nome_fantasia }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">CNPJ:</span>
                        <span>{{ $empresa->cnpj ?? 'Não informado' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">E-mail de acesso:</span>
                        <span>{{ $usuario->email }}</span>
                    </div>
                </div>

                <div class="link-fallback">
                    <p>Se o botão acima não funcionar, copie e cole o link abaixo no seu navegador:</p>
                    <a href="{!! $link !!}" style="color: #163c66;">{{ $link }}</a>
                </div>
            </div>
            
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
                <p>{{ $empresa->rua }}, {{ $empresa->numero }} - {{ $empresa->bairro }}, {{ $empresa->cidade }}/{{ $empresa->estado }}</p>
            </div>
        </div>
    </div>
</body>
</html>