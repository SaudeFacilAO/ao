<?php
header("Content-Type: application/json; charset=UTF-8");

require_once "../../../phpconexao/conexao.php";

try {

    $solicitacao_id = $_POST['solicitacao_id'] ?? null;
    $medico_id      = $_POST['medico_id'] ?? null;
    $horario_id     = $_POST['horario_id'] ?? null;
    $data_inicio    = $_POST['data_inicio'] ?? null;
    $data_fim       = $_POST['data_fim'] ?? null;
    $hospital_id    = 1;

    if (
        !$solicitacao_id ||
        !$medico_id ||
        !$horario_id ||
        !$data_inicio ||
        !$data_fim
    ) {
        echo json_encode([
            "status" => "error",
            "msg" => "Dados incompletos"
        ]);
        exit;
    }

    // 🔹 1. Buscar solicitação
    $stmt = $pdo->prepare("
        SELECT paciente_id
        FROM solicitacoes_consulta
        WHERE id= :id
    ");

    $stmt->execute([
        ':id' => $solicitacao_id
    ]);

    $sol = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sol) {
        echo json_encode([
            "status" => "error",
            "msg" => "Solicitação não encontrada"
        ]);
        exit;
    }

    $paciente_id = $sol['paciente_id'];

    // 🔹 2. Criar consulta
    $stmt = $pdo->prepare("
        INSERT INTO consultas (
            solicitacao_id,
            paciente_id,
            medico_id,
            hospital_id,
            horario_id,
            data_hora_inicio,
            data_hora_fim,
            tipo,
            estado
        ) VALUES (
            :solicitacao_id,
            :paciente_id,
            :medico_id,
            :hospital_id,
            :horario_id,
            :inicio,
            :fim,
            'teleconsulta',
            'agendada'
        )
    ");

    $stmt->execute([
        ':solicitacao_id' => $solicitacao_id,
        ':paciente_id' => $paciente_id,
        ':medico_id' => $medico_id,
        ':hospital_id' => $hospital_id,
        ':horario_id' => $horario_id,
        ':inicio' => $data_inicio,
        ':fim' => $data_fim
    ]);

    $consulta_id = $pdo->lastInsertId();

    // 🔹 3. Gerar link Jitsi
    $room = "consulta_" . $consulta_id . "_" . uniqid();
    $link = "https://meet.jit.si/" . $room;

    $stmt = $pdo->prepare("
        INSERT INTO teleconsultas (
            consulta_id,
            link,
            estado
        ) VALUES (
            :consulta_id,
            :link,
            'ativa'
        )
    ");

    $stmt->execute([
        ':consulta_id' => $consulta_id,
        ':link' => $link
    ]);

    // 🔹 4. Marcar horário como ocupado
    $stmt = $pdo->prepare("
        UPDATE horarios
        SET status = 'ocupado',
            consulta_id = :consulta_id
        WHERE id = :horario_id
    ");

    $stmt->execute([
        ':consulta_id' => $consulta_id,
        ':horario_id' => $horario_id
    ]);

    // 🔹 5. Atualizar solicitação
    $stmt = $pdo->prepare("
        UPDATE solicitacoes_consulta
        SET estado_solicitacao = 'confirmada'
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $solicitacao_id
    ]);

    // 🔥 RESPOSTA FINAL
    echo json_encode([
        "status" => "success",
        "msg" => "Consulta agendada com sucesso",
        "consulta_id" => $consulta_id,
        "link" => $link
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "msg" => $e->getMessage()
    ]);
}