<?php
header("Content-Type: application/json; charset=UTF-8");

require_once "../../../phpconexao/conexao.php";

$acao = $_GET['acao'] ?? $_POST['acao'] ?? '';

/* =========================================================
   LISTAR SOLICITAÇÕES
========================================================= */
if ($acao === 'listar') {

    try {

        $sql = "
            SELECT 
    s.id,
    u.nome AS paciente,
    s.especialidade_id,
    e.nome AS especialidade,
    s.data_desejada,
    s.estado_solicitacao AS estado
FROM solicitacoes_consulta s
INNER JOIN pacientes p ON p.id = s.paciente_id
INNER JOIN usuarios u ON u.id = p.usuario_id
INNER JOIN especialidades e ON e.id = s.especialidade_id
ORDER BY s.id DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        echo json_encode([
            "status" => "success",
            "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ]);
        exit;

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
        exit;
    }
}


/* =========================================================
   FILTRAR
========================================================= */
if ($acao === 'filtrar') {

    try {

        $estado = $_GET['estado'] ?? 'todas';

        $sql = "
            SELECT 
                s.id,
                u.nome AS paciente,
                e.nome AS especialidade,
                s.data_desejada,
                s.estado_solicitacao AS estado
            FROM solicitacoes_consulta s
            INNER JOIN pacientes p ON p.id = s.paciente_id
            INNER JOIN usuarios u ON u.id = p.usuario_id
            INNER JOIN especialidades e ON e.id = s.especialidade_id
        ";

        if ($estado !== 'todas') {
            $sql .= " WHERE s.estado_solicitacao = :estado";
        }

        $sql .= " ORDER BY s.id DESC";

        $stmt = $pdo->prepare($sql);

        if ($estado !== 'todas') {
            $stmt->bindParam(':estado', $estado);
        }

        $stmt->execute();

        echo json_encode([
            "status" => "success",
            "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ]);
        exit;

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
        exit;
    }
}


