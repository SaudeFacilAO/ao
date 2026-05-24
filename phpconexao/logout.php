<?php

session_start();

// ==========================================
// LIMPAR SESSÃO
// ==========================================
$_SESSION = [];

// ==========================================
// APAGAR COOKIE DA SESSÃO
// ==========================================
if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// ==========================================
// DESTRUIR SESSÃO
// ==========================================
session_destroy();

// ==========================================
// REDIRECIONAR
// ==========================================
header("Location: /SaudeFacil/MediLab-1.0.0/index.html");

exit;
?>