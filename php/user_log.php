<?php
require_once 'conexao.php';

// Iniciar a sessão
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Pegar o ID do dono logado
$dono_id = $_SESSION['usuario_id'];

// Adicionar um novo usuário
if (isset($_POST['add_user'])) {
    $nome_completo = $_POST['nome_completo'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografar a senha

    // Verificar o limite de usuários
    $query = "SELECT COUNT(*) AS total FROM clt WHERE dono_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $dono_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data['total'] < 3) { // Limite de 3 usuários
        $insert_query = "INSERT INTO clt (dono_id, nome_completo, cpf, telefone, email, senha) VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("isssss", $dono_id, $nome_completo, $cpf, $telefone, $email, $senha);
        
        if ($insert_stmt->execute()) {
            echo "Usuário adicionado com sucesso!";
        } else {
            echo "Erro ao adicionar usuário: " . $conn->error;
        }
    } else {
        echo "Você já atingiu o limite de usuários!";
    }
}

// Excluir um usuário
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM clt WHERE id = ? AND dono_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("ii", $delete_id, $dono_id);
    
    if ($delete_stmt->execute()) {
        echo "Usuário excluído com sucesso!";
    } else {
        echo "Erro ao excluir usuário: " . $conn->error;
    }
}

// Listar usuários logados
$query = "SELECT * FROM clt WHERE dono_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $dono_id);
$stmt->execute();
$users = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários Logados</title>
    <link rel="stylesheet" href="/css/user_log.css">
    <style>
        .online {
            border: 2px solid green;
            padding: 10px;
        }
        .offline {
            border: 2px solid red;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .back-button {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Usuários Logados</h1>
        <form method="POST">
            <input type="text" name="nome_completo" placeholder="Nome Completo" required>
            <input type="text" name="cpf" placeholder="CPF" required>
            <input type="text" name="telefone" placeholder="Telefone" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit" name="add_user">Adicionar Usuário</button>
        </form>
        
        <div class="border">
            <h2>Usuários Cadastrados</h2>
            <table>
                <tr>
                    <th>Nome Completo</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
                <?php while ($user = $users->fetch_assoc()): 
                    // Verificar se o usuário está logado com base na sessão
                    $status_class = isset($_SESSION['clt_user_id']) && $_SESSION['clt_user_id'] == $user['id'] ? 'online' : 'offline';
                    $status_text = $status_class == 'online' ? 'Online' : 'Deslogado';
                ?>
                <tr class="<?php echo $status_class; ?>">
                    <td><?php echo htmlspecialchars($user['nome_completo']); ?></td>
                    <td><?php echo htmlspecialchars($user['cpf']); ?></td>
                    <td><?php echo htmlspecialchars($user['telefone']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo $status_text; ?></td>
                    <td><a href="?delete_id=<?php echo $user['id']; ?>">Excluir</a></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <div class="back-button">
            <a href="/php/dashboard.php"><button class="btn">Voltar para a Dashboard</button></a>
        </div>
    </div>
</body>
</html>
