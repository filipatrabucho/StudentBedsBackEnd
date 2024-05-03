<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
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
$username = isset($input['username']) ? trim($input['username']) : null;
$password = isset($input['password']) ? trim($input['password']) : null;

// Verifica se os dados recebidos são válidos
if ($username !== null && $password !== null) {
    // Prepara a consulta SQL para buscar o funcionário pelo nome de usuário
    $stmt = $conn->prepare("SELECT id, password FROM staff WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o funcionário foi encontrado
    if ($result && $row = $result->fetch_assoc()) {
        $hashed_password = $row['password'];

        // Verifica se a senha fornecida corresponde à senha com hash armazenada
        if (password_verify($password, $hashed_password)) {
            // Senha válida
            echo json_encode(["success" => true, "message" => "Login realizado com sucesso.", "id" => $row['id']]);
        } else {
            // Senha inválida
            echo json_encode(["success" => false, "message" => "Senha incorreta."]);
        }
    } else {
        // Nome de usuário não encontrado
        echo json_encode(["success" => false, "message" => "Nome de usuário não encontrado."]);
    }

    // Fecha a declaração preparada
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Dados inválidos para realizar login."]);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
