console.log("recuperar_senha.js carregado");

document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("recuperarForm");
    const emailInput = document.getElementById("email");

    const emailError = document.getElementById("email-error");
    const statusMessage = document.getElementById("statusMessage");

    if (!form) return;

    // ==========================================
    // VALIDAR EMAIL
    // ==========================================
    function validarEmail(email) {

        const regex =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        return regex.test(email);
    }

    // ==========================================
    // LIMPAR ERROS AO DIGITAR
    // ==========================================
    emailInput.addEventListener("input", () => {

        emailError.textContent = "";

        statusMessage.textContent = "";
        statusMessage.className = "status-message";
    });

    // ==========================================
    // ENVIAR FORMULÁRIO
    // ==========================================
    form.addEventListener("submit", async (e) => {

        e.preventDefault();

        emailError.textContent = "";

        statusMessage.textContent = "";
        statusMessage.className = "status-message";

        const email = emailInput.value.trim();

        // ==============================
        // VALIDAÇÕES
        // ==============================

        if (!email) {

            emailError.textContent =
                "Digite o seu email.";

            emailInput.focus();

            return;
        }

        if (!validarEmail(email)) {

            emailError.textContent =
                "Digite um email válido.";

            emailInput.focus();

            return;
        }

        const submitBtn =
            form.querySelector("button[type='submit']");

        const textoOriginal =
            submitBtn.innerHTML;

        submitBtn.disabled = true;

        submitBtn.innerHTML =
            '<i class="fas fa-spinner fa-spin"></i> Enviando...';

        try {

            const response = await fetch(
                "phpconexao/enviar_codigo.php",
                {
                    method: "POST",
                    headers: {
                        "Content-Type":
                            "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                        email: email
                    })
                }
            );

            const texto =
                await response.text();

            let dados;

            try {

                dados = JSON.parse(texto);

            } catch (erro) {

                console.error(
                    "Resposta inválida:",
                    texto
                );

                statusMessage.textContent =
                    "Erro interno do servidor.";

                statusMessage.classList.add("error");

                return;
            }

            // ==========================
            // SUCESSO
            // ==========================

            if (dados.success) {

                statusMessage.textContent =
                    dados.message;

                statusMessage.classList.add("success");

                emailInput.value = "";

                // redireciona para validar código
                if (dados.redirect) {

                    setTimeout(() => {

                        window.location.href =
                            dados.redirect;

                    }, 1500);
                }

            }

            // ==========================
            // ERRO
            // ==========================

            else {

                statusMessage.textContent =
                    dados.message;

                statusMessage.classList.add("error");
            }

        } catch (error) {

            console.error(error);

            statusMessage.textContent =
                "Erro de conexão com o servidor.";

            statusMessage.classList.add("error");

        } finally {

            submitBtn.disabled = false;

            submitBtn.innerHTML =
                textoOriginal;
        }
    });
});