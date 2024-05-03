<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

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

// Verifica se o ID da reserva foi passado na URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(["success" => false, "message" => "ID da reserva inválido ou ausente."]);
    exit;
}

$booking_id = intval($_GET['id']);

// Inicia uma transação para garantir consistência
$conn->begin_transaction();

try {
    // Consulta SQL para obter o `room_id` associado à reserva
    $stmt = $conn->prepare("SELECT room FROM booking WHERE id = ?");
    $stmt->bind_param('i', $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $room_id = $row['room'];

        // Fecha a declaração preparada para a consulta anterior
        $stmt->close();

        // Prepara a consulta SQL para eliminar a reserva
        $stmt = $conn->prepare("DELETE FROM booking WHERE id = ?");
        $stmt->bind_param('i', $booking_id);
        $stmt->execute();

        // Verifica se alguma linha foi afetada (se a reserva foi de fato eliminada)
        if ($stmt->affected_rows > 0) {
            // Atualiza o status do quarto associado para 1 (disponível)
            $updateStmt = $conn->prepare("UPDATE room SET status = 1 WHERE id = ?");
            $updateStmt->bind_param('i', $room_id);
            $updateStmt->execute();

            // Verifica se a atualização foi bem-sucedida
            if ($updateStmt->affected_rows > 0) {
                // Confirma a transação
                $conn->commit();
                echo json_encode(["success" => true, "message" => "Reserva eliminada com sucesso."]);
            } else {
                // Desfaz a transação em caso de erro na atualização
                $conn->rollback();
                echo json_encode(["success" => false, "message" => "Erro ao atualizar o status do quarto."]);
            }

            // Fecha a declaração preparada para a atualização
            $updateStmt->close();
        } else {
            // Desfaz a transação em caso de erro na exclusão
            $conn->rollback();
            echo json_encode(["success" => false, "message" => "Nenhuma reserva encontrada com o ID especificado."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Nenhuma reserva encontrada com o ID especificado."]);
    }
} catch (Exception $e) {
    // Desfaz a transação em caso de exceção
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Erro inesperado: " . $e->getMessage()]);
}

// Fecha a declaração preparada
if (isset($stmt)) {
    $stmt->close();
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
