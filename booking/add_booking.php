<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
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
$student_id = isset($input['student_id']) ? intval($input['student_id']) : null;
$room_id = isset($input['room_id']) ? intval($input['room_id']) : null;

// Verifica se os dados são válidos
if ($student_id && $room_id) {
    // Inicia uma transação
    $conn->begin_transaction();

    try {
        // Prepara a consulta SQL para adicionar a reserva
        $stmt = $conn->prepare("INSERT INTO booking (student, room) VALUES (?, ?)");
        $stmt->bind_param('ii', $student_id, $room_id);

        // Executa a consulta SQL para adicionar a reserva
        if ($stmt->execute()) {
            // Consulta SQL para atualizar o campo status na tabela room para id=2
            $updateStmt = $conn->prepare("UPDATE room SET status = 2 WHERE id = ?");
            $updateStmt->bind_param('i', $room_id);

            // Executa a consulta SQL para atualizar o campo status
            if ($updateStmt->execute()) {
                // Confirma a transação se ambas as operações forem bem-sucedidas
                $conn->commit();
                echo json_encode(["success" => true, "message" => "Reserva adicionada com sucesso!"]);
            } else {
                // Desfaz a transação em caso de erro
                $conn->rollback();
                echo json_encode(["success" => false, "message" => "Erro ao atualizar o status do quarto: " . $updateStmt->error]);
            }

            // Fecha a declaração preparada para a atualização
            $updateStmt->close();
        } else {
            // Desfaz a transação em caso de erro
            $conn->rollback();
            echo json_encode(["success" => false, "message" => "Erro ao adicionar reserva: " . $stmt->error]);
        }

        // Fecha a declaração preparada para a inserção
        $stmt->close();
    } catch (Exception $e) {
        // Desfaz a transação em caso de exceção
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Erro inesperado: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Dados inválidos para adicionar reserva."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
