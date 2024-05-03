<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

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

// Consulta SQL para buscar reservas com nomes dos estudantes e quartos
$sql = "SELECT booking.id AS booking_id, student.name AS student_name, room.name AS room_name
        FROM booking
        JOIN student ON booking.student = student.id
        JOIN room ON booking.room = room.id";
$result = $conn->query($sql);

// Verifica se a consulta retornou resultados
if ($result && $result->num_rows > 0) {
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = [
            'booking_id' => $row['booking_id'],
            'student_name' => $row['student_name'],
            'room_name' => $row['room_name']
        ];
    }
    // Retorna a lista de reservas em formato JSON
    echo json_encode(["success" => true, "bookings" => $bookings]);
} else {
    echo json_encode(["success" => false, "message" => "Nenhuma reserva encontrada."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
