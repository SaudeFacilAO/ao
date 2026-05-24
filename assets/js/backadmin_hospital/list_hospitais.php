<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

try {
    $stmt = $pdo->query("SELECT id, nome FROM hospitais ORDER BY nome ASC");
    $hospitais = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "ok",
        "data" => $hospitais
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "erro",
        "msg" => "Erro ao buscar hospitais"
    ]);
}