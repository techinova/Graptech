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
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados do Usuário - GrapTech</title>
    <link rel="stylesheet" href="/css/usuario.css"> <!-- Incluindo o CSS aqui -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <div class="navbar">
        <div class="logo">GrapTech</div>
        <a href="/php/dashboard.php">Sair</a>
    </div>

    <div class="dashboard-container">
        <h1>Dados do Usuário</h1>
        <div class="user-card">
            <div class="user-info">
                <h2>Nome:</h2>
                <p><?php echo htmlspecialchars($usuario['nome_completo']); ?></p>
            </div>
            <div class="user-info">
                <h2>Email:</h2>
                <p><?php echo htmlspecialchars($usuario['email']); ?></p>
            </div>
            <div class="user-info">
                <h2>Idade:</h2>
                <p><?php echo htmlspecialchars($usuario['idade']); ?></p>
            </div>
            <div class="user-info">
                <h2>CPF/CNPJ:</h2>
                <p><?php echo htmlspecialchars($usuario['cpf_cnpj']); ?></p>
            </div>
            <div class="user-info">
                <h2>Usuários:</h2>
                <p><?php echo htmlspecialchars($usuario['numero_usuarios']); ?></p>
            </div>
            <div class="user-info">
                <h2>Número do Cartão:</h2>
                <p><?php echo htmlspecialchars($usuario['numero_cartao']); ?></p>
            </div>
            <div class="user-info">
                <h2>CVC:</h2>
                <p><?php echo htmlspecialchars($usuario['cvc']); ?></p>
            </div>
            <div class="user-info">
                <h2>Vencimento:</h2>
                <p><?php echo htmlspecialchars($usuario['vencimento']); ?></p>
            </div>

         

            
            <div class="actions">
                <a href="alterar_dados.php" class="btn">Alterar Dados</a>
                <a href="esqueci_senha.php" class="btn">Esqueci a Senha</a>
            </div>
        </div>
    </div>

</body>
</html>

