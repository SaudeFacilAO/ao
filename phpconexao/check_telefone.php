
<?php
header("Content-Type: application/json");
require "conexao.php";

$data = json_decode(file_get_contents("php://input"), true);

$telefone = $data["telefone"] ?? null;

if (!$telefone) {
    echo json_encode(["exists" => false]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT id 
    FROM usuarios 
    WHERE telefone = ?
    LIMIT 1
");

$stmt->execute([$telefone]);

$exists = $stmt->fetch() ? true : false;

echo json_encode([
    "exists" => $exists
]);