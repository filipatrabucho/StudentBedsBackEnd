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

// Consulta para buscar a lista de estudantes com os nomes das universidades associadas
$sql = "SELECT student.id, student.name, student.phone, student.nif, university.name AS university_name 
        FROM student
        JOIN university ON student.uni = university.id;";

$result = $conn->query($sql);

// Verifica se a consulta retornou resultados
if ($result && $result->num_rows > 0) {
    $students = [];
    while ($row = $result->fetch_assoc()) {
        // Adiciona cada estudante com seus dados à lista de estudantes
        $students[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'university_name' => $row['university_name'], // Nome da universidade associada
            'tel' => $row['phone'],
            'nif' => $row['nif'],
        ];
    }
    // Retorna a lista de estudantes em formato JSON
    echo json_encode(["success" => true, "students" => $students]);
} else {
    echo json_encode(["success" => false, "message" => "Nenhum estudante encontrado."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
