

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Saúde Fácil - Acesso seguro à sua conta">
  <meta name="theme-color" content="#0D5C8F">
  <title>Saúde Fácil - Login</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>

  <main class="login-container">

    <div class="login-card">

      <!-- LOGO -->
      <div class="logo-section">

        <!-- LOGO PRINCIPAL -->
        <div class="logo-icon">
          <i class="fas fa-heartbeat"></i>
        </div>

        <h1>Saúde Fácil</h1>
        <p class="subtitle">Acesso seguro à sua conta</p>

      </div>

      <!-- FORMULÁRIO -->
      <form id="loginForm" class="login-form" novalidate>

        <!-- EMAIL / TELEFONE -->
        <div class="form-group">

          <label for="email">Email ou Telefone</label>

          <div class="input-field-wrapper">

            <i class="fas fa-user"></i>

            <input
              type="text"
              id="login"
              name="login"
              placeholder="Digite seu email ou telefone"
              required
              autocomplete="username"
            >

          </div>

          <span class="error-message" id="email-error"></span>

        </div>

        <!-- SENHA -->
        <div class="form-group">

          <div class="label-with-link">
            <label for="password">Senha</label>
            <a href="recuperar_senha.php" class="forgot-password-link">Esqueceu?</a>
          </div>

          <div class="input-field-wrapper">

            <i class="fas fa-lock"></i>

            <input
              type="password"
              id="password"
              name="password"
              placeholder="Digite sua senha"
              required
              autocomplete="current-password"
            >

            
          </div>

          <span class="error-message" id="password-error"></span>

        </div>

        <!-- LEMBRAR-ME -->
        <div class="remember-checkbox">

          <input type="checkbox" id="remember" name="remember">

          <label for="remember">Lembrar-me neste dispositivo</label>

        </div>

        <!-- BOTÃO -->
        <button type="submit" class="login-button">
  <i class="fas fa-sign-in-alt"></i> Entrar
</button>

        <!-- STATUS -->
        <div id="statusMessage" class="status-message"></div>

      </form>

      <!-- CRIAR CONTA -->
      <div class="signup-section">
        <p>Não tem conta? <a href="cadastro.html">Criar uma conta</a></p>
      </div>

    </div>

    <!-- FOOTER -->
    <footer class="login-footer">
      <p>&copy; 2026 Saúde Fácil. Todos os direitos reservados.</p>
    </footer>

  </main>

  <script src="assets/js/login.js"></script>

</body>

</html>