<?php

date_default_timezone_set('Africa/Luanda');

header('Content-Type: application/json');

session_start();

require '../../../phpconexao/conexao.php';

// =====================================================
// 1. VERIFICAR LOGIN
// =====================================================
$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_id) {

    echo json_encode([
        "status" => "erro",
        "msg" => "Acesso negado. Faça login."
    ]);

    exit;
}

// =====================================================
// 2. BUSCAR PACIENTE REAL DO USUÁRIO
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
        "msg" => "Paciente não encontrado."
    ]);

    exit;
}

$paciente_id = $paciente['id'];

// =====================================================
// 3. CONSULTA PRINCIPAL
// =====================================================
try {

    $sql = "
    SELECT

        s.id AS solicitacao_id,
        s.data_desejada,
        s.motivo,
        s.estado_solicitacao,
        s.created_at AS data_solicitacao,

        c.id AS consulta_id,
        c.data_hora_inicio,
        c.data_hora_fim,
        c.estado AS estado_consulta,

        t.link AS teleconsulta_link,
        t.estado AS teleconsulta_estado,

        u.nome AS medico_nome,
        e.nome AS especialidade

    FROM solicitacoes_consulta s

    LEFT JOIN consultas c
        ON c.solicitacao_id = s.id

    LEFT JOIN teleconsultas t
        ON t.consulta_id = c.id

    LEFT JOIN medicos m
        ON c.medico_id = m.id

    LEFT JOIN usuarios u
        ON m.usuario_id = u.id

    LEFT JOIN especialidades e
        ON s.especialidade_id = e.id

    WHERE s.paciente_id = ?

    ORDER BY s.id DESC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$paciente_id]);

    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // =====================================================
    // NORMALIZAÇÃO
    // =====================================================
    foreach ($dados as &$d) {

        // =========================================
        // ESTADO FINAL
        // =========================================
        if ($d['estado_consulta'] === 'cancelada') {

            $d['estado_final'] = 'cancelada';

        } elseif (!empty($d['consulta_id'])) {

            $d['estado_final'] = 'confirmada';

        } else {

            $d['estado_final'] = 'pendente';
        }

        // =========================================
        // LINK TELECONSULTA
        // =========================================
        if (empty($d['teleconsulta_link'])) {

            $d['teleconsulta_link'] = null;
        }

        // =========================================
        // 🔥 CONTROLO DE HORÁRIO (NOVO)
        // =========================================
        $d['pode_entrar'] = false;

        if (
            $d['estado_consulta'] === 'em_andamento' &&
            !empty($d['teleconsulta_link']) &&
            !empty($d['data_hora_inicio'])
        ) {

            try {

                $agora = new DateTime();
                $inicio = new DateTime($d['data_hora_inicio']);

                // tolerância igual ao médico
                $limite_inicio = clone $inicio;
                $limite_inicio->modify('-10 minutes');

                $limite_fim = clone $inicio;
                $limite_fim->modify('+60 minutes');

                if ($agora >= $limite_inicio && $agora <= $limite_fim) {
                    $d['pode_entrar'] = true;
                }

            } catch (Exception $e) {
                $d['pode_entrar'] = false;
            }
        }
    }

    echo json_encode([
        "status" => "ok",
        "data" => $dados
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "erro",
        "msg" => $e->getMessage()
    ]);
}
?>