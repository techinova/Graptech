<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GrapTech</title>
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <div class="dashboard-container">
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
        <div class="cards-container">

            <div class="card">
                <h2><i class="fas fa-user"></i> Dados do Usuário</h2>
                <p>Visualizar e editar informações do usuário</p>
                <a href="/php/usuario.php"><button class="btn">Ver Detalhes</button></a>
            </div>

            <div class="card">
                <h2><i class="fas fa-box"></i> Registrar Produtos</h2>
                <p>Cadastrar novos produtos</p>
                <a href="/php/produtos.php"><button class="btn">Ver Detalhes</button></a>
            </div>

            <div class="card">
                <h2><i class="fas fa-cash-register"></i> Caixa</h2>
                <p>Visualizar caixa</p>
                <a href="/php/caixa.php"><button class="btn">Ver Caixa</button></a>
            </div>

            <div class="card">
                <h2><i class="fas fa-sign-out-alt"></i> Sair</h2>
                <p>Deslogar Usuário</p>
                <a href="?logout=true"><button class="btn">Deslogar</button></a>
            </div>

        </div>
    </div>

</body>

</html>
