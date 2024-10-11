
<?php
// Inclui a conexão com o banco de dados
include 'conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $idade = $_POST['idade'];
    $cpf_cnpj = $_POST['cpf_cnpj'];
    $usuarios = $_POST['usuarios'];
    $cartao = $_POST['cartao'];
    $cvc = $_POST['cvc'];
    $vencimento = $_POST['vencimento'] . '-01'; // Adiciona '-01' para garantir o formato correto
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hash da senha

    // Query de inserção
    $sql = "INSERT INTO usuarios (nome_completo, idade, cpf_cnpj, numero_usuarios, numero_cartao, cvc, vencimento, email, senha) 
            VALUES ('$nome', '$idade', '$cpf_cnpj', '$usuarios', '$cartao', '$cvc', '$vencimento', '$email', '$senha')";

$query_cpf = "SELECT * FROM usuarios WHERE cpf_cnpj = '$cpf_cnpj'";
$result_cpf = $conn->query($query_cpf);
if ($result_cpf->num_rows > 0) {
    echo "<script>alert('CPF ou CNPJ já cadastrado!');</script>";
    exit;
}

// Verifica se o Número do Cartão já está cadastrado
$query_cartao = "SELECT * FROM usuarios WHERE numero_cartao = '$cartao'";
$result_cartao = $conn->query($query_cartao);
if ($result_cartao->num_rows > 0) {
    echo "<script>alert('Número do Cartão já cadastrado!');</script>";
    exit;
}

// Verifica se o CVC já está cadastrado
$query_cvc = "SELECT * FROM usuarios WHERE cvc = '$cvc'";
$result_cvc = $conn->query($query_cvc);
if ($result_cvc->num_rows > 0) {
    echo "<script>alert('CVC já cadastrado!');</script>";
    exit;
}

// Verifica se o Email já está cadastrado
$query_email = "SELECT * FROM usuarios WHERE email = '$email'";
$result_email = $conn->query($query_email);
if ($result_email->num_rows > 0) {
    echo "<script>alert('Email já cadastrado!');</script>";
    exit;
}

// Se todas as verificações passarem, insere os dados no banco de dados
$sql = "INSERT INTO usuarios (nome_completo, idade, cpf_cnpj, numero_usuarios, numero_cartao, cvc, vencimento, email, senha) 
        VALUES ('$nome', '$idade', '$cpf_cnpj', '$usuarios', '$cartao', '$cvc', '$vencimento', '$email', '$senha')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Cadastro realizado com sucesso!');</script>";
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}
header("Location: loading.php");

// Fecha a conexão
$conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - GrapTech</title>
    <link rel="stylesheet" href="/css/cadastro.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Navbar Section -->
    <nav class="navbar">
        <div class="logo">GrapTech</div>
        <div>
            <a href="index.php">Home</a>
            <a href="#sobre">Sobre</a>
            <a href="#login">Login</a>
        </div>
    </nav>

    <!-- Formulário de Cadastro -->
    <div class="form-container">
        <h2>Cadastro de Usuário</h2>
        <form action="cadastro.php" method="post">
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="idade">Idade:</label>
                <input type="number" id="idade" name="idade" required>
            </div>

            <div class="form-group">
                <label for="cpf_cnpj">CPF ou CNPJ:</label>
                <input type="text" id="cpf_cnpj" name="cpf_cnpj" required>
            </div>

            <div class="form-group">
                <label for="usuarios">Número de Usuários:</label>
                <input type="number" id="usuarios" name="usuarios" required>
            </div>

            <div class="form-group">
                <label for="cartao">Número do Cartão:</label>
                <input type="text" id="cartao" name="cartao" required>
            </div>

            <div class="form-group">
                <label for="cvc">CVC:</label>
                <input type="number" id="cvc" name="cvc" required>
            </div>

            <div class="form-group">
                <label for="vencimento">Data de Vencimento:</label>
                <input type="month" id="vencimento" name="vencimento" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <button type="submit" class="btn-submit">Cadastrar</button>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>
