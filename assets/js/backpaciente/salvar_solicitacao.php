<?php

session_start();
header('Content-Type: application/json');

require '../../../phpconexao/conexao.php';

try {

    // =====================================================
    // VERIFICAR LOGIN
    // =====================================================
    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode([
            "status" => "erro",
            "msg" => "Não autenticado"
        ]);
        exit;
    }

    $usuario_id = $_SESSION['usuario_id'];

    // =====================================================
    // BUSCAR PACIENTE REAL DO LOGIN
    // =====================================================
    $stmt = $pdo->prepare("
        SELECT id 
        FROM pacientes 
        WHERE usuario_id = ?
        LIMIT 1
    ");

    $stmt->execute([$usuario_id]);
    $paciente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$paciente) {
        echo json_encode([
            "status" => "erro",
            "msg" => "Paciente não encontrado"
        ]);
        exit;
    }

    $paciente_id = $paciente['id'];

    // =====================================================
    // RECEBER DADOS
    // =====================================================
    $data = json_decode(file_get_contents("php://input"), true);

    $especialidade_id = $data['especialidade_id'] ?? null;
    $data_desejada = $data['data_desejada'] ?? null;
    $motivo = $data['motivo'] ?? null;

    if (!$especialidade_id || !$data_desejada || !$motivo) {

        echo json_encode([
            "status" => "erro",
            "msg" => "Dados incompletos"
        ]);
        exit;
    }

    // =====================================================
    // INSERIR SOLICITAÇÃO
    // =====================================================
    $stmt = $pdo->prepare("
        INSERT INTO solicitacoes_consulta
        (paciente_id, especialidade_id, data_desejada, motivo)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $paciente_id,
        $especialidade_id,
        $data_desejada,
        $motivo
    ]);

    echo json_encode([
        "status" => "ok",
        "msg" => "Solicitação enviada com sucesso"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "erro",
        "msg" => $e->getMessage()
    ]);
}