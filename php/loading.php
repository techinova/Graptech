<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carregando - GrapTech</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f3f3;
        }
        .loading {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="loading">
        <h2>Carregando...</h2>
        <iframe src="https://giphy.com/embed/hWZBZjMMuMl7sWe0x8" width="480" height="360" frameBorder="0" allowFullScreen></iframe>
    </div>

    <script>
        setTimeout(function() {
            // Redireciona para a página de conclusão
            window.location.href = "conclusao.php"; // Substitua pelo caminho para a página de conclusão
        }, 7000); // 7 segundos
    </script>
</body>
</html>
