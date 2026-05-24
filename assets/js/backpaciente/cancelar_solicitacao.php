<?php

header('Content-Type: application/json');

require '../../../phpconexao/conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

$solicitacao_id = $data['solicitacao_id'];

try {

    // ================= BUSCAR SOLICITAÇÃO =================
    $stmt = $pdo->prepare("
        SELECT *
        FROM solicitacoes_consulta
        WHERE id = ?
    ");

    $stmt->execute([$solicitacao_id]);

    $solicitacao = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$solicitacao) {

        echo json_encode([
            "status" => "erro",
            "msg" => "Solicitação não encontrada"
        ]);

        exit;
    }

    // ================= CANCELAR SOLICITAÇÃO =================
    $stmt = $pdo->prepare("
        UPDATE solicitacoes_consulta
        SET estado_solicitacao = 'cancelada'
        WHERE id = ?
    ");

    $stmt->execute([$solicitacao_id]);

    // ================= VERIFICAR CONSULTA =================
    $stmt = $pdo->prepare("
        SELECT *
        FROM consultas
        WHERE paciente_id = ?
        ORDER BY id DESC
        LIMIT 1
    ");

    $stmt->execute([$solicitacao['paciente_id']]);

    $consulta = $stmt->fetch(PDO::FETCH_ASSOC);

    // ================= SE EXISTIR CONSULTA =================
    if ($consulta) {

        // cancelar consulta
        $stmt = $pdo->prepare("
            UPDATE consultas
            SET estado = 'cancelada'
            WHERE id = ?
        ");

        $stmt->execute([$consulta['id']]);

        // guardar histórico
        $stmt = $pdo->prepare("
            INSERT INTO cancelamentos_consulta_paciente
            (
                consulta_id,
                paciente_id,
                motivo
            )
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $consulta['id'],
            $solicitacao['paciente_id'],
            'Cancelado pelo paciente'
        ]);
    }

    echo json_encode([
        "status" => "ok"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "erro",
        "msg" => $e->getMessage()
    ]);
}