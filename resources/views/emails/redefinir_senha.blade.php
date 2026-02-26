<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de Senha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #4A5568; /* Cinza Escuro */
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .code-box {
            background-color: #f4f4f4;
            border-radius: 5px;
            padding: 15px 20px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 5px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f9f9f9;
            color: #888;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Redefinição de Senha</h1>
        </div>
        <div class="content">
            <p>Olá, {{ $nome }}!</p>
            <p>Recebemos uma solicitação para redefinir sua senha. Use o código de verificação abaixo para continuar. Se você não solicitou isso, pode ignorar este e-mail.</p>
            
            <p>Seu código de verificação é:</p>
            <div class="code-box">
                {{ $codigo }}
            </div>
            
            <p>Este código irá expirar em 24 horas.</p>
            <p>Atenciosamente,<br>Equipe {{ config('app.name') }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>