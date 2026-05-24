<?php

require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    $mail->Username = 'SEUEMAIL@gmail.com';
    $mail->Password = 'SUA_APP_PASSWORD';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('SEUEMAIL@gmail.com', 'Teste Sistema');
    $mail->addAddress('SEU_EMAIL_DE_TESTE@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Teste PHPMailer';
    $mail->Body = '<h2>Se recebeste isto, está a funcionar 👍</h2>';

    $mail->send();

    echo "Email enviado com sucesso!";

} catch (Exception $e) {

    echo "Erro: " . $mail->ErrorInfo;
}