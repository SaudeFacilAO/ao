<?php

header('Content-Type: application/json');

require '../../../phpconexao/conexao.php';

try {

    $sqlPaciente = "SELECT COUNT(*) as quant from paciente";
    $stmt1 = $pdo->query($sqlPaciente);
    $pacientes = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    $sqlConsultas = "SELECT COUNT(*) as quant from consultas";
    $stmt2 = $pdo->query($sqlConsultas);
    $consultas = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $sqlMedicos = "SELECT COUNT(*) as quant from medicos";
    $stmt3 = $pdo->query($sqlMedicos);
    $medicos = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        $sqlProfissional = "
        SELECT 
            m.foto_url as foto
            u.nome as medico,
            e.nome AS especialidade
        FROM usuarios u
        INNER JOIN medicos m ON m.usuario_id = u.id
        INNER JOIN especialidades e ON e.id = m.especialidade_id
        WHERE u.tipo_usuario = 'medico'
        LIMIT 4
    ";

    $stmt4 = $pdo->query($sqlProfissional);
    $profissional = $stmt4->fetchAll(PDO::FETCH_ASSOC);

    // ================= JUNÇÃO FINAL =================
    $dados = array_merge($pacientes, $consultas, $medicos, $profissional);

    echo json_encode([
        "status" => "ok",
        "data" => $medicos
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "erro",
        "msg" => $e->getMessage()
    ]);
}