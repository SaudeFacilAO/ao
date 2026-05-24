<?php
header('Content-Type: application/json; charset=UTF-8');

require '../../../phpconexao/conexao.php';

try {

    $sql = "
        SELECT
            a.id AS agenda_id,
            a.dia_semana,
            a.turno,
            a.hora_inicio,
            a.hora_fim,
            a.intervalo_consulta,

            m.id AS medico_id,
            u.nome AS medico_nome,
            e.nome AS especialidade,

            h.id AS horario_id,
            h.hora_inicio AS slot_inicio,
            h.hora_fim AS slot_fim,
            h.status

        FROM agendas a

        INNER JOIN medicos m
            ON a.medico_id = m.id

        INNER JOIN usuarios u
            ON m.usuario_id = u.id

        INNER JOIN especialidades e
            ON m.especialidade_id = e.id

        INNER JOIN horarios h
            ON h.agenda_id = a.id

        ORDER BY
            u.nome,
            a.dia_semana,
            h.hora_inicio
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "ok",
        "data"   => $dados
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "erro",
        "msg"    => $e->getMessage()
    ]);
}