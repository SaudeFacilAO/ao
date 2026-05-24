<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "../../../phpconexao/conexao.php";

try {

    $especialidade_nome = $_GET['especialidade'] ?? '';
    $data_consulta = $_GET['data'] ?? '';

    if ($especialidade_nome === '' || $data_consulta === '') {
        echo json_encode([
            "status" => "error",
            "msg" => "Especialidade e data são obrigatórias"
        ]);
        exit;
    }

    // ======================================
    // 1. ID DA ESPECIALIDADE
    // ======================================
    $stmt = $pdo->prepare("
        SELECT id 
        FROM especialidades 
        WHERE nome = :nome
        LIMIT 1
    ");

    $stmt->execute([
        ':nome' => $especialidade_nome
    ]);

    $esp = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$esp) {
        echo json_encode([
            "status" => "success",
            "data" => []
        ]);
        exit;
    }

    $esp_id = $esp['id'];

    // ======================================
    // 2. DIA DA SEMANA DA DATA ESCOLHIDA
    // ======================================
    $diaSemana = (int) date('w', strtotime($data_consulta));

    // ======================================
    // 3. MÉDICOS DISPONÍVEIS NO DIA
    // ======================================
    $stmt = $pdo->prepare("
        SELECT DISTINCT
            m.id,
            u.nome
        FROM medicos m
        INNER JOIN usuarios u ON u.id = m.usuario_id
        INNER JOIN agendas a ON a.medico_id = m.id
        WHERE m.especialidade_id = :esp_id
        AND a.dia_semana = :dia
    ");

    $stmt->execute([
        ':esp_id' => $esp_id,
        ':dia' => $diaSemana
    ]);

    $medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $medicos
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "msg" => $e->getMessage()
    ]);
}