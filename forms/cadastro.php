<?php
require_once '../php/conexao.php'
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $nome = filter_var($_POST['nome'], FILTER_SANITIZE_STRING);
    $telefone = filter_var($_POST['telefone'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $senha = md5($_POST['senha']);

    $sql = "INSERT INTO usuario(nome, email, telefone, senha) VALUES ($nome, $email, $telefone, $senha)";
    if($conn->query($sql)) {
        header("Location:../paciente.html");
    }
    else {
        echo "Erro ao inserir mensagem: ".$conn->error;
    }
    
}
?>