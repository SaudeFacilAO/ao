<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

try {

    $sql = "SELECT m.id, u.nome 
            FROM medicos m
            INNER JOIN usuarios u ON m.usuario_id = u.id
            ORDER BY u.nome ASC";

    $stmt = $pdo->query($sql);
    $medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "ok",
        "data" => $medicos
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "erro",
        "msg" => "Erro ao listar médicos"
    ]);
}