<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentbeds";

// Estabelece conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão com o banco de dados
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Conexão falhou: " . $conn->connect_error]);
    exit;
}

// Consulta para buscar a lista de universidades
$sql = "SELECT id, name FROM university;";
$result = $conn->query($sql);

// Verifica se a consulta retornou resultados
if ($result && $result->num_rows > 0) {
    $universities = [];
    while ($row = $result->fetch_assoc()) {
        $universities[] = $row;
    }
    // Retorna a lista de universidades em formato JSON
    echo json_encode(["success" => true, "universities" => $universities]);
} else {
    echo json_encode(["success" => false, "message" => "Nenhuma universidade encontrada."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
