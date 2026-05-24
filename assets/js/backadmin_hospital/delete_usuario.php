<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode([
        "status" => "erro",
        "msg" => "ID não fornecido"
    ]);
    exit;
}

try {

    $pdo->beginTransaction();

    // 1. Remover da tabela medicos (se existir)
    $stmt = $pdo->prepare("DELETE FROM medicos WHERE usuario_id = ?");
    $stmt->execute([$id]);

    // 2. Remover da tabela secretarios (se existir)
    $stmt = $pdo->prepare("DELETE FROM secretarios WHERE usuario_id = ?");
    $stmt->execute([$id]);

    // 3. Remover usuário principal
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);

    $pdo->commit();

    echo json_encode([
        "status" => "ok",
        "msg" => "Usuário removido com sucesso"
    ]);

} catch (Exception $e) {

    $pdo->rollBack();

    echo json_encode([
        "status" => "erro",
        "msg" => $e->getMessage()
    ]);
}