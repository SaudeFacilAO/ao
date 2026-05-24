<?php

require_once "../../../phpconexao/conexao.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => "error",
        "msg" => "Método inválido"
    ]);
    exit;
}

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode([
        "status" => "error",
        "msg" => "ID inválido"
    ]);
    exit;
}

try {

    $sql = "UPDATE solicitacoes_consulta  
            SET estado_solicitacao = 'cancelada'
            WHERE id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$id]);

    echo json_encode([
        "status" => "success"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "msg" => $e->getMessage()
    ]);
}