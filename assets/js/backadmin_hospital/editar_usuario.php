<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["status" => "erro", "msg" => "ID não fornecido"]);
    exit;
}

try {

    // buscar usuario base
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo json_encode(["status" => "erro", "msg" => "Usuário não encontrado"]);
        exit;
    }

    $extra = null;

    // se for medico
    if ($usuario['tipo_usuario'] === 'medico') {

        $stmt2 = $pdo->prepare("
            SELECT crm, especialidade_id, hospital_id
            FROM medicos
            WHERE usuario_id = ?
        ");
        $stmt2->execute([$id]);
        $extra = $stmt2->fetch(PDO::FETCH_ASSOC);
    }

    // se for secretario
    if ($usuario['tipo_usuario'] === 'secretario') {

        $stmt2 = $pdo->prepare("
            SELECT id_funcionario, hospital_id
            FROM secretarios
            WHERE usuario_id = ?
        ");
        $stmt2->execute([$id]);
        $extra = $stmt2->fetch(PDO::FETCH_ASSOC);
    }

    echo json_encode([
        "status" => "ok",
        "usuario" => $usuario,
        "extra" => $extra
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "erro",
        "msg" => $e->getMessage()
    ]);
}