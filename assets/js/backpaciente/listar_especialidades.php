<?php

header('Content-Type: application/json');

require '../../../phpconexao/conexao.php';

try {

    $sql = "
        SELECT id, nome
        FROM especialidades
        WHERE ativa = 1
        ORDER BY nome ASC
    ";

    $stmt = $pdo->query($sql);

    $especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "ok",
        "data" => $especialidades
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "erro",
        "msg" => $e->getMessage()
    ]);
}