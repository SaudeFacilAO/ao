<?php
header("Content-Type: application/json");
require "../../../phpconexao/conexao.php";

$data = json_decode(file_get_contents("php://input"), true);

// =========================
// TIPOS PERMITIDOS
// =========================
$tiposPermitidos = ["admin", "medico", "secretario"];

if (!in_array($data["tipo_usuario"] ?? null, $tiposPermitidos)) {
    echo json_encode([
        "status" => "erro",
        "msg" => "Tipo de usuário inválido"
    ]);
    exit;
}

// =========================
// VALIDAÇÕES BÁSICAS
// =========================
if (
    empty($data["nome"]) ||
    empty($data["email"]) ||
    empty($data["senha"])
) {
    echo json_encode([
        "status" => "erro",
        "msg" => "Preencha os campos obrigatórios"
    ]);
    exit;
}

// =========================
// VALIDAÇÕES ANGOLA
// =========================

// EMAIL (obrigatório minúsculas)
if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL) || $data["email"] !== strtolower($data["email"])) {
    echo json_encode(["status" => "erro", "msg" => "Email inválido (use apenas minúsculas)"]);
    exit;
}

// TELEFONE (9XXXXXXXX)
if (!empty($data["telefone"]) && !preg_match("/^9\d{8}$/", $data["telefone"])) {
    echo json_encode(["status" => "erro", "msg" => "Telefone inválido"]);
    exit;
}

// BI (00 + 12 caracteres restantes = 14 total)
if (!empty($data["bi"]) && !preg_match("/^00[A-Z0-9]{12}$/", strtoupper($data["bi"]))) {
    echo json_encode(["status" => "erro", "msg" => "BI inválido"]);
    exit;
}

// SENHA (4 dígitos)
if (!preg_match("/^\d{4}$/", $data["senha"])) {
    echo json_encode(["status" => "erro", "msg" => "Senha deve ter 4 dígitos"]);
    exit;
}

try {

    $pdo->beginTransaction();

    // =========================
    // VERIFICAR DUPLICADOS
    // =========================
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$data["email"]]);

    if ($stmt->fetch()) {
        echo json_encode(["status" => "erro", "msg" => "Email já existe"]);
        exit;
    }

    if (!empty($data["bi"])) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE bi = ?");
        $stmt->execute([$data["bi"]]);

        if ($stmt->fetch()) {
            echo json_encode(["status" => "erro", "msg" => "BI já existe"]);
            exit;
        }
    }

    // =========================
    // 1. CRIAR USUÁRIO
    // =========================
    $stmt = $pdo->prepare("
        INSERT INTO usuarios 
        (nome, email, telefone, genero, bi, senha, tipo_usuario, primeiro_login)
        VALUES (?, ?, ?, ?, ?, ?, ?, 1)
    ");

    $stmt->execute([
        $data["nome"],
        $data["email"],
        $data["telefone"],
        $data["genero"],
        $data["bi"],
        password_hash($data["senha"], PASSWORD_DEFAULT),
        $data["tipo_usuario"]
    ]);

    $usuario_id = $pdo->lastInsertId();

    // =========================
    // 2. MÉDICO (CRM AUTOMÁTICO)
    // =========================
    if ($data["tipo_usuario"] === "medico") {

        $prefixo = "HOSP-JM";

        $stmt = $pdo->prepare("
            SELECT crm
            FROM medicos
            WHERE hospital_id = ?
            ORDER BY id DESC
            LIMIT 1
        ");

        $stmt->execute([$data["hospital_id"]]);
        $ultimo = $stmt->fetchColumn();

        $numero = 1;

        if ($ultimo) {
            $partes = explode("-", $ultimo);
            $numero = intval(end($partes)) + 1;
        }

        $crm = $prefixo . "-" . str_pad($numero, 4, "0", STR_PAD_LEFT);

        $stmt = $pdo->prepare("
            INSERT INTO medicos 
            (usuario_id, crm, especialidade_id, hospital_id)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $usuario_id,
            $crm,
            $data["especialidade_id"],
            $data["hospital_id"]
        ]);
    }

    // =========================
    // 3. SECRETÁRIO (ID AUTOMÁTICO CORRIGIDO)
    // =========================
    if ($data["tipo_usuario"] === "secretario") {

        $prefixo = "HOSP-JM-SEC";

        $stmt = $pdo->prepare("
            SELECT id_funcionario
            FROM secretarios
            WHERE hospital_id = ?
            ORDER BY id DESC
            LIMIT 1
        ");

        $stmt->execute([$data["hospital_id"]]);
        $ultimo = $stmt->fetchColumn();

        $numero = 1;

        if ($ultimo) {
            $partes = explode("-", $ultimo);
            $numero = intval(end($partes)) + 1;
        }

        $idFuncionario = $prefixo . "-" . str_pad($numero, 4, "0", STR_PAD_LEFT);

        $stmt = $pdo->prepare("
            INSERT INTO secretarios 
            (usuario_id, hospital_id, id_funcionario)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $usuario_id,
            $data["hospital_id"],
            $idFuncionario
        ]);
    }

    // =========================
    // 4. ADMIN (SÓ USUÁRIO)
    // =========================

    $pdo->commit();

    echo json_encode([
        "status" => "ok",
        "msg" => "Usuário criado com sucesso"
    ]);

} catch (Exception $e) {

    $pdo->rollBack();

    echo json_encode([
        "status" => "erro",
        "msg" => $e->getMessage()
    ]);
}