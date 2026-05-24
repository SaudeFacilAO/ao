<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

$login = $data['login'];
$senha = $data['senha'];

try {

    $sql = "
        SELECT * FROM usuarios 
        WHERE (email = ? OR telefone = ?)
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$login, $login]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => "erro", "msg" => "Utilizador não encontrado"]);
        exit;
    }

    // ⚠️ se não estiver com password_hash ainda
    if ($user['senha'] !== $senha) {
        echo json_encode(["status" => "erro", "msg" => "Senha incorreta"]);
        exit;
    }

    echo json_encode([
        "status" => "ok",
        "usuario_id" => $user['id'],
        "tipo" => $user['tipo_usuario']
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "erro",
        "msg" => $e->getMessage()
    ]);
}