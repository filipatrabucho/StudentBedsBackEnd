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

// Consulta SQL para buscar todos os funcionários da tabela staff
$sql = "SELECT id, name, username, password FROM staff";
$result = $conn->query($sql);

// Verifica se a consulta retornou resultados
if ($result && $result->num_rows > 0) {
    $staff = [];
    while ($row = $result->fetch_assoc()) {
        // Corrigido para usar a coluna 'password' em vez de 'password_hash'
        $staff[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'username' => $row['username'],
            'password' => $row['password']
        ];
    }
    // Retorna os dados em formato JSON
    echo json_encode(["success" => true, "staff" => $staff]);
} else {
    echo json_encode(["success" => false, "message" => "Nenhum funcionário encontrado."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
