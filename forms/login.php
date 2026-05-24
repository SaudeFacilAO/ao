<?php
require_once '../php/conexao.php'
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logar'])) {
    $email = filter_input($_POST['email'], 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];

    $sql = "SELECT email, senha FROM paciente WHERE email LIKE @email";
    $result = $conn->prepare($sql);
    $result('@email', $email);
    
    $_SESSION['nome'] = $email;
    $_SESSION['senha'] = $senha;
    header('Location:../paciente.html');
}

?>