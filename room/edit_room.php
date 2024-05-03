<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = ""; // Insira sua senha do banco de dados aqui
$dbname = "studentbeds";

// Estabelece uma conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão com o banco de dados
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Erro de conexão com o banco de dados: " . $conn->connect_error]);
    exit;
}

// Recebe os dados da solicitação PUT
$input = json_decode(file_get_contents('php://input'), true);

// Extrai os dados da solicitação
$id = isset($input['id']) ? intval($input['id']) : null;
$name = isset($input['name']) ? trim($input['name']) : null;
$university_id = isset($input['university_id']) ? intval($input['university_id']) : null;
$price = isset($input['price']) ? floatval($input['price']) : null;
$description = isset($input['description']) ? trim($input['description']) : null;
$status_id = isset($input['status_id']) ? intval($input['status_id']) : null;

// Verifica se todos os campos necessários estão presentes
if (is_null($id) || is_null($name) || is_null($university_id) || is_null($price) || is_null($description) || is_null($status_id)) {
    echo json_encode(["success" => false, "message" => "Alguns campos necessários estão faltando ou inválidos."]);
    exit;
}

// Prepara a consulta SQL para atualizar o quarto
$stmt = $conn->prepare("UPDATE room SET name = ?, university = ?, price = ?, description = ?, status = ? WHERE id = ?");
$stmt->bind_param("sidsii", $name, $university_id, $price, $description, $status_id, $id);

// Executa a consulta SQL
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Quarto editado com sucesso."]);
} else {
    echo json_encode(["success" => false, "message" => "Erro ao editar quarto: " . $stmt->error]);
}

// Fecha a declaração preparada
$stmt->close();

// Fecha a conexão com o banco de dados
$conn->close();
?>
