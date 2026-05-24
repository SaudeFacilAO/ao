<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
// =====================================================
// VERIFICA LOGIN
// =====================================================
if (!isset($_SESSION['usuario_id'])) {

    // guarda tentativa (opcional)
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

    header("Location: /SaudeFacil/MediLab-1.0.0/index.html");
    exit;
}

// tempo de sessão (30 min)
$tempoMaximo = 1800;

if (isset($_SESSION['last_activity'])) {

    if (time() - $_SESSION['last_activity'] > $tempoMaximo) {

        session_destroy();
        header("Location: /SaudeFacil/MediLab-1.0.0/index.html");
        exit;
    }
}

$_SESSION['last_activity'] = time();