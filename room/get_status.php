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
    echo json_encode(['success' => false, 'message' => 'Conexão falhou: ' . $conn->connect_error]);
    exit;
}

// Consulta SQL para obter os valores de `status` na tabela `status`
$sql = 'SELECT id, status FROM status';
$result = $conn->query($sql);

if ($result) {
    $statusList = [];
    while ($row = $result->fetch_assoc()) {
        $statusList[] = $row;
    }
    echo json_encode(['success' => true, 'statusList' => $statusList]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao obter status.']);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
