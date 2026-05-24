
<?php
header("Content-Type: application/json");
require "conexao.php";

$data = json_decode(file_get_contents("php://input"), true);

$bi = $data["bi"] ?? null;

if (!$bi) {
    echo json_encode(["exists" => false]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT id 
    FROM usuarios 
    WHERE bi = ?
    LIMIT 1
");

$stmt->execute([$bi]);

$exists = $stmt->fetch() ? true : false;

echo json_encode([
    "exists" => $exists
]);