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

// Consulta para buscar a lista de quartos
$sql = "SELECT id, name FROM room";
$result = $conn->query($sql);

// Verifica se a consulta retornou resultados
if ($result && $result->num_rows > 0) {
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = [
            'id' => $row['id'],
            'name' => $row['name']
        ];
    }
    // Retorna a lista de quartos em formato JSON
    echo json_encode(["success" => true, "rooms" => $rooms]);
} else {
    echo json_encode(["success" => false, "message" => "Nenhum quarto encontrado."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
