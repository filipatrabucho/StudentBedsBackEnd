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

// Verifica se o método de requisição é DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Recebe o ID do distrito a ser excluído da query string
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    // Verifica se o ID é válido
    if ($id) {
        // Prepara a consulta SQL para excluir o distrito com base no ID
        $stmt = $conn->prepare("DELETE FROM district WHERE id = ?");
        $stmt->bind_param('i', $id);

        // Executa a consulta SQL
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Distrito excluído com sucesso."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao excluir distrito: " . $stmt->error]);
        }

        // Fecha a declaração preparada
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "ID de distrito inválido."]);
    }
} else {
    // Retorna uma resposta de erro para método de requisição inválido
    echo json_encode(["success" => false, "message" => "Método de requisição inválido. Use DELETE."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
