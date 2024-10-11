<?php
session_start();
require_once 'conexao.php'; // Conectando ao banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Obtém os dados do usuário
$usuario_id = $_SESSION['usuario_id'];
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

// Processa o formulário quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_completo = $_POST['nome_completo'];
    $idade = $_POST['idade'];
    $cpf_cnpj = $_POST['cpf_cnpj'];
    $numero_usuarios = $_POST['numero_usuarios'];
    $numero_cartao = $_POST['numero_cartao'];
    $cvc = $_POST['cvc'];
    $vencimento = $_POST['vencimento'];
    $email = $_POST['email'];

    // Atualiza os dados no banco de dados
    $stmt = $conn->prepare("UPDATE usuarios SET nome_completo = ?, idade = ?, cpf_cnpj = ?, numero_usuarios = ?, numero_cartao = ?, cvc = ?, vencimento = ?, email = ? WHERE id = ?");
    $stmt->bind_param("siissisii", $nome_completo, $idade, $cpf_cnpj, $numero_usuarios, $numero_cartao, $cvc, $vencimento, $email, $usuario_id);

    if ($stmt->execute()) {
        echo "<script>alert('Dados atualizados com sucesso!');</script>";
        header("Location: usuario.php"); // Redireciona para a página do usuário
        exit();
    } else {
        echo "<script>alert('Erro ao atualizar os dados.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Dados - GrapTech</title>
    <link rel="stylesheet" href="/css/alter_dados.css"> <!-- Incluindo o CSS aqui -->
</head>
<body>

    <div class="navbar">
        <div class="logo">GrapTech</div>
        <a href="dashboard.php">Sair</a>
    </div>

    <div class="dashboard-container">
        <h1>Alterar Dados do Usuário</h1>
        <form action="" method="POST">
            <div class="user-info">
                <label for="nome_completo">Nome Completo:</label>
                <input type="text" name="nome_completo" id="nome_completo" value="<?php echo htmlspecialchars($usuario['nome_completo']); ?>" required>
            </div>
            <div class="user-info">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="user-info">
                <label for="idade">Idade:</label>
                <input type="number" name="idade" id="idade" value="<?php echo htmlspecialchars($usuario['idade']); ?>" required>
            </div>
            <div class="user-info">
                <label for="cpf_cnpj">CPF/CNPJ:</label>
                <input type="text" name="cpf_cnpj" id="cpf_cnpj" value="<?php echo htmlspecialchars($usuario['cpf_cnpj']); ?>" required>
            </div>
            <div class="user-info">
                <label for="numero_usuarios">Número de Usuários:</label>
                <input type="number" name="numero_usuarios" id="numero_usuarios" value="<?php echo htmlspecialchars($usuario['numero_usuarios']); ?>" required>
            </div>
            <div class="user-info">
                <label for="numero_cartao">Número do Cartão:</label>
                <input type="text" name="numero_cartao" id="numero_cartao" value="<?php echo htmlspecialchars($usuario['numero_cartao']); ?>" required>
            </div>
            <div class="user-info">
                <label for="cvc">CVC:</label>
                <input type="text" name="cvc" id="cvc" value="<?php echo htmlspecialchars($usuario['cvc']); ?>" required>
            </div>
            <div class="user-info">
                <label for="vencimento">Vencimento:</label>
                <input type="date" name="vencimento" id="vencimento" value="<?php echo htmlspecialchars($usuario['vencimento']); ?>" required>
            </div>
            <button type="submit" class="btn">Salvar Alterações</button>
        </form>
    </div>

</body>
</html>
