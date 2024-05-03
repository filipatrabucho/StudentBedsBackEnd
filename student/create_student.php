<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
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

// Recebe os dados da solicitação POST
$input = json_decode(file_get_contents('php://input'), true);
$name = isset($input['name']) ? trim($input['name']) : null;
$universityId = isset($input['university_id']) ? intval($input['university_id']) : null;
$tel = isset($input['tel']) ? trim($input['tel']) : null;
$nif = isset($input['nif']) ? trim($input['nif']) : null;

// Verifica se os dados são válidos
if ($name && $universityId && $tel && $nif) {
    // Prepara a consulta SQL para inserir um novo estudante
    $stmt = $conn->prepare("INSERT INTO student (name,phone,nif,uni) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('siss', $name,$tel, $nif, $universityId);

    // Executa a consulta SQL
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Estudante criado com sucesso."]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao criar estudante: " . $stmt->error]);
    }

    // Fecha a declaração preparada
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Dados inválidos para criar estudante."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
