<?php

header('Content-Type: application/json');

require '../../../phpconexao/conexao.php';

try {

    $id = $_GET['id'] ?? null;

    if (!$id) {
        throw new Exception("ID inválido");
    }

    $stmt = $pdo->prepare("
        DELETE FROM agendas
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    echo json_encode([
        "status" => "ok"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "erro",
        "msg" => $e->getMessage()
    ]);
}