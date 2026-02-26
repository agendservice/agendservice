<!-- resources/views/emails/redefinicao_senha.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Envio de Placa</title>
    <style>
        /* Fundo do e-mail */
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Container do e-mail */
        .email-container {
            border-radius: 12px;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f3f4f6;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Cabeçalho com logo */
        .email-header {
            text-align: left;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .email-header img {
            max-width: 150px;
        }

        /* Conteúdo principal */
        .email-body {
            padding: 20px 0;
            text-align: center;
        }

        /* Botão de redefinição */
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Cabeçalho com logo -->
        <div class="email-header">
            <img src="https://hyppe.com.br/images/logo.png" alt="Logo da Empresa">
        </div>

        <!-- Corpo do e-mail -->
        <div class="email-body">
            <h1>Envio de Placa</h1>
            <p>Atenção! Uma nova placa foi conquistada.</p>
            <p>Afiliado: {{ $nome }}</p>
            <p>Telefone: {{ $telefone }}</p>
            <p>Endereço: {{ $endereco }}</p>
        </div>
    </div>
</body>
</html>
