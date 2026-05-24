<?php
header("Content-Type: application/json; charset=UTF-8");

require_once "../../../phpconexao/conexao.php";

try {

    $medico_id = $_GET['medico_id'] ?? null;
    $data = $_GET['data'] ?? null;

    if (!$medico_id) {
        echo json_encode([
            "status" => "error",
            "msg" => "Médico obrigatório"
        ]);
        exit;
    }

    // 1. Buscar agenda do médico
    $stmt = $pdo->prepare("
        SELECT id
        FROM agendas
        WHERE medico_id = :medico_id
        LIMIT 1
    ");

    $stmt->execute([
        ':medico_id' => $medico_id
    ]);

    $agenda = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$agenda) {
        echo json_encode([
            "status" => "success",
            "data" => []
        ]);
        exit;
    }

    $agenda_id = $agenda['id'];

    // 2. Buscar horários livres
    $stmt = $pdo->prepare("
        SELECT 
            id,
            hora_inicio,
            hora_fim
        FROM horarios
        WHERE agenda_id = :agenda_id
        AND status = 'livre'
        ORDER BY hora_inicio ASC
    ");

    $stmt->execute([
        ':agenda_id' => $agenda_id
    ]);

    echo json_encode([
        "status" => "success",
        "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "msg" => $e->getMessage()
    ]);
}