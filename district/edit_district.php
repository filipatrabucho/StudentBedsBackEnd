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

// Verifica se o método de requisição é PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Recebe os dados da solicitação PUT
    $input = json_decode(file_get_contents('php://input'), true);
    $id = isset($input['id']) ? intval($input['id']) : null;
    $name = isset($input['name']) ? trim($input['name']) : null;

    // Verifica se o ID e o nome são válidos
    if ($id && !empty($name)) {
        // Prepara a consulta SQL para atualizar o distrito
        $stmt = $conn->prepare("UPDATE district SET name = ? WHERE id = ?");
        $stmt->bind_param('si', $name, $id);

        // Executa a consulta SQL
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Distrito editado com sucesso."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao editar distrito: " . $stmt->error]);
        }

        // Fecha a declaração preparada
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "ID ou nome inválido."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método de requisição inválido. Use PUT."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
