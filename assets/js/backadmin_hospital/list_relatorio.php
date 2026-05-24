<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

try {

    // ================= MÉDICOS =================
    $sqlRelatorios = "
        SELECT 
            DATE(c.data_hora_inicio) as data,
            um.nome as medico,
            up.nome as paciente,
            c.tipo as tipo,
            c.estado as status,
            e.nome AS especialidade
        FROM consultas c
        INNER JOIN medicos m ON m.id = c.medico_id
        INNER JOIN pacientes p ON p.id = c.paciente_id
        INNER JOIN usuarios um ON m.usuario_id = um.id
        INNER JOIN usuarios up ON p.usuario_id = up.id
        INNER JOIN especialidades e ON e.id = m.especialidade_id
    ";

    $stmt = $pdo->query($sqlRelatorios);
    $relatorio = $stmt->fetchAll(PDO::FETCH_ASSOC);

 /*   // ================= SECRETÁRIOS =================
    $sqlSecretarios = "
        SELECT 
            u.id,
            u.nome,
            u.email,
            u.telefone,
            u.genero,
            u.bi,
            u.tipo_usuario,
            u.ativo,
            s.id_funcionario AS identificacao,
            '-' AS especialidade
        FROM usuarios u
        INNER JOIN secretarios s ON s.usuario_id = u.id
        WHERE u.tipo_usuario = 'secretario'
    ";

    $stmt2 = $pdo->query($sqlSecretarios);
    $secretarios = $stmt2->fetchAll(PDO::FETCH_ASSOC);
*/
    // ================= JUNÇÃO FINAL =================
    $dados = array_merge($relatorio);

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