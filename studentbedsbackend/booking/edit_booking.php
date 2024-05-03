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
$booking_id = isset($input['booking_id']) ? intval($input['booking_id']) : null;
$student_id = isset($input['student_id']) ? intval($input['student_id']) : null;
$room_id = isset($input['room_id']) ? intval($input['room_id']) : null;

// Verifica se os dados são válidos
if ($booking_id && $student_id && $room_id) {
    // Prepara a consulta SQL para atualizar a reserva
    $stmt = $conn->prepare("UPDATE booking SET student = ?, room= ? WHERE id = ?");
    $stmt->bind_param('iii', $student_id, $room_id, $booking_id);

    // Executa a consulta SQL
    if ($stmt->execute()) {
        // Verifica se alguma linha foi afetada
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Reserva editada com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Nenhuma reserva foi atualizada."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao editar reserva: " . $stmt->error]);
    }

    // Fecha a declaração preparada
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Dados inválidos para editar reserva."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
