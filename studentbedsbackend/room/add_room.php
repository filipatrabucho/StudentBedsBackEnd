<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

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

// Recebe os dados da solicitação POST
$input = json_decode(file_get_contents('php://input'), true);
$name = isset($input['name']) ? trim($input['name']) : null;
$universityId = isset($input['university_id']) ? intval($input['university_id']) : null;
$price = isset($input['price']) ? floatval($input['price']) : null;
$description = isset($input['description']) ? trim($input['description']) : null;
$statusId = isset($input['status_id']) ? intval($input['status_id']) : null;

// Verifica se os dados são válidos
if ($name && $universityId && $price !== null && $statusId !== null) {
    // Prepara a consulta SQL para inserir a nova room com status
    $stmt = $conn->prepare("INSERT INTO room (name, university, price, description, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sidsi', $name, $universityId, $price, $description, $statusId);

    // Executa a consulta SQL
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "room adicionada com sucesso."]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao adicionar room: " . $stmt->error]);
    }

    // Fecha a declaração preparada
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Dados inválidos para adicionar room."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
