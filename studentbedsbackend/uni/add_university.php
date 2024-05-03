<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
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
$districtId = isset($input['district_id']) ? $input['district_id'] : null;

// Verifica se o district_id é um inteiro válido
if ($districtId !== null) {
    $districtId = filter_var($districtId, FILTER_VALIDATE_INT);
}

// Verifica se o nome e o ID do distrito são válidos
if ($name && $districtId !== false) {
    // Prepara a consulta SQL para inserir a nova universidade
    $stmt = $conn->prepare("INSERT INTO university (name, district) VALUES (?, ?)");
    $stmt->bind_param('si', $name, $districtId);

    // Executa a consulta SQL
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Universidade adicionada com sucesso."]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao adicionar universidade: " . $stmt->error]);
    }

    // Fecha a declaração preparada
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Nome ou ID do distrito inválido."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
