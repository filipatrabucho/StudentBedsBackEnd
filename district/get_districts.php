<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentbeds";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Conexão falhou: " . $conn->connect_error]);
    exit;
}

// Consulta para buscar a lista de districts
$sql = "SELECT id, name FROM district";
$result = $conn->query($sql);

// Verifica se a consulta retornou resultados
if ($result && $result->num_rows > 0) {
    $districts = [];
    while ($row = $result->fetch_assoc()) {
        $districts[] = $row;
    }
    // Retorna a lista de districts em formato JSON
    echo json_encode(["success" => true, "districts" => $districts]);
} else {
    echo json_encode(["success" => false, "message" => "Nenhum district encontrado."]);
}

// Fecha a conexão
$conn->close();
?>
