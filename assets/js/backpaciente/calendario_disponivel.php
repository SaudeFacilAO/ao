<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

session_start();

try {

    $especialidade_id = $_GET['especialidade_id'] ?? null;
    $ano = (int)($_GET['ano'] ?? date('Y'));
    $mes = (int)($_GET['mes'] ?? date('m'));

    if (!$especialidade_id) {
        echo json_encode([
            "status" => "erro",
            "msg" => "Especialidade não informada"
        ]);
        exit;
    }

    // =========================================
    // 1. MÉDICOS DA ESPECIALIDADE
    // =========================================
    $sqlMedicos = "SELECT id FROM medicos WHERE especialidade_id = ?";
    $stmt = $pdo->prepare($sqlMedicos);
    $stmt->execute([$especialidade_id]);

    $medicos = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($medicos)) {
        echo json_encode([
            "status" => "ok",
            "dias_disponiveis" => []
        ]);
        exit;
    }

    // =========================================
    // 2. AGENDA DOS MÉDICOS
    // =========================================
    $placeholders = implode(',', array_fill(0, count($medicos), '?'));

    $sqlAgenda = "
        SELECT DISTINCT dia_semana
        FROM agendas
        WHERE medico_id IN ($placeholders)
    ";

    $stmt = $pdo->prepare($sqlAgenda);
    $stmt->execute($medicos);

    $agenda = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $diasTrabalho = [];

    foreach ($agenda as $dia) {
        $diasTrabalho[(int)$dia] = true;
    }

    // =========================================
    // 3. CONSULTAS EXISTENTES
    // =========================================
    $sqlConsultas = "
        SELECT DATE(data_hora_inicio) as data
        FROM consultas
        WHERE MONTH(data_hora_inicio) = ?
        AND YEAR(data_hora_inicio) = ?
    ";

    $stmt = $pdo->prepare($sqlConsultas);
    $stmt->execute([$mes, $ano]);

    $consultas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $diasOcupados = [];

    foreach ($consultas as $c) {
        $diasOcupados[$c] = true;
    }

    // =========================================
    // 4. REGRAS DE NEGÓCIO
    // =========================================
    $hoje = new DateTime();
    $hoje->setTime(0, 0, 0);

    $now = new DateTime();

    $diasDisponiveis = [];

    $diasNoMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

    for ($d = 1; $d <= $diasNoMes; $d++) {

        $data = DateTime::createFromFormat('Y-n-j', "$ano-$mes-$d");
        $data->setTime(0, 0, 0);

        $dataStr = $data->format('Y-m-d');

        $diaSemana = (int)$data->format('w'); // 0 domingo - 6 sábado

        $disponivel = true;

        // ❌ dias passados
        if ($data < $hoje) {
            $disponivel = false;
        }

        // ❌ domingo
        if ($diaSemana === 0) {
            $disponivel = false;
        }

        // ❌ regra das 16h (bloqueia amanhã)
        $amanha = (new DateTime())->modify('+1 day')->format('Y-m-d');

        if ((int)$now->format('H') >= 16 && $dataStr === $amanha) {
            $disponivel = false;
        }

        // ❌ sem médico nesse dia
        if (!isset($diasTrabalho[$diaSemana])) {
            $disponivel = false;
        }

        // ❌ já existe consulta nesse dia
        if (isset($diasOcupados[$dataStr])) {
            $disponivel = false;
        }

        if ($disponivel) {
            $diasDisponiveis[] = $dataStr;
        }
    }

    // =========================================
    // 5. RESPOSTA FINAL (ÚNICA!)
    // =========================================
    echo json_encode([
        "status" => "ok",
        "dias_disponiveis" => $diasDisponiveis
    ]);
    exit;

} catch (Exception $e) {

    echo json_encode([
        "status" => "erro",
        "msg" => $e->getMessage()
    ]);
    exit;
}