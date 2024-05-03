<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
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

// Recebe o ID do quarto da solicitação DELETE
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Verifica se o ID é válido
if ($id !== null) {
    // Prepara a consulta SQL para excluir o quarto pelo ID
    $stmt = $conn->prepare("DELETE FROM room WHERE id = ?");
    $stmt->bind_param('i', $id);

    // Executa a consulta SQL
    if ($stmt->execute()) {
        // Verifica se a exclusão foi bem-sucedida
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Quarto excluído com sucesso."]);
        } else {
            echo json_encode(["success" => false, "message" => "Nenhum quarto encontrado com o ID fornecido."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao excluir quarto: " . $stmt->error]);
    }

    // Fecha a declaração preparada
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "ID inválido ou não fornecido."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
