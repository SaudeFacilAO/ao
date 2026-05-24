<?php
session_start();

header("Content-Type: application/json; charset=UTF-8");

require_once "../../../phpconexao/conexao.php";

try {

    // =====================================================
    // VERIFICAR LOGIN
    // =====================================================
    $usuario_id =
        $_SESSION['usuario_id'] ?? null;

    if (!$usuario_id) {

        echo json_encode([
            "status" => "erro",
            "msg" => "Acesso negado. Faça login."
        ]);

        exit;
    }

    // =====================================================
    // BUSCAR ID REAL DO MÉDICO
    // =====================================================
    $stmt = $pdo->prepare("
        SELECT id
        FROM medicos
        WHERE usuario_id = :usuario_id
        LIMIT 1
    ");

    $stmt->execute([
        ':usuario_id' => $usuario_id
    ]);

    $medico =
        $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$medico) {

        echo json_encode([
            "status" => "erro",
            "msg" => "Médico não encontrado"
        ]);

        exit;
    }

    $medico_id = $medico['id'];

    // =====================================================
    // LISTAR CONSULTAS
    // =====================================================
    $sql = "
        SELECT
            c.id AS consulta_id,

            -- PACIENTE
            u.nome AS paciente_nome,
            u.telefone,

            -- CONSULTA
            c.data_hora_inicio,
            c.estado,

            -- TELECONSULTA
            t.link,

            -- ESPECIALIDADE
            e.nome AS especialidade

        FROM consultas c

        INNER JOIN pacientes p
            ON p.id = c.paciente_id

        INNER JOIN usuarios u
            ON u.id = p.usuario_id

        INNER JOIN medicos m
            ON m.id = c.medico_id

        INNER JOIN especialidades e
            ON e.id = m.especialidade_id

        LEFT JOIN teleconsultas t
            ON t.consulta_id = c.id

        WHERE c.medico_id = :medico_id

        ORDER BY c.data_hora_inicio DESC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':medico_id' => $medico_id
    ]);

    echo json_encode([
        "status" => "success",
        "data" =>
            $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "msg" => $e->getMessage()
    ]);
}