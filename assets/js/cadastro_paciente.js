// =====================================================
// CONTROLE
// =====================================================
let touched = {};

// =====================================================
// ELEMENTOS
// =====================================================
const biInput = document.getElementById('bi');

const telefoneInput =
    document.getElementById('telefone');

const nextStep1Btn =
    document.getElementById('nextStep1Btn');

const nextStep2Btn =
    document.getElementById('nextStep2Btn');

const nextStep3Btn =
    document.getElementById('nextStep3Btn');

const confirmarBtn =
    document.getElementById('confirmarBtn');

// =====================================================
// MASK BI
// =====================================================

// =========================
// ELEMENTOS
// =========================
const errorBi = document.getElementById("errorBi");

// =========================
// MASK BI
// =========================
function maskBI(value) {
    return value
        .replace(/[^A-Za-z0-9]/g, "")
        .toUpperCase()
        .slice(0, 14);
}

// =========================
// VERIFICAR BI NA BD
// =========================
async function checkBI(bi) {

    try {

        const res = await fetch(
            "phpconexao/check_bi.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ bi })
            }
        );

        const data = await res.json();

        // SE EXISTE NA BD
        if (data.exists === true) {
            errorBi.innerText = "BI já existe no sistema";
            return false;
        }

        errorBi.innerText = "";
        return true;

    } catch (err) {
        console.error("Erro ao verificar BI:", err);
        return false;
    }
}

// =========================
// INPUT BI
// =========================
biInput.addEventListener("input", async (e) => {

    let v = maskBI(e.target.value);
    e.target.value = v;

    // se vazio
    if (v.length === 0) {
        errorBi.innerText = "";
        return;
    }

    // validação básica de formato
    const formatoValido = /^00[A-Z0-9]{12}$/.test(v);

    if (!formatoValido) {
        errorBi.innerText = "BI inválido (deve começar com 00 e ter 14 caracteres)";
        return;
    }

    // verifica na BD
    await checkBI(v);
});

// =====================================================
// VALIDAR IDADE (18+)
// =====================================================
function validarIdade(dataNascimento) {

    if (!dataNascimento) return false;

    const hoje = new Date();

    const nascimento = new Date(dataNascimento);

    let idade =
        hoje.getFullYear() -
        nascimento.getFullYear();

    const mes =
        hoje.getMonth() -
        nascimento.getMonth();

    // ajustar idade se ainda não fez aniversário
    if (
        mes < 0 ||
        (mes === 0 &&
        hoje.getDate() < nascimento.getDate())
    ) {
        idade--;
    }

    return idade >= 18;
}


// =====================================================
// ELEMENTOS EMAIL
// =====================================================
const emailInput =
    document.getElementById('email');

const errorEmail =
    document.getElementById('errorEmail');

// =====================================================
// VALIDAR FORMATO EMAIL
// =====================================================
function validarEmail(email) {

    // apenas minúsculas
    const regex =
        /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;

    return regex.test(email);
}

// =====================================================
// VERIFICAR EMAIL NA BD
// =====================================================
async function checkEmail(email) {

    try {

        const res = await fetch(
            'phpconexao/check_email.php',
            {
                method: 'POST',

                headers: {
                    'Content-Type':
                        'application/json'
                },

                body: JSON.stringify({
                    email
                })
            }
        );

        const data = await res.json();

        // EMAIL EXISTE
        if (data.exists === true) {

            showError(
                'errorEmail',
                'Email já existe no sistema'
            );

            return false;
        }

        showError('errorEmail', '');

        return true;

    } catch (err) {

        console.error(
            'Erro ao verificar email:',
            err
        );

        return false;
    }
}

// =====================================================
// INPUT EMAIL
// =====================================================
emailInput?.addEventListener(
    'input',
    async (e) => {

        let v =
            e.target.value.trim();

        // bloquear maiúsculas
        v = v.toLowerCase();

        e.target.value = v;

        // vazio
        if (!v) {

            showError(
                'errorEmail',
                ''
            );

            return;
        }

        // formato inválido
        if (!validarEmail(v)) {

            showError(
                'errorEmail',
                'Email inválido'
            );

            return;
        }

        // limpar erro
        showError(
            'errorEmail',
            ''
        );

        // verificar BD
        await checkEmail(v);

        validarTudo();
    }
);



// =====================================================
// MASK TELEFONE
// =====================================================
function maskTelefone(value) {

    let c = value.replace(/\D/g, '');

    if (c.length <= 3)
        return c;

    if (c.length <= 6)
        return c.replace(
            /(\d{3})(\d{1,3})/,
            '$1 $2'
        );

    return c
        .replace(
            /(\d{3})(\d{3})(\d{1,3})/,
            '$1 $2 $3'
        )
        .slice(0, 11);
}


// =====================================================
// VALIDAR TELEFONE
// =====================================================
function validarTelefone(telefone) {

    // remove espaços
    telefone = telefone.replace(/\D/g, '');

    // Angola → 9 dígitos
    // começa com 9
    return /^9\d{8}$/.test(telefone);
}

// =====================================================
// VERIFICAR TELEFONE NA BD
// =====================================================
async function checkTelefone(telefone) {

    try {

        const res = await fetch(
            'phpconexao/check_telefone.php',
            {
                method: 'POST',

                headers: {
                    'Content-Type':
                        'application/json'
                },

                body: JSON.stringify({
                    telefone
                })
            }
        );

        const data = await res.json();

        // telefone existe
        if (data.exists === true) {

            showError(
                'errorTelefone',
                'Telefone já existe no sistema'
            );

            return false;
        }

        showError(
            'errorTelefone',
            ''
        );

        return true;

    } catch (err) {

        console.error(
            'Erro ao verificar telefone:',
            err
        );

        return false;
    }
}


// =====================================================
// MASKS
// =====================================================
biInput?.addEventListener('input', e => {

    e.target.value =
        maskBI(e.target.value);

    validarTudo();
});

// =====================================================
// INPUT TELEFONE
// =====================================================
telefoneInput?.addEventListener(
    'input',
    async e => {

        let v =
            maskTelefone(e.target.value);

        e.target.value = v;

        // apenas números
        const telefone =
            v.replace(/\D/g, '');

        // vazio
        if (!telefone) {

            showError(
                'errorTelefone',
                ''
            );

            return;
        }

        // formato inválido
        if (!validarTelefone(telefone)) {

            showError(
                'errorTelefone',
                'Telefone inválido'
            );

            validarTudo();

            return;
        }

        // limpar erro local
        showError(
            'errorTelefone',
            ''
        );

        // verificar na BD
        await checkTelefone(telefone);

        validarTudo();
    }
);

// =====================================================
// MOSTRAR ERRO
// =====================================================
function showError(id, msg) {

    const el =
        document.getElementById(id);

    if (el)
        el.innerText = msg;
}

// =====================================================
// VALIDAR BI
// =====================================================
function validarBI(bi) {

    // deve ter exatamente 14 caracteres
    if (bi.length !== 14) return false;

    // deve começar com 00
    if (!bi.startsWith("00")) return false;

    // estrutura completa validada
    const regex = /^00\d{7}[A-Z]{2}\d{3}$/;

    return regex.test(bi);
}
// =====================================================
// VALIDAR SENHA
// =====================================================
function validarSenha(s) {

    return (
        s.length >= 8 &&
        /[A-Z]/.test(s) &&
        /[a-z]/.test(s) &&
        /[0-9]/.test(s) &&
        /[^A-Za-z0-9]/.test(s)
    );
}

// =====================================================
// ETAPA 1
// =====================================================
function validarEtapa1() {

    let ok = true;

    const nome =
        document.getElementById(
            'nomeCompleto'
        ).value.trim();

    const bi =
        document.getElementById(
            'bi'
        ).value.trim();


        const email =
    document.getElementById(
        'email'
    ).value.trim();

    const data =
        document.getElementById(
            'dataNascimento'
        ).value;

    const genero =
        document.getElementById(
            'genero'
        ).value;

    const telefone =
        document.getElementById(
            'telefone'
        ).value.replace(/\D/g, '');

    if (
    !nome ||
    !bi ||
    !data ||
    !genero ||
    !validarTelefone(telefone)
) {

    ok = false;
}

    if (bi && !validarBI(bi)) {

    showError(
        'errorBi',
        'BI deve começar com 00 e ter formato válido'
    );

    ok = false;

} else {

    showError('errorBi', '');
}

if (
    data &&
    !validarIdade(data)
) {

    showError(
        'errorData',
        'É necessário ter 18 anos ou mais'
    );

    ok = false;

} else {

    showError(
        'errorData',
        ''
    );
}

if (email) {

    if (!validarEmail(email)) {

        showError(
            'errorEmail',
            'Email inválido'
        );

        ok = false;
    }

}
    nextStep1Btn.disabled = !ok;

    return ok;
}

// =====================================================
// ETAPA 2
// =====================================================
function validarEtapa2() {

    const rua =
        document.getElementById(
            'rua'
        ).value.trim();

    const ok =
        rua.length > 0;

    nextStep2Btn.disabled = !ok;

    return ok;
}

// =====================================================
// ETAPA 3
// =====================================================
function validarEtapa3() {

    const senha =
        document.getElementById(
            'senha'
        ).value;

    const confirmar =
        document.getElementById(
            'confirmarSenha'
        ).value;

    let ok = true;

    if (
        !senha ||
        !confirmar
    ) {

        ok = false;
    }

    if (
        senha !== confirmar
    ) {

        showError(
            'errorConfirmar',
            'Senhas diferentes'
        );

        ok = false;

    } else {

        showError(
            'errorConfirmar',
            ''
        );
    }

    if (
        senha &&
        !validarSenha(senha)
    ) {

        showError(
            'errorSenha',
            'Senha fraca'
        );

        ok = false;

    } else {

        showError(
            'errorSenha',
            ''
        );
    }

    nextStep3Btn.disabled = !ok;

    return ok;
}

// =====================================================
// VALIDAR TERMOS
// =====================================================
function validarTermos() {

    const termos =
        document.querySelector(
            '.term-checkbox[data-term="termos_uso"]'
        )?.checked;

    const privacidade =
        document.querySelector(
            '.term-checkbox[data-term="privacidade"]'
        )?.checked;

    const lgpd =
        document.querySelector(
            '.term-checkbox[data-term="lgpd"]'
        )?.checked;

    confirmarBtn.disabled =
        !(termos && privacidade && lgpd);
}

// =====================================================
// VALIDAR TUDO
// =====================================================
function validarTudo() {

    validarEtapa1();

    validarEtapa2();

    validarEtapa3();

    validarTermos();
}

// =====================================================
// INPUTS
// =====================================================
document
.querySelectorAll('input, select')
.forEach(el => {

    el.addEventListener(
        'input',
        validarTudo
    );
});

// =====================================================
// ATUALIZAR INDICADORES
// =====================================================
function atualizarIndicadores(stepAtual) {

    const step1 =
        document.getElementById('step1Indicator');

    const step2 =
        document.getElementById('step2Indicator');

    const step3 =
        document.getElementById('step3Indicator');

    // limpar
    [step1, step2, step3]
    .forEach(step => {

        step.classList.remove(
            'active',
            'completed'
        );
    });

    // STEP 1
    if (stepAtual === 1) {

        step1.classList.add('active');
    }

    // STEP 2
    if (stepAtual === 2) {

        step1.classList.add('completed');

        step2.classList.add('active');
    }

    // STEP 3
    if (stepAtual === 3) {

        step1.classList.add('completed');

        step2.classList.add('completed');

        step3.classList.add('active');
    }

    // STEP 4
    if (stepAtual === 4) {

        step1.classList.add('completed');

        step2.classList.add('completed');

        step3.classList.add('completed');
    }
}

// =====================================================
// STEP 1 -> STEP 2
// =====================================================
nextStep1Btn.addEventListener(
    'click',
    () => {

        if (!validarEtapa1())
            return;

        document.getElementById(
            'step1Content'
        ).style.display = 'none';

        document.getElementById(
            'step2Content'
        ).style.display = 'block';

        atualizarIndicadores(2);
    }
);

// =====================================================
// STEP 2 -> STEP 3
// =====================================================
nextStep2Btn.addEventListener(
    'click',
    () => {

        if (!validarEtapa2())
            return;

        document.getElementById(
            'step2Content'
        ).style.display = 'none';

        document.getElementById(
            'step3Content'
        ).style.display = 'block';

        atualizarIndicadores(3);
    }
);

// =====================================================
// STEP 3 -> STEP 4
// =====================================================
nextStep3Btn.addEventListener(
    'click',
    () => {

        if (!validarEtapa3())
            return;

        document.getElementById(
            'step3Content'
        ).style.display = 'none';

        document.getElementById(
            'step4Content'
        ).style.display = 'block';

        atualizarIndicadores(4);
    }
);

// =====================================================
// VOLTAR STEP 2
// =====================================================
document
.getElementById('backStep2Btn')
.addEventListener('click', () => {

    document.getElementById(
        'step2Content'
    ).style.display = 'none';

    document.getElementById(
        'step1Content'
    ).style.display = 'block';

    atualizarIndicadores(1);
});

// =====================================================
// VOLTAR STEP 3
// =====================================================
document
.getElementById('backStep3Btn')
.addEventListener('click', () => {

    document.getElementById(
        'step3Content'
    ).style.display = 'none';

    document.getElementById(
        'step2Content'
    ).style.display = 'block';

    atualizarIndicadores(2);
});

// =====================================================
// VOLTAR STEP 4
// =====================================================
document
.getElementById('backStep4Btn')
.addEventListener('click', () => {

    document.getElementById(
        'step4Content'
    ).style.display = 'none';

    document.getElementById(
        'step3Content'
    ).style.display = 'block';

    atualizarIndicadores(3);
});

// =====================================================
// TERMOS COMPLETOS
// =====================================================
const fullTerms = {

    termos_uso:
`Termos de Uso – Saúde Fácil

Ao utilizar esta plataforma,
o utilizador concorda em
fornecer informações verdadeiras
e respeitar as regras do sistema.`,

    privacidade:
`Política de Privacidade

Os dados pessoais e médicos
serão protegidos e utilizados
apenas para funcionalidades
da plataforma Saúde Fácil.`,

    lgpd:
`LGPD

O utilizador autoriza o
tratamento dos dados de saúde
conforme a legislação vigente.`,

    comunicacao:
`Comunicações

O utilizador poderá receber
notificações e lembretes
da plataforma.`,

    pesquisa:
`Pesquisa

Dados anónimos poderão ser
usados em pesquisas científicas.`
};

// =====================================================
// MODAL TERMOS
// =====================================================
document
.querySelectorAll('.view-term-btn')
.forEach(btn => {

    btn.addEventListener(
        'click',
        function () {

            const id =
                this.dataset.termId;

            document.getElementById(
                'modalTitle'
            ).innerText = id;

            document.getElementById(
                'modalBody'
            ).innerText =
                fullTerms[id];

            document.getElementById(
                'termModal'
            ).style.display = 'flex';
        }
    );
});

// =====================================================
// FECHAR MODAL
// =====================================================
document
.getElementById(
    'closeModalBtn'
)
.addEventListener('click', () => {

    document.getElementById(
        'termModal'
    ).style.display = 'none';
});

window.addEventListener(
    'click',
    e => {

        const modal =
            document.getElementById(
                'termModal'
            );

        if (e.target === modal) {

            modal.style.display =
                'none';
        }
    }
);

// =====================================================
// EXPANDIR TERMOS
// =====================================================
document
.querySelectorAll('.term-header')
.forEach(header => {

    header.addEventListener(
        'click',
        e => {

            if (
                e.target.closest('.view-term-btn') ||
                e.target.closest('.custom-checkbox')
            ) return;

            const content =
                header
                .closest('.term-group')
                .querySelector(
                    '.expandable-text'
                );

            content.classList.toggle(
                'open'
            );
        }
    );
});

// =====================================================
// CHECKBOXES TERMOS
// =====================================================
document
.querySelectorAll('.term-checkbox')
.forEach(cb => {

    cb.addEventListener(
        'change',
        validarTermos
    );
});

// =====================================================
// CRIAR CONTA
// =====================================================
confirmarBtn.addEventListener(
    'click',
    async () => {

        const termos =
            document.querySelector(
                '.term-checkbox[data-term="termos_uso"]'
            ).checked;

        const privacidade =
            document.querySelector(
                '.term-checkbox[data-term="privacidade"]'
            ).checked;

        const lgpd =
            document.querySelector(
                '.term-checkbox[data-term="lgpd"]'
            ).checked;

        if (
            !termos ||
            !privacidade ||
            !lgpd
        ) {

            alert(
                'Aceite os termos obrigatórios.'
            );

            return;
        }

        const dados = {

            nome:
                document.getElementById(
                    'nomeCompleto'
                ).value.trim(),

            bi:
                document.getElementById(
                    'bi'
                ).value.trim(),

            dataNascimento:
                document.getElementById(
                    'dataNascimento'
                ).value,

            genero:
                document.getElementById(
                    'genero'
                ).value,

            email:
                document.getElementById(
                    'email'
                ).value.trim(),

            telefone:
                document.getElementById(
                    'telefone'
                ).value.trim(),

            provincia:
                document.getElementById(
                    'provincia'
                ).value,

            municipio:
                document.getElementById(
                    'municipio'
                ).value,

            rua:
                document.getElementById(
                    'rua'
                ).value.trim(),

            senha:
                document.getElementById(
                    'senha'
                ).value,

            termos_aceitos:
                termos,

            privacidade_aceita:
                privacidade,

            lgpd_aceita:
                lgpd
        };

        try {

            const resposta =
                await fetch(
                    'phpconexao/cadastro_paciente.php',
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json'
                        },

                        body:
                            JSON.stringify(dados)
                    }
                );

            const resultado =
                await resposta.json();

            if (
                resultado.status ===
                'success'
            ) {

                alert(
                    'Conta criada com sucesso!'
                );

                window.location.href =
                    'paciente.php';

            } else {

                alert(
                    resultado.msg ||
                    'Erro ao criar conta.'
                );
            }

        } catch (erro) {

            console.error(erro);

            alert(
                'Erro ao conectar ao servidor.'
            );
        }
    }
);

// =====================================================
// CANCELAR
// =====================================================
document
.getElementById(
    'cancelCadastroBtn'
)
.addEventListener('click', () => {

    if (
        confirm(
            'Deseja cancelar o cadastro?'
        )
    ) {

        window.location.href =
            'index.html';
    }
});

// =====================================================
// INICIAR
// =====================================================
validarTudo();

validarTermos();

atualizarIndicadores(1);