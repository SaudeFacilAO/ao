<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

try {

    if (
        empty($data['medico_id']) ||
        empty($data['dias']) ||
        empty($data['hora_inicio']) ||
        empty($data['hora_fim'])
    ) {
        throw new Exception("Dados obrigatórios em falta");
    }

    $pdo->beginTransaction();

    foreach ($data['dias'] as $dia) {

        // ================= 1. CRIAR AGENDA =================
        $stmt = $pdo->prepare("
            INSERT INTO agendas 
            (medico_id, dia_semana, hora_inicio, hora_fim, intervalo_consulta, intervalo_inicio, intervalo_fim)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['medico_id'],
            $dia,
            $data['hora_inicio'],
            $data['hora_fim'],
            $data['intervalo'] ?? 30,
            $data['pausaInicio'] ?? null,
            $data['pausaFim'] ?? null
        ]);

        $agenda_id = $pdo->lastInsertId();

        // ================= 2. GERAR HORÁRIOS =================
        gerarHorarios($pdo, $agenda_id, $data);
    }

    $pdo->commit();

    echo json_encode([
        "status" => "ok",
        "msg" => "Escala criada com sucesso"
    ]);

} catch (Exception $e) {

    $pdo->rollBack();

    echo json_encode([
        "status" => "erro",
        "msg" => $e->getMessage()
    ]);
}


// ================= GERAR HORÁRIOS =================
function gerarHorarios($pdo, $agenda_id, $data) {

    $inicio = toMin($data['hora_inicio']);
    $fim = toMin($data['hora_fim']);
    $intervalo = (int) ($data['intervalo'] ?? 30);

    $pausaIni = !empty($data['pausaInicio']) ? toMin($data['pausaInicio']) : null;
    $pausaFim = !empty($data['pausaFim']) ? toMin($data['pausaFim']) : null;

    while ($inicio + $intervalo <= $fim) {

        $proximo = $inicio + $intervalo;

        // 🔥 ignorar pausa almoço
        if ($pausaIni && $pausaFim && $inicio < $pausaFim && $proximo > $pausaIni) {
            $inicio = $pausaFim;
            continue;
        }

        $stmt = $pdo->prepare("
            INSERT INTO horarios (agenda_id, hora_inicio, hora_fim)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $agenda_id,
            formatHora($inicio),
            formatHora($proximo)
        ]);

        $inicio = $proximo;
    }
}


// ================= AUX =================
function toMin($hora) {
    $parts = explode(":", $hora);
    return ((int)$parts[0] * 60) + (int)$parts[1];
}

function formatHora($min) {
    $h = floor($min / 60);
    $m = $min % 60;
    return sprintf("%02d:%02d", $h, $m);
}