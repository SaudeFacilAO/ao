<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

try {

    // ================= MÉDICOS =================
    $sqlMedicos = "
        SELECT 
            u.id,
            u.nome,
            u.email,
            u.telefone,
            u.genero,
            u.bi,
            u.tipo_usuario,
            u.ativo,
            m.crm AS identificacao,
            e.nome AS especialidade
        FROM usuarios u
        INNER JOIN medicos m ON m.usuario_id = u.id
        INNER JOIN especialidades e ON e.id = m.especialidade_id
        WHERE u.tipo_usuario = 'medico'
    ";

    $stmt = $pdo->query($sqlMedicos);
    $medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ================= SECRETÁRIOS =================
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

    // ================= JUNÇÃO FINAL =================
    $dados = array_merge($medicos, $secretarios);

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