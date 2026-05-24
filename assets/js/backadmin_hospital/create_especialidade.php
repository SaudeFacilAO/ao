<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

$nome = trim($data['nome'] ?? '');
$descricao = trim($data['descricao'] ?? '');

if (!$nome) {
    echo json_encode(["status" => "erro", "msg" => "Nome obrigatório"]);
    exit;
}

try {
    $sql = "INSERT INTO especialidades (nome, descricao) VALUES (:nome, :descricao)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao
    ]);

    echo json_encode(["status" => "ok"]);

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(["status" => "erro", "msg" => "Especialidade já existe"]);
    } else {
        echo json_encode(["status" => "erro", "msg" => "Erro ao cadastrar"]);
    }
}