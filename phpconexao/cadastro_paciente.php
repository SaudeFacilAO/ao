<?php
session_start();

header("Content-Type: application/json; charset=UTF-8");

require_once "conexao.php";

try {

    // =====================================================
    // RECEBER JSON
    // =====================================================
    $json = file_get_contents("php://input");
    $dados = json_decode($json, true);

    if (!$dados) {
        echo json_encode([
            "status" => "error",
            "msg" => "Dados inválidos"
        ]);
        exit;
    }

    // =====================================================
    // CAMPOS
    // =====================================================
    $nome = trim($dados['nome'] ?? '');
    $bi = trim($dados['bi'] ?? '');
    $dataNascimento = trim($dados['dataNascimento'] ?? '');
    $genero = strtolower(trim($dados['genero'] ?? ''));

    $email = trim($dados['email'] ?? '');
    $telefone = trim($dados['telefone'] ?? '');

    $provincia = trim($dados['provincia'] ?? '');
    $municipio = trim($dados['municipio'] ?? '');
    $rua = trim($dados['rua'] ?? '');

    $senha = $dados['senha'] ?? '';

    // =====================================================
    // TERMOS (IMPORTANTE)
    // =====================================================
    $termosAceitos = !empty($dados['termos_aceitos']) ? 1 : 0;
    $privacidadeAceita = !empty($dados['privacidade_aceita']) ? 1 : 0;

    // =====================================================
    // VALIDAÇÕES
    // =====================================================
    if (
        empty($nome) ||
        empty($bi) ||
        empty($dataNascimento) ||
        empty($genero) ||
        empty($senha)
    ) {
        echo json_encode([
            "status" => "error",
            "msg" => "Preencha todos os campos obrigatórios"
        ]);
        exit;
    }

    // BI
    if (!preg_match('/^00\d{7}[A-Z]{2}\d{3}$/', $bi)) {
        echo json_encode([
            "status" => "error",
            "msg" => "BI inválido"
        ]);
        exit;
    }

    if (empty($email) && empty($telefone)) {
        echo json_encode([
            "status" => "error",
            "msg" => "Informe e-mail ou telefone"
        ]);
        exit;
    }

    // =====================================================
    // EMAIL
    // =====================================================
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "msg" => "E-mail inválido"
        ]);
        exit;
    }

    // =====================================================
    // TELEFONE LIMPO
    // =====================================================
    $telefoneLimpo = null;

    if (!empty($telefone)) {
        $telefoneLimpo = preg_replace('/\D/', '', $telefone);

        if (strlen($telefoneLimpo) != 9) {
            echo json_encode([
                "status" => "error",
                "msg" => "Telefone inválido"
            ]);
            exit;
        }
    }

    // =====================================================
    // SENHA
    // =====================================================
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // =====================================================
    // ENDEREÇO
    // =====================================================
    $endereco = "Luanda";

    if (!empty($municipio)) {
        $endereco .= ", " . $municipio;
    }

    if (!empty($rua)) {
        $endereco .= ", " . $rua;
    }

    // =====================================================
    // TRANSACTION
    // =====================================================
    $pdo->beginTransaction();

    // =====================================================
    // USUARIO
    // =====================================================
    $stmt = $pdo->prepare("
        INSERT INTO usuarios (
            nome, email, senha, telefone, genero, tipo_usuario, bi
        ) VALUES (
            ?, ?, ?, ?, ?, 'paciente', ?
        )
    ");

    $stmt->execute([
        $nome,
        $email ?: null,
        $senhaHash,
        $telefoneLimpo,
        $genero,
        $bi
    ]);

    $usuarioId = $pdo->lastInsertId();

    // =====================================================
    // PACIENTE
    // =====================================================
    $stmt = $pdo->prepare("
        INSERT INTO pacientes (
            usuario_id,
            data_nascimento,
            endereco,
            numero_utente,
            consentimento_dados,
            data_consentimento
        ) VALUES (
            ?, ?, ?, ?, 1, NOW()
        )
    ");

    $numeroUtente = "HOSPJS-PAC-" . str_pad($usuarioId, 6, "0", STR_PAD_LEFT);

    $stmt->execute([
        $usuarioId,
        $dataNascimento,
        $endereco,
        $numeroUtente
    ]);

    $pacienteId = $pdo->lastInsertId();

    // =====================================================
    // CONSENTIMENTOS (CORREÇÃO PRINCIPAL)
    // =====================================================
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    $stmt = $pdo->prepare("
        INSERT INTO consentimentos (
            paciente_id,
            termos_aceitos,
            privacidade_aceita,
            versao_termos,
            versao_privacidade,
            ip,
            user_agent
        ) VALUES (
            ?, ?, ?, 'v1.0', 'v1.0', ?, ?
        )
    ");

    $stmt->execute([
        $pacienteId,
        $termosAceitos,
        $privacidadeAceita,
        $ip,
        $userAgent
    ]);

    // =====================================================
    // FINAL
    // =====================================================
     // =====================================================
// SESSÃO AUTOMÁTICA APÓS CADASTRO
// =====================================================
$_SESSION['usuario_id'] = $usuarioId;

$_SESSION['paciente_id'] = $pacienteId;

$_SESSION['tipo_usuario'] = 'paciente';

$_SESSION['nome'] = $nome;

    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "msg" => "Conta criada com sucesso",
        "redirect" => "paciente.php"
    ]);

} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        "status" => "error",
        "msg" => $e->getMessage()
    ]);
}
?>