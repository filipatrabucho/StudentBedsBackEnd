<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = ""; // Insira a senha do banco de dados aqui
$dbname = "studentbeds";

// Estabelece conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão com o banco de dados
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Conexão falhou: " . $conn->connect_error]);
    exit;
}

// Consulta SQL para buscar as informações das universidades
$sql = "
    SELECT
        university.id AS id,
        university.name AS name,
        district.name AS district_name
    FROM
        university
        JOIN district ON university.district = district.id
";

$result = $conn->query($sql);

if ($result) {
    $universities = [];
    while ($row = $result->fetch_assoc()) {
        $universities[] = $row;
    }
    echo json_encode(["success" => true, "universities" => $universities]);
} else {
    echo json_encode(["success" => false, "message" => "Erro ao obter dados das universidades: " . $conn->error]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
