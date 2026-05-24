<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

try {
    $stmt = $pdo->query("
        SELECT id, nome 
        FROM especialidades 
        WHERE ativa = 1
        ORDER BY nome ASC
    ");

    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "ok",
        "data" => $dados
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => "erro",
        "msg" => "Erro ao buscar especialidades"
    ]);
}