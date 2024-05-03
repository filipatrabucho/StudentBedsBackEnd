<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = ""; // Insira sua senha do banco de dados aqui
$dbname = "studentbeds";

// Estabelece uma conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão com o banco de dados
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Erro de conexão com o banco de dados: " . $conn->connect_error]);
    exit;
}

// Recebe os dados da solicitação PUT
$input = json_decode(file_get_contents('php://input'), true);

// Extrai os dados da solicitação
$id = isset($input['id']) ? intval($input['id']) : null;
$name = isset($input['name']) ? trim($input['name']) : null;
$district_name = isset($input['district_name']) ? trim($input['district_name']) : null;

// Verifica se todos os campos necessários estão presentes
if (is_null($id) || is_null($name) || is_null($district_name)) {
    echo json_encode(["success" => false, "message" => "ID, nome ou nome do distrito inválidos."]);
    exit;
}

// Obter o ID do distrito com base no nome do distrito (insensível a maiúsculas e minúsculas)
$stmt = $conn->prepare("SELECT id FROM district WHERE LOWER(name) = LOWER(?)");
$stmt->bind_param("s", $district_name);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Verifica se o distrito foi encontrado
if (!$row) {
    echo json_encode(["success" => false, "message" => "Distrito não encontrado."]);
    exit;
}

// Obter o ID do distrito
$district_id = $row['id'];

// Prepara a consulta SQL para atualizar a universidade
$stmt = $conn->prepare("UPDATE university SET name = ?, district = ? WHERE id = ?");
$stmt->bind_param("sii", $name, $district_id, $id);

// Executa a consulta SQL
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Universidade editada com sucesso."]);
} else {
    echo json_encode(["success" => false, "message" => "Erro ao editar universidade: " . $stmt->error]);
}

// Fecha a declaração preparada
$stmt->close();

// Fecha a conexão com o banco de dados
$conn->close();
?>
