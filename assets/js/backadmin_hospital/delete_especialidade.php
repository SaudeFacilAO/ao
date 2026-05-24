<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(["status" => "erro"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM especialidades WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode(["status" => "ok"]);

} catch (PDOException $e) {
    echo json_encode(["status" => "erro"]);
}