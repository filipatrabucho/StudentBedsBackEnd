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

// Verifica se o ID foi fornecido
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Verifica se o ID é válido
    if ($id > 0) {
        // Prepara a consulta SQL para excluir o funcionário com o ID fornecido
        $stmt = $conn->prepare("DELETE FROM staff WHERE id = ?");
        $stmt->bind_param('i', $id);

        // Executa a consulta SQL
        if ($stmt->execute()) {
            // Verifica se alguma linha foi afetada
            if ($stmt->affected_rows > 0) {
                echo json_encode(["success" => true, "message" => "Funcionário excluído com sucesso!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Nenhuma exclusão foi feita. Funcionário não encontrado."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao excluir funcionário: " . $stmt->error]);
        }

        // Fecha a declaração preparada
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "ID inválido para exclusão."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ID do funcionário não fornecido para exclusão."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
