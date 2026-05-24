console.log("JS carregou");

document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("loginForm");
    const loginInput = document.getElementById("login");
    const passwordInput = document.getElementById("password");

    const emailError = document.getElementById("email-error");
    const passwordError = document.getElementById("password-error");
    const statusMessage = document.getElementById("statusMessage");
    const togglePassword = document.getElementById("togglePassword");

    // =========================
    // PROTEÇÃO (EVITA ERROS SILENCIOSOS)
    // =========================
    if (!form || !loginInput || !passwordInput) {
        console.error("Elementos do login não encontrados no HTML");
        return;
    }

    

    // =========================
    // SUBMIT LOGIN
    // =========================
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // limpar mensagens
        if (emailError) emailError.textContent = "";
        if (passwordError) passwordError.textContent = "";
        if (statusMessage) {
            statusMessage.textContent = "";
            statusMessage.className = "status-message";
        }

        const login = loginInput.value.trim();
        const password = passwordInput.value.trim();

        let hasError = false;

        if (!login) {
            if (emailError) emailError.textContent = "Digite o email ou telefone";
            hasError = true;
        }

        if (!password) {
            if (passwordError) passwordError.textContent = "Digite a senha";
            hasError = true;
        }

        if (hasError) return;

        try {

            const response = await fetch("phpconexao/login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    login: login,
                    password: password
                })
            });

            const text = await response.text();

            let data;
            try {
                data = JSON.parse(text);
            } catch (err) {
                console.error("RESPOSTA DO PHP NÃO É JSON:", text);
                if (statusMessage) {
                    statusMessage.textContent = "Erro no servidor (PHP inválido)";
                    statusMessage.classList.add("error");
                }
                return;
            }

            if (data.success) {

                if (statusMessage) {
                    statusMessage.textContent = data.message;
                    statusMessage.classList.add("success");
                }

                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 800);

            } else {

                if (statusMessage) {
                    statusMessage.textContent = data.message;
                    statusMessage.classList.add("error");
                }
            }

        } catch (error) {
            console.error(error);

            if (statusMessage) {
                statusMessage.textContent = "Erro de conexão com o servidor.";
                statusMessage.classList.add("error");
            }
        }
    });

});