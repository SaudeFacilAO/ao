<?php

session_start();

header('Content-Type: application/json; charset=UTF-8');

require_once "conexao.php";

/*
|--------------------------------------------------------------------------
| PHPMailer
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {

    // =====================================================
    // VALIDAR EMAIL
    // =====================================================

    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {

        echo json_encode([
            "success" => false,
            "message" => "Digite o email."
        ]);

        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo json_encode([
            "success" => false,
            "message" => "Email inválido."
        ]);

        exit;
    }

    // =====================================================
    // PROCURAR UTILIZADOR
    // =====================================================

    $stmt = $pdo->prepare("
        SELECT id, nome, email
        FROM usuarios
        WHERE email = ?
        LIMIT 1
    ");

    $stmt->execute([$email]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {

        echo json_encode([
            "success" => false,
            "message" => "Email não encontrado."
        ]);

        exit;
    }

    // =====================================================
    // GERAR CÓDIGO
    // =====================================================

    $codigo = str_pad(
        random_int(0, 999999),
        6,
        '0',
        STR_PAD_LEFT
    );

    // validade 15 minutos

    $expira_em = date(
        'Y-m-d H:i:s',
        strtotime('+15 minutes')
    );

    // =====================================================
    // INVALIDAR CÓDIGOS ANTIGOS
    // =====================================================

    $stmt = $pdo->prepare("
        UPDATE recuperacao_senha
        SET usado = 1
        WHERE usuario_id = ?
    ");

    $stmt->execute([
        $usuario['id']
    ]);

    // =====================================================
    // GUARDAR NOVO CÓDIGO
    // =====================================================

    $stmt = $pdo->prepare("
        INSERT INTO recuperacao_senha
        (
            usuario_id,
            codigo,
            expira_em
        )
        VALUES
        (
            ?,
            ?,
            ?
        )
    ");

    $stmt->execute([
        $usuario['id'],
        $codigo,
        $expira_em
    ]);

    // =====================================================
    // ENVIAR EMAIL
    // =====================================================

    $mail = new PHPMailer(true);

    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    $mail->Username = 'SEUEMAIL@gmail.com';

    $mail->Password = 'SUA_APP_PASSWORD';

    $mail->SMTPSecure =
        PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;

    $mail->CharSet = 'UTF-8';

    $mail->setFrom(
        'SEUEMAIL@gmail.com',
        'Saúde Fácil'
    );

    $mail->addAddress(
        $usuario['email'],
        $usuario['nome']
    );

    $mail->isHTML(true);

    $mail->Subject =
        'Recuperação de Senha - Saúde Fácil';

    $mail->Body = "
        <h2>Recuperação de Senha</h2>

        <p>Olá, {$usuario['nome']}.</p>

        <p>O seu código de recuperação é:</p>

        <h1 style='letter-spacing:5px'>
            {$codigo}
        </h1>

        <p>
            Este código expira em
            15 minutos.
        </p>

        <p>
            Caso não tenha solicitado
            esta recuperação,
            ignore este email.
        </p>
    ";

    $mail->send();

    // =====================================================
    // GUARDAR EMAIL NA SESSÃO
    // =====================================================

    $_SESSION['email_recuperacao'] =
        $usuario['email'];

    // =====================================================
    // RESPOSTA
    // =====================================================

    echo json_encode([
        "success" => true,
        "message" => "Código enviado para o email.",
        "redirect" => "verificar_codigo.php"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => "Erro ao enviar email."
    ]);
}