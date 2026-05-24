<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/conexao.php";

try {

    $login = trim($_POST['login'] ?? '');
    $senha = trim($_POST['password'] ?? '');

    if ($login === '' || $senha === '') {
        echo json_encode([
            "success" => false,
            "message" => "Preencha todos os campos"
        ]);
        exit;
    }

    // buscar utilizador
    $stmt = $pdo->prepare("
        SELECT * FROM usuarios 
        WHERE email = ? OR telefone = ?
        LIMIT 1
    ");

    $stmt->execute([$login, $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([
            "success" => false,
            "message" => "Utilizador não encontrado"
        ]);
        exit;
    }

    // validar senha
    if (!password_verify($senha, $user['senha'])) {
        echo json_encode([
            "success" => false,
            "message" => "Senha incorreta"
        ]);
        exit;
    }

    // =========================
    // 🔥 PRIMEIRO LOGIN CHECK
    // =========================
    if ($user['primeiro_login'] == 1) {

        // guardar sessão temporária
        $_SESSION['temp_user_id'] = $user['id'];
        $_SESSION['temp_tipo'] = $user['tipo_usuario'];
        $_SESSION['temp_nome'] = $user['nome'];

        echo json_encode([
            "success" => true,
            "primeiro_login" => true,
            "message" => "Primeiro login - deve alterar senha",
            "redirect" => "alterar_senha.php"
        ]);
        exit;
    }

    // sessão normal
    $_SESSION['usuario_id'] = $user['id'];
    $_SESSION['tipo_usuario'] = $user['tipo_usuario'];
    $_SESSION['nome'] = $user['nome'];

    // atualizar último acesso
    $pdo->prepare("
        UPDATE usuarios 
        SET ultimo_acesso = NOW()
        WHERE id = ?
    ")->execute([$user['id']]);

    // redirect seguro
    switch ($user['tipo_usuario']) {
        case 'paciente':
            $redirect = "paciente.php";
            break;
        case 'medico':
            $redirect = "medico.php";
            break;
        case 'admin':
            $redirect = "admin.php";
            break;
        case 'secretario':
            $redirect = "sec.php";
            break;
        default:
            $redirect = "index.html";
    }

    echo json_encode([
        "success" => true,
        "message" => "Login efetuado com sucesso",
        "redirect" => $redirect
    ]);

} catch (Throwable $e) {

    echo json_encode([
        "success" => false,
        "message" => "Erro interno no servidor"
    ]);
}