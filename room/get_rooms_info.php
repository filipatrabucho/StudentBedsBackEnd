<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Configurações de conexão com o banco de dados
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

// Consulta SQL para buscar os dados dos quartos
$sql = "
    SELECT
        room.id AS id,
        room.name AS name,
        university.name AS university_name,
        room.price AS price,
        room.description AS description,
        status.status AS status_name
    FROM
        room
        JOIN university ON room.university = university.id
        JOIN status ON room.status = status.id";

$result = $conn->query($sql);

if ($result) {
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
    echo json_encode(["success" => true, "rooms" => $rooms]);
} else {
    echo json_encode(["success" => false, "message" => "Erro ao obter dados dos quartos: " . $conn->error]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
