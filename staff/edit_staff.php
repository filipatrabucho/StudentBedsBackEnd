<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

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

// Recebe os dados da solicitação PUT
$input = json_decode(file_get_contents('php://input'), true);

// Verifica se os dados são válidos
if (isset($input['id']) && isset($input['name']) && isset($input['username']) && isset($input['password'])) {
    $id = intval($input['id']);
    $name = trim($input['name']);
    $username = trim($input['username']);
    $password = trim($input['password']);
    
    // Aplica hash à senha usando `password_hash`
    $passwordHashed = password_hash($password, PASSWORD_BCRYPT);
    
    // Prepara a consulta SQL para atualizar os dados do funcionário
    $stmt = $conn->prepare("UPDATE staff SET name = ?, username = ?, password = ? WHERE id = ?");
    $stmt->bind_param('sssi', $name, $username, $passwordHashed, $id);

    // Executa a consulta SQL
    if ($stmt->execute()) {
        // Verifica se alguma linha foi afetada
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Funcionário editado com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Nenhuma alteração foi feita."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao editar funcionário: " . $stmt->error]);
    }

    // Fecha a declaração preparada
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Dados inválidos para editar funcionário."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
