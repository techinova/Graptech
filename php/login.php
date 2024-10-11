<?php
// Inclui a conexão com o banco de dados
include 'conexao.php';

// Inicia a sessão
session_start();

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo_usuario = $_POST['tipo_usuario']; // Novo campo para diferenciar dono e usuário

    if ($tipo_usuario === 'dono') {
        // Login do dono
        $query = "SELECT * FROM usuarios WHERE email = ?";
    } else {
        // Login de usuário (funcionário)
        $query = "SELECT * FROM clt WHERE email = ?";
    }

    // Prepara a consulta
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o usuário existe
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Verifica se a senha está correta
        if (password_verify($senha, $usuario['senha'])) {
            // Armazena os dados do usuário na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome_completo'];

            if ($tipo_usuario === 'dono') {
                header("Location: dashboard.php"); // Redireciona para o painel do dono
            } else {
                header("Location: dash_user.php"); // Redireciona para o painel do usuário
            }
            exit();
        } else {
            echo "<script>alert('Senha incorreta!');</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado!');</script>";
    }
}

// Fecha a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GrapTech</title>
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>

    <div class="form-container">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <div class="form-group">
                <label>Login como:</label><br>
                <input type="radio" id="dono" name="tipo_usuario" value="dono" required>
                <label for="dono">Dono</label><br>
                <input type="radio" id="usuario" name="tipo_usuario" value="usuario" required>
                <label for="usuario">Usuário</label>
            </div>

            <button type="submit" class="btn-submit">Entrar</button>
        </form>
    </div>

</body>
</html>
