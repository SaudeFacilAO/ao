
<?php
header("Content-Type: application/json");
require "conexao.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"] ?? null;

if (!$email) {
    echo json_encode(["exists" => false]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT id 
    FROM usuarios 
    WHERE email = ?
    LIMIT 1
");

$stmt->execute([$email]);

$exists = $stmt->fetch() ? true : false;

echo json_encode([
    "exists" => $exists
]);