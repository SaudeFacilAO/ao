const form = document.getElementById("resetForm");
const senha = document.getElementById("senha");
const confirmar = document.getElementById("confirmar");
const btn = document.getElementById("btnSalvar");
const status = document.getElementById("statusMessage");

// =========================
// ESTADO
// =========================
let estado = {
    senhaValida: false,
    confirmarValida: false
};

// =========================
// SENHAS FRACAS
// =========================
const senhasFracas = [
    "12345678",
    "87654321",
    "11111111",
    "00000000",
    "qwerty",
    "abcdef",
    "password"
];

// =========================
// VALIDAR SENHA FORTE
// =========================
function validarSenha(v) {
    if (senhasFracas.includes(v.toLowerCase())) return false;

    return (
        v.length >= 8 &&
        v.length <= 10 &&
        /[A-Z]/.test(v) &&
        /[a-z]/.test(v) &&
        /[0-9]/.test(v) &&
        /[^A-Za-z0-9]/.test(v)
    );
}

// =========================
// ATUALIZAR BOTÃO
// =========================
function atualizarBotao() {
    btn.disabled = !(estado.senhaValida && estado.confirmarValida);
}

// =========================
// FUNÇÃO DE VALIDAÇÃO DE CONFIRMAÇÃO
// =========================
function validarConfirmacao() {
    const s1 = senha.value;
    const s2 = confirmar.value;

    if (s2.length === 0) {
        estado.confirmarValida = false;
        document.getElementById("confirmar-error").innerText = "";
        atualizarBotao();
        return;
    }

    if (s1 !== s2) {
        estado.confirmarValida = false;
        document.getElementById("confirmar-error").innerText =
            "As senhas não coincidem";
    } else {
        estado.confirmarValida = true;
        document.getElementById("confirmar-error").innerText = "";
    }
}

// =========================
// INPUT SENHA
// =========================
senha.addEventListener("input", () => {
    let v = senha.value;

    // limitar 10 caracteres
    if (v.length > 10) {
        v = v.slice(0, 10);
        senha.value = v;
    }

    // validar senha forte
    estado.senhaValida = validarSenha(v);

    document.getElementById("senha-error").innerText =
        estado.senhaValida
            ? ""
            : "Senha fraca (8-10 chars, letras, número e símbolo)";

    // atualizar confirmação caso exista
    validarConfirmacao();
    atualizarBotao();
});

// =========================
// INPUT CONFIRMAR
// =========================
confirmar.addEventListener("input", () => {
    let v = confirmar.value;

    if (v.length > 10) {
        v = v.slice(0, 10);
        confirmar.value = v;
    }

    validarConfirmacao();
    atualizarBotao();
});

// =========================
// SUBMIT
// =========================
form.addEventListener("submit", async (e) => {
    e.preventDefault();
    if (btn.disabled) return;

    btn.disabled = true;
    status.innerText = "Atualizando senha...";

    try {
        const res = await fetch("/SaudeFacil/MediLab-1.0.0/phpconexao/alterar_senha.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ senha: senha.value })
        });

        // caso o PHP retorne HTML por erro, mostramos no console
        const text = await res.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch {
            console.error("Resposta inválida do servidor:", text);
            status.innerText = "Erro ao atualizar senha (resposta inválida)";
            btn.disabled = false;
            return;
        }

        if (data.status === "ok") {
            status.innerText = "Senha atualizada com sucesso!";
            setTimeout(() => window.location.href = data.redirect, 1200);
        } else {
            status.innerText = data.msg || "Erro ao atualizar senha";
            btn.disabled = false;
        }

    } catch (err) {
        console.error(err);
        status.innerText = "Erro de comunicação com servidor";
        btn.disabled = false;
    }
});