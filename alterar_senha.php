

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <metaname="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="theme-color"
        content="#0D5C8F"
    >

    <title>Saúde Fácil - Alterar Senha</title>

    <!-- ICONS -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    <!-- CSS -->
    <link
        rel="stylesheet"
        href="assets/css/alterar_senha.css"
    >

</head>

<body>

<main class="login-container">

    <div class="login-card">

        <!-- LOGO -->
        <div class="logo-section">

            <div class="logo-icon">
                <i class="fas fa-lock"></i>
            </div>

            <h1>Definir Nova Senha</h1>

            <p class="subtitle">
                Primeiro acesso ao sistema
            </p>

        </div>

        <!-- FORM -->
        <form
            id="resetForm"
            class="login-form"
            novalidate
        >

            <!-- NOVA SENHA -->
            <div class="form-group">

                <label for="senha">
                    Nova Senha
                </label>

                <div class="input-field-wrapper">

                    <i class="fas fa-key"></i>

                    <input
                        type="password"
                        id="senha"
                        placeholder="Digite sua nova senha"
                        autocomplete="new-password"
                        m
                        required
                    >

                </div>

                <!-- ERRO -->
                <span
                    class="error-message"
                    id="senha-error"
                ></span>

            </div>

            <!-- CONFIRMAR -->
            <div class="form-group">

                <label for="confirmar">
                    Confirmar Senha
                </label>

                <div class="input-field-wrapper">

                    <i class="fas fa-key"></i>

                    <input
                        type="password"
                        id="confirmar"
                        placeholder="Repita a senha"
                        autocomplete="new-password"
                        required
                    >

                </div>

                <!-- ERRO -->
                <span
                    class="error-message"
                    id="confirmar-error"
                ></span>

            </div>

            <!-- STATUS -->
            <div
                id="statusMessage"
                class="status-message"
            ></div>

            <!-- BOTÃO -->
            <button
                type="submit"
                class="login-button"
                id="btnSalvar"
                
            >

                <i class="fas fa-save"></i>

                Atualizar Senha

            </button>

        </form>

    </div>

     <!-- FOOTER -->
    <footer class="login-footer">
      <p>&copy; 2026 Saúde Fácil. Todos os direitos reservados.</p>
    </footer>

</main>

<!-- JS -->
<script src="assets/js/alterar_senha.js"></script>

</body>
</html>