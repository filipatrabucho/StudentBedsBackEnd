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

// Consulta SQL para buscar os últimos 5 bookings
$sql = "SELECT booking.id AS booking_id, student.name AS student_name, room.name AS room_name
        FROM booking
        JOIN student ON booking.student = student.id
        JOIN room ON booking.room = room.id
        ORDER BY booking.id DESC
        LIMIT 5";

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
    // Retorna os dados em formato JSON
    echo json_encode(["success" => true, "bookings" => $bookings]);
} else {
    echo json_encode(["success" => false, "message" => "Nenhum booking encontrado."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
