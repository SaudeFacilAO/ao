<?php
session_start();

header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . "/conexao.php";

try {

    // =========================
    // VERIFICAR SESSÃO DE PRIMEIRO LOGIN
    // =========================
    if (!isset($_SESSION['temp_user_id'])) {
        echo json_encode([
            "status" => "erro",
            "msg" => "Sessão inválida ou expirada"
        ]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data["senha"])) {
        echo json_encode([
            "status" => "erro",
            "msg" => "Senha não enviada"
        ]);
        exit;
    }

    $senha = trim($data["senha"]);

    // =========================
    // VALIDAÇÃO DE SENHA FORTE
    // 8–10 caracteres:
    // maiúscula, minúscula, número e símbolo
    // =========================
    if (
        strlen($senha) < 8 ||
        strlen($senha) > 10 ||
        !preg_match('/[A-Z]/', $senha) ||
        !preg_match('/[a-z]/', $senha) ||
        !preg_match('/[0-9]/', $senha) ||
        !preg_match('/[^A-Za-z0-9]/', $senha)
    ) {
        echo json_encode([
            "status" => "erro",
            "msg" => "Senha fraca. Use 8–10 caracteres com maiúscula, minúscula, número e símbolo"
        ]);
        exit;
    }

    // =========================
    // ID DO UTILIZADOR
    // =========================
    $user_id = $_SESSION['temp_user_id'];

    // =========================
    // ATUALIZAR SENHA
    // =========================
    $stmt = $pdo->prepare("
        UPDATE usuarios 
        SET senha = ?, primeiro_login = 0
        WHERE id = ?
    ");

    

    $stmt->execute([
        password_hash($senha, PASSWORD_DEFAULT),
        $user_id
    ]);

    // =========================
    // LIMPAR SESSÃO TEMPORÁRIA
    // =========================
    unset($_SESSION['temp_user_id']);
    unset($_SESSION['temp_tipo']);
    unset($_SESSION['temp_nome']);

    // =========================
    // RESPOSTA FINAL
    // =========================
    echo json_encode([
        "status" => "ok",
        "msg" => "Senha atualizada com sucesso",
        "redirect" => "login.php"
    ]);

} catch (Throwable $e) {

    echo json_encode([
        "status" => "erro",
        "msg" => "Erro interno no servidor"
    ]);
}