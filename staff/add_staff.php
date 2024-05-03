<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = ""; // Altere conforme necessário
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
$username = isset($input['username']) ? trim($input['username']) : null;
$password = isset($input['password']) ? trim($input['password']) : null;

// Verifica se os dados recebidos são válidos
if ($name !== null && $username !== null && $password !== null) {
    // Cria um hash seguro da senha usando password_hash()
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    
    // Prepara a consulta SQL para adicionar um novo registro à tabela staff
    $stmt = $conn->prepare("INSERT INTO staff (name, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $name, $username, $password_hash);

    // Executa a consulta SQL
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Funcionário adicionado com sucesso."]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao adicionar funcionário: " . $stmt->error]);
    }

    // Fecha a declaração preparada
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Dados inválidos para adicionar funcionário."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
