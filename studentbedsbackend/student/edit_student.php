<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
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

// Recebe os dados da solicitação PUT
$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? intval($input['id']) : null;
$name = isset($input['name']) ? trim($input['name']) : null;
$university_id = isset($input['university_id']) ? intval($input['university_id']) : null;
$tel = isset($input['tel']) ? trim($input['tel']) : null;
$nif = isset($input['nif']) ? trim($input['nif']) : null;

// Verifica se os dados recebidos são válidos
if ($id !== null && $name !== null && $university_id !== null && $tel !== null && $nif !== null) {
    // Prepara a consulta SQL para atualizar os dados do estudante
    $stmt = $conn->prepare("UPDATE student SET name = ?, phone = ?, nif = ?, uni = ? WHERE id = ?");
    $stmt->bind_param('siiii', $name, $tel, $nif, $university_id, $id);

    // Executa a consulta SQL
    if ($stmt->execute()) {
        // Verifica se alguma linha foi atualizada
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Estudante editado com sucesso."]);
        } else {
            echo json_encode(["success" => false, "message" => "Nenhuma mudança realizada."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao editar estudante: " . $stmt->error]);
    }

    // Fecha a declaração preparada
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Dados inválidos para editar estudante."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
