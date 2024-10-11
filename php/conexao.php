<?php
$servername = "localhost";
$username = "root";
$password = "admin";
$database = "graptech_db";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $database);

// Checar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
