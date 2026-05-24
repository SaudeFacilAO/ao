<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

try {

    $medico_id = $_GET['medico_id'] ?? null;

    if (!$medico_id) {
        echo json_encode([
            "status" => "erro",
            "msg" => "Médico não informado"
        ]);
        exit;
    }

    $sql = "SELECT e.id, e.nome 
            FROM medicos m
            INNER JOIN especialidades e ON m.especialidade_id = e.id
            WHERE m.id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$medico_id]);

    $especialidade = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "ok",
        "data" => $especialidade
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "erro",
        "msg" => "Erro ao buscar especialidade"
    ]);
}