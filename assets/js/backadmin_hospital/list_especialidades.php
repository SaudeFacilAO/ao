<?php
header('Content-Type: application/json');
require '../../../phpconexao/conexao.php';

try {
    $stmt = $pdo->query("SELECT * FROM especialidades ORDER BY id ASC");
    $dados = $stmt->fetchAll();

    echo json_encode($dados);

} catch (PDOException $e) {
    echo json_encode([]);
}