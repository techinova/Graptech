<?php
require_once 'conexao.php'; // Certifique-se que o arquivo conexao.php está correto

// Função para calcular o total das vendas por dia
function totalVendasPorDia($conn, $dia) {
    $sql = "SELECT SUM(valor_produto) AS total_vendas FROM controle_vendas WHERE DATE(data_venda) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dia);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total_vendas'] ?? 0;
}

// Função para calcular o total das vendas por mês
function totalVendasPorMes($conn, $mes, $ano) {
    $sql = "SELECT SUM(valor_produto) AS total_vendas FROM controle_vendas WHERE MONTH(data_venda) = ? AND YEAR(data_venda) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $mes, $ano);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total_vendas'] ?? 0;
}

// Função para calcular o total das vendas por ano
function totalVendasPorAno($conn, $ano) {
    $sql = "SELECT SUM(valor_produto) AS total_vendas FROM controle_vendas WHERE YEAR(data_venda) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ano);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total_vendas'] ?? 0;
}

// Exemplo de uso:
$dia = date('Y-m-d'); // Data de hoje
$mes = date('m');
$ano = date('Y');

$totalDia = totalVendasPorDia($conn, $dia);
$totalMes = totalVendasPorMes($conn, $mes, $ano);
$totalAno = totalVendasPorAno($conn, $ano);

// Buscando dados para o gráfico
$query = "SELECT DATE(data_venda) AS data, SUM(valor_produto) AS total_vendas FROM controle_vendas GROUP BY DATE(data_venda)";
$result = $conn->query($query);

$graficoData = [];
while ($row = $result->fetch_assoc()) {
    $graficoData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluxo de Vendas</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Data', 'Total Vendido'],
                <?php
                foreach ($graficoData as $row) {
                    echo "['" . $row['data'] . "', " . $row['total_vendas'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Fluxo de Vendas',
                hAxis: {title: 'Data', titleTextStyle: {color: '#333'}},
                vAxis: {minValue: 0, title: 'Total Vendido'},
                legend: {position: 'none'},
                bar: {groupWidth: "20%"}, // Ajusta a largura das colunas
                colors: ['#007bff']
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .totais {
            margin-bottom: 20px;
        }
        .totais div {
            margin-bottom: 10px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="totais">
        <div>Total de vendas do dia: R$<?php echo number_format($totalDia, 2, ',', '.'); ?></div>
        <div>Total de vendas do mês: R$<?php echo number_format($totalMes, 2, ',', '.'); ?></div>
        <div>Total de vendas do ano: R$<?php echo number_format($totalAno, 2, ',', '.'); ?></div>
    </div>

    <div id="chart_div" style="width: 100%; height: 500px;"></div>

    <button onclick="window.location.href='/php/dashboard.php'">Voltar para a Dashboard</button>

</body>
</html>
