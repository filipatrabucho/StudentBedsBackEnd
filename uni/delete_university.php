<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
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

// Obtém o ID da universidade a ser excluída da URL
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Verifica se o ID é válido
if ($id) {
    // Prepara a consulta SQL para excluir a universidade
    $stmt = $conn->prepare("DELETE FROM university WHERE id = ?");
    $stmt->bind_param('i', $id);

    // Executa a consulta SQL
    if ($stmt->execute()) {
        // Verifica se alguma linha foi afetada
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Universidade excluída com sucesso."]);
        } else {
            echo json_encode(["success" => false, "message" => "Nenhuma universidade encontrada com o ID fornecido."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao excluir universidade: " . $stmt->error]);
    }

    // Fecha a declaração preparada
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "ID da universidade inválido."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
