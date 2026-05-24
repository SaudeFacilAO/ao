<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$nome = trim($data['nome'] ?? '');
$descricao = trim($data['descricao'] ?? '');
$ativa = $data['ativa'] ?? 1;

if (!$id || !$nome) {
    echo json_encode(["status" => "erro"]);
    exit;
}

try {
    $sql = "UPDATE especialidades 
            SET nome = :nome, descricao = :descricao, ativa = :ativa 
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':ativa' => $ativa
    ]);

    echo json_encode(["status" => "ok"]);

} catch (PDOException $e) {
    echo json_encode(["status" => "erro"]);
}