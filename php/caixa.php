<?php
session_start();
require_once 'conexao.php';

// Inicializa as variáveis
$produtoEncontrado = null;
$total = 0;

// Certifique-se de que o usuario_id esteja na sessão
$usuarioId = $_SESSION['usuario_id'] ?? null; // Adicionando o usuario_id

// Verifica se a requisição é um POST para buscar produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Busca produto
    if (isset($_POST['nomeProduto'])) {
        $nomeProduto = $_POST['nomeProduto'] ?? '';

        // Busca o produto no banco de dados
        $stmt = $conn->prepare("SELECT * FROM produtos WHERE nome LIKE ?");
        $searchTerm = "%$nomeProduto%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        // Armazena o produto encontrado
        if ($result->num_rows > 0) {
            $produtoEncontrado = $result->fetch_assoc();
        }

        $stmt->close();
    }

    // Adiciona o produto ao carrinho
    if (isset($_POST['addToCart']) && !empty($_POST['produtoId'])) {
        $produtoId = $_POST['produtoId'];
        $produtoNome = $_POST['produtoNome'];
        $produtoPreco = $_POST['produtoPreco'];

        // Verifica se o carrinho já existe na sessão
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        // Adiciona o produto ao carrinho
        $_SESSION['carrinho'][] = [
            'id' => $produtoId,
            'nome' => $produtoNome,
            'preco' => $produtoPreco,
        ];

        // Redireciona para evitar reenvio de formulário
        header("Location: caixa.php");
        exit();
    }

    // Finaliza a compra e registra no banco de dados
    if (isset($_POST['finalizarCompra'])) {
        $cpf = $_POST['cpf'] ?? '';
        $formaPagamento = $_POST['formaPagamento'] ?? '';
        $valorPago = $_POST['valorPago'] ?? 0;

        // Grava cada produto vendido no banco de dados
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $item) {
                $stmt = $conn->prepare("INSERT INTO controle_vendas (nome_produto, valor_produto, data_venda, forma_pagamento, cpf_cliente, usuario_id) VALUES (?, ?, NOW(), ?, ?, ?)");
                $stmt->bind_param("sdssi", $item['nome'], $item['preco'], $formaPagamento, $cpf, $usuarioId); // Adicionando usuario_id
                $stmt->execute();
                $stmt->close();
            }

            // Limpa o carrinho após finalizar a compra
            unset($_SESSION['carrinho']);
        }

        // Redireciona para a página de carregamento
        header("Location: caixa.php");
        exit();
    }
}

// Calcula o total
if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $total += $item['preco'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixa</title>
    <link rel="stylesheet" href="/css/caixa.css">
    <script>
        function calcularTroco() {
            const valorPago = parseFloat(document.getElementById('valorPago').value);
            const total = parseFloat(document.getElementById('total').value);
            const troco = valorPago - total;
            document.getElementById('resultadoTroco').innerText = troco >= 0 ? `Troco: R$ ${troco.toFixed(2).replace('.', ',')}` : 'Valor insuficiente!';
        }

        function mostrarQRCode() {
            document.getElementById('qrcodeSection').style.display = 'block';
        }
    </script>
</head>
<body>

<div class="navbar">
    <div class="logo">GrapTech</div>
    <a href="/php/dashboard.php">Voltar</a>
</div>

<div class="container">
    <h1>Caixa</h1>

    <!-- Formulário para buscar produto -->
    <form method="POST" id="searchForm">
        <label for="nomeProduto">Nome do Produto:</label>
        <input type="text" id="nomeProduto" name="nomeProduto" required>
        <button type="submit" class="btn">Buscar</button>
    </form>

    <!-- Exibe informações do produto encontrado -->
    <?php if ($produtoEncontrado): ?>
        <div class="product-info" style="border: 1px solid #000; padding: 10px; margin-top: 20px; border-radius: 8px;">
            <h2>Informações do Produto</h2>
            <p><strong>Código:</strong> <?php echo $produtoEncontrado['codigo']; ?></p>
            <p><strong>Nome:</strong> <?php echo $produtoEncontrado['nome']; ?></p>
            <p><strong>Preço:</strong> R$ <?php echo number_format($produtoEncontrado['preco'], 2, ',', '.'); ?></p>
            <p><strong>Tipo:</strong> <?php echo $produtoEncontrado['tipo']; ?></p>
            <p><strong>Marca:</strong> <?php echo $produtoEncontrado['marca']; ?></p>

            <!-- Botão para adicionar produto ao carrinho -->
            <form method="POST" style="margin-top: 10px;">
                <input type="hidden" name="addToCart" value="1">
                <input type="hidden" name="produtoId" value="<?php echo $produtoEncontrado['id']; ?>">
                <input type="hidden" name="produtoNome" value="<?php echo $produtoEncontrado['nome']; ?>">
                <input type="hidden" name="produtoPreco" value="<?php echo $produtoEncontrado['preco']; ?>">
                <button type="submit" class="btn">Adicionar ao Carrinho</button>
            </form>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p>Nenhum produto encontrado.</p>
    <?php endif; ?>

    <!-- Exibe o carrinho e o total -->
    <div class="cart" style="margin-top: 20px; border: 1px solid #000; padding: 10px; border-radius: 8px;">
        <h2>Carrinho de Compras</h2>
        <?php if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])): ?>
            <ul>
                <?php foreach ($_SESSION['carrinho'] as $item): ?>
                    <li><?php echo $item['nome']; ?> - R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></li>
                <?php endforeach; ?>
            </ul>
            <h3>Total: R$ <span id="total"><?php echo number_format($total, 2, ',', '.'); ?></span></h3>
            <input type="hidden" id="total" value="<?php echo $total; ?>">
            <form method="POST" id="finalizarCompraForm">
                <label for="cpf">Deseja CPF na nota? (digite se sim):</label>
                <input type="text" id="cpf" name="cpf">
                <p>Forma de Pagamento:</p>
                <div>
                    <label><input type="radio" name="formaPagamento" value="pix" onclick="mostrarQRCode()"> Pix</label>
                    <label><input type="radio" name="formaPagamento" value="dinheiro" onclick="document.getElementById('trocoSection').style.display='block';"> Dinheiro</label>
                </div>
                <div id="trocoSection" style="display: none;">
                    <label for="valorPago">Valor Pago:</label>
                    <input type="text" id="valorPago" name="valorPago">
                    <button type="button" onclick="calcularTroco()">Calcular Troco</button>
                    <p id="resultadoTroco"></p>
                </div>
                <button type="submit" name="finalizarCompra" class="btn">Finalizar Compra</button>
            </form>
        <?php else: ?>
            <p>Carrinho vazio.</p>
        <?php endif; ?>
    </div>

    <!-- Se a forma de pagamento for Pix, exibe QR Code -->
    <div id="qrcodeSection" style="display: none;">
        <h2>QR Code para Pagamento</h2>
        <!-- Aqui você deve gerar e exibir o QR Code, utilizando a biblioteca de QR Code de sua escolha -->
    </div>
</div>

</body>
</html>
