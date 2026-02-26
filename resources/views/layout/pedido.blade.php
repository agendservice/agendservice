<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumo do Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff; /* Branco para economizar tinta */
            color: #000; /* Preto para texto */
        }
        .container {
            width: 100%; /* 100% para garantir que se ajuste ao tamanho da página */
            margin: auto;
            overflow: hidden;
            padding: 20px; /* Adiciona espaçamento ao redor do conteúdo */
        }
        .content {
            border: 1px solid #ddd; /* Borda leve para definir o conteúdo */
            padding: 20px;
            border-radius: 5px;
        }
        h1, h2 {
            color: #000; /* Preto para títulos */
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            border-bottom: 1px solid #ddd; /* Linha fina para separar os títulos das seções */
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .section p {
            margin: 5px 0;
        }
        .section .label {
            font-weight: bold;
        }

        /* Estilos específicos para impressão */
        @media print {
            body {
                color: #000;
                background: #fff;
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="images/logo.png" width="30%" alt="Logo da Empresa">
    </header>
    <div class="container">
        <div class="content">
            <h1>Resumo do Pedido #{{$id}}</h1>
            
            <div class="section">
                <h2>Informações do Pedido</h2>
                <p><span class="label">Nome do Produto:</span> {{$produto['nome']}}</p>
                <p><span class="label">Quantidade:</span> {{$quantidade}}</p>
                <p><span class="label">Valor Total:</span> R$ {{number_format($valor_total, 2, ',', '.')}}</p>
                <p><span class="label">Observações:</span> {{$observacoes}}</p>
            </div>
            
            <div class="section">
                <h2>Endereço de Entrega</h2>
                <p><span class="label">Cidade:</span> {{$cidade['nome']}}</p>
                <p><span class="label">Endereço:</span> {{$endereco['endereco']}}</p>
                <p><span class="label">Número:</span> {{$endereco['numero']}}</p>
                @if($bairro)
                <p><span class="label">Bairro:</span> {{$bairro['nome']}}</p>
                @endif
                <p><span class="label">Complemento:</span> {{$endereco['complemento']}}</p>
                <p><span class="label">Horário para Entrega:</span> {{$turno}}</p>
                <p><span class="label">Data de Agendamento:</span> {{$data_agendamento}}</p>
            </div>

            <div class="section">
                <h2>Informações do Cliente</h2>
                <p><span class="label">Nome do Cliente:</span> {{$cliente['nome']}}</p>
                <p><span class="label">Telefone do Cliente:</span> {{$cliente['whatsapp']}}</p>
            </div>
        </div>
    </div>
</body>
</html>
