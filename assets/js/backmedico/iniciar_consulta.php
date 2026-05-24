<?php

date_default_timezone_set('Africa/Luanda');
session_start();


header("Content-Type: application/json; charset=UTF-8");

require_once "../../../phpconexao/conexao.php";

try {

    // =====================================================
    // SEGURANÇA (SÓ LOGADO)
    // =====================================================
    if (!isset($_SESSION['usuario_id'])) {

        echo json_encode([
            "status" => "error",
            "msg" => "Não autenticado"
        ]);

        exit;
    }

    $usuario_id   = $_SESSION['usuario_id'];
    $tipo_usuario = $_SESSION['tipo_usuario'];
    $nome         = $_SESSION['nome'];

    $consulta_id = $_POST['consulta_id'] ?? null;

    if (!$consulta_id) {

        echo json_encode([
            "status" => "error",
            "msg" => "Consulta inválida"
        ]);

        exit;
    }

    // =====================================================
    // VALIDAR SE CONSULTA EXISTE
    // =====================================================
    $stmt = $pdo->prepare("
        SELECT 
            t.link,
            c.id,
            c.estado,
            c.data_hora_inicio
        FROM consultas c
        INNER JOIN teleconsultas t
            ON t.consulta_id = c.id
        WHERE c.id = :id
        LIMIT 1
    ");

    $stmt->execute([
        ':id' => $consulta_id
    ]);

    $tele = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tele) {

        echo json_encode([
            "status" => "error",
            "msg" => "Consulta não encontrada"
        ]);

        exit;
    }

    // =====================================================
    // 🔥 VALIDAÇÃO DE DATA E HORA (CORRIGIDA)
    // =====================================================
    

    if (empty($tele['data_hora_inicio'])) {

        echo json_encode([
            "status" => "error",
            "msg" => "Consulta sem horário definido"
        ]);

        exit;
    }

    try {
        $consultaDataHora = new DateTime($tele['data_hora_inicio']);
    } catch (Exception $e) {

        echo json_encode([
            "status" => "error",
            "msg" => "Formato de data inválido na consulta"
        ]);

        exit;
    }

    $agora = new DateTime();

    // tolerância: 10 min antes e 60 min depois
    $inicio = clone $consultaDataHora;
    $inicio->modify('-10 minutes');

    $fim = clone $consultaDataHora;
    $fim->modify('+60 minutes');

    if ($agora < $inicio || $agora > $fim) {

        echo json_encode([
            "status" => "error",
            "msg" => "Só pode entrar no horário da consulta"
        ]);

        exit;
    }

    // =====================================================
    // CONTROLE DE ACESSO
    // =====================================================

    if (
        $tipo_usuario !== "medico" &&
        $tele['estado'] === 'agendada'
    ) {

        echo json_encode([
            "status" => "error",
            "msg" => "Aguardando médico iniciar a consulta"
        ]);

        exit;
    }

    $link = $tele['link'];

    // =====================================================
    // CONFIGURAÇÕES JITSI
    // =====================================================

    $params  = "#config.prejoinPageEnabled=false";
    $params .= "&config.requireDisplayName=false";
    $params .= "&config.enableWelcomePage=false";
    $params .= "&config.startWithAudioMuted=false";
    $params .= "&config.startWithVideoMuted=false";
    $params .= "&config.disableModeratorIndicator=true";
    $params .= "&config.enableClosePage=false";

    // =====================================================
    // MÉDICO
    // =====================================================

    if ($tipo_usuario === "medico") {

        $link .= $params .
                 "&userInfo.displayName=Dr%20" .
                 urlencode($nome);

        $stmt = $pdo->prepare("
            UPDATE consultas
            SET estado = 'em_andamento'
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $consulta_id
        ]);

        $stmt = $pdo->prepare("
            UPDATE teleconsultas
            SET estado = 'ativa',
                iniciado_em = NOW()
            WHERE consulta_id = :id
        ");

        $stmt->execute([
            ':id' => $consulta_id
        ]);
    }

    // =====================================================
    // PACIENTE
    // =====================================================

    else {

        $link .= $params .
                 "&userInfo.displayName=" .
                 urlencode($nome);
    }

    // =====================================================
    // RESPOSTA
    // =====================================================

    echo json_encode([
        "status" => "success",
        "link"   => $link,
        "tipo"   => $tipo_usuario
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "msg" => "Erro no servidor"
    ]);
}
?>