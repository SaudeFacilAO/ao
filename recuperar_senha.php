<?php
require_once "phpconexao/nocache.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Saúde Fácil</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>

<main class="login-container">

    <div class="login-card">

        <div class="logo-section">

            <div class="logo-icon">
                <i class="fas fa-key"></i>
            </div>

            <h1>Recuperar Senha</h1>

            <p class="subtitle">
                Digite o seu e-mail para receber o código de recuperação.
            </p>

        </div>

        <form id="recuperarForm">

            <div class="form-group">

                <label>Email</label>

                <div class="input-field-wrapper">

                    <i class="fas fa-envelope"></i>

                    <input
                        type="email"
                        id="email"
                        placeholder="Digite o seu email"
                        required
                    >

                </div>

                <span
                    class="error-message"
                    id="email-error">
                </span>

            </div>

            <button
                type="submit"
                class="login-button">

                <i class="fas fa-paper-plane"></i>
                Enviar Código

            </button>

            <div
                id="statusMessage"
                class="status-message">
            </div>

        </form>

        <div class="signup-section">

            <p>
                <a href="login.php">
                    Voltar ao Login
                </a>
            </p>

        </div>

    </div>

     <!-- FOOTER -->
    <footer class="login-footer">
      <p>&copy; 2026 Saúde Fácil. Todos os direitos reservados.</p>
    </footer>

</main>

<script src="assets/js/recuperar_senha.js"></script>

</body>
</html>