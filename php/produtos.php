<?php
require_once 'conexao.php';

// Supondo que você tenha o ID do usuário na sessão
session_start(); // Inicie a sessão se não estiver iniciada
$usuario_id = $_SESSION['usuario_id']; // Pega o ID do usuário logado

// Verifica se a requisição é um POST para adicionar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $preco = $_POST['preco'] ?? 0;
    $tipo = $_POST['tipo'] ?? '';
    $marca = $_POST['marca'] ?? '';

    // Adiciona um novo produto com o usuario_id
    $stmt = $conn->prepare("INSERT INTO produtos (codigo, nome, preco, tipo, marca, usuario_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdssi", $codigo, $nome, $preco, $tipo, $marca, $usuario_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
        $stmt->close(); // Fecha o statement
        exit(); // Sai após a resposta JSON para a requisição POST
    } else {
        echo json_encode(['success' => false, 'error' => 'Erro ao salvar produto: ' . $stmt->error]);
        $stmt->close();
        exit();
    }
}

// Verifica se a requisição é um DELETE para excluir
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'] ?? '';

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM produtos WHERE id=? AND usuario_id=?");
        $stmt->bind_param("ii", $id, $usuario_id); // Apenas o usuário pode excluir seus próprios produtos

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erro ao excluir produto: ' . $stmt->error]);
        }

        $stmt->close();
        exit(); // Sai após a resposta JSON para a requisição DELETE
    }
}

// Lê os produtos do banco de dados apenas do usuário logado
$stmt = $conn->prepare("SELECT * FROM produtos WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$produtos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="/css/produto.css">
</head>
<body>

<div class="navbar">
    <div class="logo">GrapTech</div>
    <a href="dashboard.php">Voltar</a>
</div>

<div class="container">
    <h1>Gestão de Produtos</h1>

    <!-- Botão para Adicionar Novo Produto -->
    <button class="btn" id="btnAdicionar" onclick="openPopup()">Adicionar Produto</button>

    <!-- Tabela de Produtos -->
    <div class="products-list" id="productsList">
        <?php foreach ($produtos as $produto): ?>
            <div class="product-item" data-id="<?php echo $produto['id']; ?>">
                <p><strong>Código:</strong> <?php echo $produto['codigo']; ?></p>
                <p><strong>Nome:</strong> <?php echo $produto['nome']; ?></p>
                <p><strong>Preço:</strong> R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                <p><strong>Tipo:</strong> <?php echo $produto['tipo']; ?></p>
                <p><strong>Marca:</strong> <?php echo $produto['marca']; ?></p>
                <button class="btn btn-delete" onclick="deleteProduct(<?php echo $produto['id']; ?>)">Excluir</button>
                <hr>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Pop-up para Adicionar Produto -->
<div class="popup" id="produtoPopup" style="display:none;">
    <div class="popup-content">
        <span class="close-btn" id="closePopup" onclick="closePopup()">&times;</span>
        <h2 id="popupTitle">Adicionar Produto</h2>
        <form id="produtoForm" method="POST" onsubmit="addProduct(event)">
            <label for="codigo">Código:</label>
            <input type="text" id="codigo" name="codigo" required>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>
            <label for="preco">Preço:</label>
            <input type="number" step="0.01" id="preco" name="preco" required>
            <label for="tipo">Tipo:</label>
            <input type="text" id="tipo" name="tipo" required>
            <label for="marca">Marca:</label>
            <input type="text" id="marca" name="marca" required>
            <button type="submit" class="btn">Salvar</button>
        </form>
    </div>
</div>

<script>
function openPopup() {
    document.getElementById('produtoPopup').style.display = 'block';
    document.getElementById('popupTitle').innerText = 'Adicionar Produto';
    document.getElementById('produtoForm').reset();
}

function closePopup() {
    document.getElementById('produtoPopup').style.display = 'none';
}

function addProduct(event) {
    event.preventDefault(); // Impede o comportamento padrão do formulário
    const formData = new FormData(document.getElementById('produtoForm'));

    fetch('produtos.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Produto adicionado com sucesso!');
            closePopup();
            location.reload(); // Recarrega a página para atualizar a lista de produtos
        } else {
            alert(data.error);
        }
    })
    .catch(error => alert('Erro ao adicionar produto: ' + error));
}

function deleteProduct(id) {
    if (confirm('Tem certeza que deseja excluir este produto?')) {
        fetch('produtos.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const productItem = document.querySelector(`.product-item[data-id='${id}']`);
                productItem.remove();
                alert('Produto excluído com sucesso.');
            } else {
                alert(data.error);
            }
        });
    }
}
</script>

</body>
</html>
