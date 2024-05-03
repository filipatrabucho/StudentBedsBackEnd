<?php
// Configurações de cabeçalho para permitir requisições de qualquer origem
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentbeds";

// Estabelece conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão com o banco de dados
if ($conn->connect_error) {
    die(json_encode(['message' => 'Conexão falhou: ' . $conn->connect_error]));
}

// Verifica se o método de requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decodifica o corpo da requisição JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Extrai o valor do campo `name` do distrito
    $district_name = isset($input['name']) ? trim($input['name']) : '';

    // Verifica se o campo `name` não está vazio
    if (!empty($district_name)) {
        // Prepara a consulta SQL para inserir o novo distrito
        $stmt = $conn->prepare("INSERT INTO district (name) VALUES (?)");
        $stmt->bind_param('s', $district_name);

        // Executa a consulta SQL e verifica o resultado
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Distrito adicionado com sucesso']);
        } else {
            // Registra o erro e retorna uma mensagem de erro
            debugLog("Erro ao adicionar distrito: " . $stmt->error);
            echo json_encode(['message' => 'Erro ao adicionar distrito: ' . $stmt->error]);
        }

        // Fecha a declaração preparada
        $stmt->close();
    } else {
        echo json_encode(['message' => 'Nome do distrito inválido. Certifique-se de fornecer um nome de distrito válido.']);
    }
} else {
    echo json_encode(['message' => 'Método de requisição inválido']);
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
