<?php
session_start();
require_once 'conexao.php'; // Conectando ao banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Lógica para alterar a senha
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Verifica se as senhas coincidem
    if ($nova_senha === $confirmar_senha) {
        // Hasheando a nova senha
        $hash_senha = password_hash($nova_senha, PASSWORD_DEFAULT);
        $usuario_id = $_SESSION['usuario_id'];

        // Atualiza a senha no banco de dados
        $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        $stmt->bind_param("si", $hash_senha, $usuario_id);

        if ($stmt->execute()) {
            // Redireciona para a página do usuário após a alteração bem-sucedida
            header("Location: usuario.php");
            exit();
        } else {
            echo "<p>Erro ao alterar a senha. Tente novamente.</p>";
        }

        $stmt->close();
    } else {
        echo "<p>As senhas não coincidem. Tente novamente.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha - GrapTech</title>
    <link rel="stylesheet" href="/css/esqueci_senha.css"> <!-- Incluindo o CSS aqui -->
</head>
<body>

    <div class="navbar">
        <div class="logo">GrapTech</div>
        <a href="usuario.php">Voltar</a>
    </div>

    <div class="dashboard-container">
        <h1>Alterar Senha</h1>
        <form action="" method="POST">
            <div class="user-info">
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" id="nova_senha" name="nova_senha" required>
            </div>
            <div class="user-info">
                <label for="confirmar_senha">Confirmar Senha:</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            </div>
            <button type="submit" class="btn">Alterar Senha</button>
        </form>
    </div>

</body>
</html>
