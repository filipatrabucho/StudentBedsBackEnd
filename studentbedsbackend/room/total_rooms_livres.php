<?php
// Configurações de cabeçalho para permitir requisições de qualquer origem
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Configurações de conexão com o banco de dados
$servername = 'localhost';
$username = 'root';
$password = ''; 
$dbname = 'studentbeds';

// Estabelece conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão com o banco de dados
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Conexão falhou: ' . $conn->connect_error]));
}

// Consulta SQL para obter o total de rooms livres (status = 1)
$sql = 'SELECT COUNT(*) AS total FROM room WHERE status = 1';
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $total = $row['total'];
    echo json_encode(['success' => true, 'total' => $total]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao obter total de quartos livres.']);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
