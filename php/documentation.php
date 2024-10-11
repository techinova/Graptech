<?php
// documentacao.php

// Incluir o arquivo de conexão ao banco de dados
require_once 'conexao.php';

// Definindo o cabeçalho do HTML
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentação do Projeto GrapTech</title>
    <link rel="stylesheet" href="style.css"> <!-- Link para o seu arquivo CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1, h2, h3 {
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Documentação do Projeto GrapTech</h1>

    <h2>Visão Geral</h2>
    <p><strong>GrapTech</strong> é um serviço inovador desenvolvido para facilitar o gerenciamento do caixa de empresas, oferecendo uma interface amigável e funcionalidades robustas. O objetivo do GrapTech é proporcionar uma solução eficiente para o controle financeiro, permitindo que os proprietários de negócios tenham uma visão clara e precisa de suas operações diárias.</p>

    <h2>Funcionalidades Principais</h2>
    <ul>
        <li><strong>Gerenciamento de Vendas</strong>: Registro de vendas em tempo real, suporte a diferentes formas de pagamento (dinheiro e Pix) e geração de relatórios de vendas.</li>
        <li><strong>Cadastro de Usuários</strong>: Cadastro de múltiplos usuários e controle sobre quantos estão logados.</li>
        
        <li><strong>Relatórios de Vendas</strong>: Visualização de dados de vendas através de gráficos interativos e acompanhamento do fluxo de vendas.</li>
        <li><strong>Registro de Produtos</strong>: Adição e gerenciamento de produtos com informações detalhadas.</li>
    </ul>

    <h2>Aplicativo</h2>
    <p>O GrapTech também está em desenvolvimento para plataformas móveis, visando trazer a conveniência do gerenciamento de caixa para a palma da sua mão. O aplicativo permitirá que os usuários visualizem vendas em tempo real e acessem relatórios detalhados.</p>

    <h2>Conclusão</h2>
    <p>O GrapTech é uma solução completa para o gerenciamento de caixa, que visa simplificar a vida dos empreendedores e otimizar a gestão financeira.</p>

    <a href="/php/dashboard.php"><button>Voltar</button></a>
</div>

</body>
</html>
