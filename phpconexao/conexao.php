<?php
// ============================================
// CONEXÃO SEGURA COM A BASE DE DADOS (PDO)
// ============================================

$host = "localhost";
$db   = "saudefacil";
$user = "root";      // padrão do XAMPP
$pass = "12345678";          // vazio por padrão no XAMPP

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // mostra erros reais
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // retorna array associativo
            PDO::ATTR_EMULATE_PREPARES   => false                   // mais seguro
        ]
    );

} catch (PDOException $e) {
    // Nunca mostrar erro completo em produção
    die(json_encode([
        "status" => "erro",
        "mensagem" => "Erro ao conectar com a base de dados."
    ]));
}