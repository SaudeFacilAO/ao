/**
 * Saúde Fácil - Script Principal
 * Gerencia toda a interatividade da plataforma
 */

// ============================================
// INICIALIZAÇÃO E CONFIGURAÇÃO
// ============================================



const API_PACIENTE = "assets/js/backpaciente/";

function formatLocalDate(date) {

    const year = date.getFullYear();

    const month =
        String(date.getMonth() + 1).padStart(2, '0');

    const day =
        String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

// Verificar dark mode ao carregar
document.addEventListener('DOMContentLoaded', () => {
    initializePage();
    loadDarkModePreference();
    generateCalendar();
    carregarEspecialidadesPaciente();
    carregarConsultas();
});

// ============================================
// FUNÇÕES DE INICIALIZAÇÃO
// ============================================

/**
 * Inicializa a página com configurações básicas
 */
function initializePage() {
    console.log('[v0] Inicializando Saúde Fácil...');
    setupNavigationListeners();
}

/**
 * Carrega preferência de modo escuro do localStorage
 */
function loadDarkModePreference() {
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    if (isDarkMode) {
        enableDarkMode();
    }
}

/**
 * Configura os listeners de navegação
 */
function setupNavigationListeners() {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const section = link.getAttribute('data-section');
            switchSection(section);
            
            // Remover classe active de todos os links
            navLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
            
            // Fechar sidebar em mobile se estiver aberta
            const sidebar = document.querySelector('.sidebar');
            if (sidebar.classList.contains('active') && window.innerWidth < 768) {
                sidebar.classList.remove('active');
            }
        });
    });
}

/**
 * Muda de seção
 * @param {string} sectionName - Nome da seção para exibir
 */
function switchSection(sectionName) {
    // Esconder todas as seções
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => section.classList.remove('active'));
    
    // Mostrar seção selecionada
    const selectedSection = document.getElementById(`${sectionName}-section`);
    if (selectedSection) {
        selectedSection.classList.add('active');
        
        // Atualizar título
        const titles = {
            'dashboard': 'Dashboard',
            'agendamento': 'Agendar Consulta',
            'consultas': 'Minhas Consultas Agendadas',
            'historico': 'Histórico de Consultas',
            'perfil': 'Perfil do Paciente',
            'configuracoes': 'Configurações',
            'suporte': 'Suporte e Ajuda'
        };
        
        document.getElementById('page-title').textContent = titles[sectionName] || 'Dashboard';
    }
}

// ============================================
// NOTIFICAÇÕES
// ============================================

/**
 * Gerencia o dropdown de notificações
 */

// ============================================
// MODO ESCURO
// ============================================

/**
 * Ativa o modo escuro
 */
function enableDarkMode() {
    document.body.classList.add('dark-mode');
    localStorage.setItem('darkMode', 'true');
    updateDarkModeButton();
}

/**
 * Desativa o modo escuro
 */
function disableDarkMode() {
    document.body.classList.remove('dark-mode');
    localStorage.setItem('darkMode', 'false');
    updateDarkModeButton();
}

/**
 * Atualiza o ícone do botão de modo escuro
 */
function updateDarkModeButton() {
    const btn = document.getElementById('darkModeToggle');
    const isDarkMode = document.body.classList.contains('dark-mode');
    if (btn) {
        btn.innerHTML = isDarkMode ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
    }
}

// Listener para botão de dark mode
document.addEventListener('DOMContentLoaded', () => {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeSetting = document.getElementById('darkModeToggleSetting');
    
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            const isDarkMode = document.body.classList.contains('dark-mode');
            if (isDarkMode) {
                disableDarkMode();
            } else {
                enableDarkMode();
            }
        });
    }
    
    if (darkModeSetting) {
        darkModeSetting.addEventListener('change', () => {
            const isDarkMode = document.body.classList.contains('dark-mode');
            if (isDarkMode) {
                disableDarkMode();
            } else {
                enableDarkMode();
            }
        });
    }
});

// ============================================
// MENU LATERAL MOBILE
// ============================================

/**
 * Gerencia o menu hambúrguer em mobile
 */
document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('active');
        });
    }
});
// ============================================
// CALENDÁRIO
// ============================================

let currentDisplayMonth = new Date().getMonth();
let currentDisplayYear = new Date().getFullYear();


// ============================================
// DIAS DISPONÍVEIS NO BACKEND
// ============================================
document.getElementById("especialidadeConsulta")
.addEventListener("change", () => {

    carregarDiasDisponiveis();
});
let diasDisponiveis = []; // vindo do backend
let requestVersion = 0;

async function carregarDiasDisponiveis() {

    const el = document.getElementById("especialidadeConsulta");
    const especialidade = el ? el.value : null;

    const myRequest = ++requestVersion;

    // =========================================
    // SEM ESPECIALIDADE → LIMPA TUDO
    // =========================================
    if (!especialidade) {
        diasDisponiveis = [];
        generateCalendar();
        return;
    }

    try {

        const res = await fetch(
            `assets/js/backpaciente/calendario_disponivel.php?especialidade_id=${especialidade}&ano=${currentDisplayYear}&mes=${currentDisplayMonth + 1}`
        );

        const data = await res.json();

        console.log("BACKEND RESPONSE:", data);

        // =========================================
        // IGNORAR RESPOSTAS ANTIGAS
        // =========================================
        if (myRequest !== requestVersion) return;

        // =========================================
        // VALIDAR RESPOSTA
        // =========================================
        diasDisponiveis =
            data.status === "ok"
                ? (data.dias_disponiveis || [])
                : [];

        generateCalendar();

    } catch (error) {

        if (myRequest !== requestVersion) return;

        console.error("Erro ao carregar dias:", error);

        diasDisponiveis = [];
        generateCalendar();
    }
}
// ============================================
// GENERATECALENDAR
// ============================================

function generateCalendar() {

    const calendar = document.getElementById('calendar');
    const monthLabel = document.getElementById('calendarMonthLabel');
    if (!calendar) return;

    calendar.innerHTML = '';

    const monthNames = [
        'Janeiro','Fevereiro','Março','Abril',
        'Maio','Junho','Julho','Agosto',
        'Setembro','Outubro','Novembro','Dezembro'
    ];

    if (monthLabel) {
        monthLabel.textContent = `${monthNames[currentDisplayMonth]} ${currentDisplayYear}`;
    }

    const firstDay = new Date(currentDisplayYear, currentDisplayMonth, 1);
    const lastDay = new Date(currentDisplayYear, currentDisplayMonth + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();

    const weekDays = ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'];

    weekDays.forEach(day => {
        const d = document.createElement('div');
        d.className = 'calendar-header';
        d.textContent = day;
        calendar.appendChild(d);
    });

    for (let i = 0; i < startingDayOfWeek; i++) {
        calendar.appendChild(document.createElement('div'));
    }

    for (let day = 1; day <= daysInMonth; day++) {

    const date = new Date(currentDisplayYear, currentDisplayMonth, day);
    const iso = formatLocalDate(date);

    const especialidade =
        document.getElementById("especialidadeConsulta").value;

    const today = new Date();
    today.setHours(0,0,0,0);

    const compare = new Date(date);
    compare.setHours(0,0,0,0);

    const now = new Date();

    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);

    let disabled = false;

    // 🔴 REGRAS FIXAS
    if (compare < today) disabled = true;
    if (compare.getDay() === 0) disabled = true;
    if (compare.getTime() === today.getTime()) disabled = true;

    if (
        now.getHours() >= 16 &&
        iso === tomorrow.toISOString().split('T')[0]
    ) {
        disabled = true;
    }

    // 🔵 REGRAS DO BACKEND (ESPECIALIDADE)
    if (especialidade) {

        if (!diasDisponiveis.includes(iso)) {
            disabled = true;
        }
    }

    // criar botão
    const btn = document.createElement('button');
    btn.className = 'calendar-day';
    btn.textContent = day;
    btn.type = 'button';

    if (disabled) {
        btn.classList.add('disabled');
        btn.disabled = true;
    } else {
        btn.addEventListener('click', () => selectDate(btn, date));
    }

    calendar.appendChild(btn);
}
}
// ============================================
// 
// ============================================


function prevMonth() {

    currentDisplayMonth--;

    if (currentDisplayMonth < 0) {
        currentDisplayMonth = 11;
        currentDisplayYear--;
    }

    carregarDiasDisponiveis();
}

function nextMonth() {

    currentDisplayMonth++;

    if (currentDisplayMonth > 11) {
        currentDisplayMonth = 0;
        currentDisplayYear++;
    }

    carregarDiasDisponiveis();
}

//««««««««««««««««««««««««««««««««««««Seelecionar data

function selectDate(element, date) {

    // 🔒 botão desabilitado
    if (element.disabled) return;

    // 🔒 especialidade obrigatória
    const especialidade =
        document.getElementById("especialidadeConsulta")?.value;

    if (!especialidade) {
        alert("Selecione primeiro a especialidade!");
        return;
    }

    // ✅ data local correta
    const iso = formatLocalDate(date);

    // 🔒 segurança extra
    if (
        typeof diasDisponiveis !== "undefined" &&
        diasDisponiveis.length > 0 &&
        !diasDisponiveis.includes(iso)
    ) {
        return;
    }

    // remover seleção anterior
    const previousSelected =
        document.querySelector('.calendar-day.selected');

    if (previousSelected) {
        previousSelected.classList.remove('selected');
    }

    // marcar atual
    element.classList.add('selected');

    // 👁️ data bonita
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };

    const display =
        document.getElementById('selectedDateDisplay');

    if (display) {
        display.value =
            date.toLocaleDateString('pt-BR', options);
    }

    // 🧠 data para banco
    const hidden =
        document.getElementById('selectedDate');

    if (hidden) {
        hidden.value = iso;
    }
}
// ============================================
// CHAT
// ============================================

/**
 * Inicializa o sistema de chat
 */
document.addEventListener('DOMContentLoaded', () => {
    const doctorItems = document.querySelectorAll('.doctor-item');
    
    doctorItems.forEach(item => {
        item.addEventListener('click', () => {
            // Remover active de todos
            doctorItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');
        });
    });
    
    // Enviar mensagem
    const sendBtn = document.getElementById('sendMessageBtn');
    const messageInput = document.getElementById('messageInput');
    
    if (sendBtn && messageInput) {
        sendBtn.addEventListener('click', sendMessage);
        messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }
});

/**
 * Envia uma mensagem no chat
 */
function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    const chatMessages = document.getElementById('chatMessages');
    
    // Criar elemento da mensagem
    const messageElement = document.createElement('div');
    messageElement.className = 'message patient';
    messageElement.innerHTML = `
        <p>${escapeHtml(message)}</p>
        <small>${new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })}</small>
    `;
    
    chatMessages.appendChild(messageElement);
    
    // Limpar input
    messageInput.value = '';
    
    // Rolar para baixo
    chatMessages.scrollTop = chatMessages.scrollHeight;
    
    // Simular resposta do médico (em produção seria uma chamada à API)
    setTimeout(() => {
        const responseElement = document.createElement('div');
        responseElement.className = 'message doctor';
        responseElement.innerHTML = `
            <p>Obrigado pela mensagem! Em breve responderemos.</p>
            <small>${new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })}</small>
        `;
        chatMessages.appendChild(responseElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }, 1500);
}

/**
 * Escapa caracteres HTML para evitar injeção
 * @param {string} text - Texto a escapar
 * @returns {string} Texto escapado
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// PERGUNTAS FREQUENTES (FAQ)
// ============================================

/**
 * Inicializa o accordion de FAQ
 */
document.addEventListener('DOMContentLoaded', () => {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', () => {
            // Fechar outros itens
            faqItems.forEach(i => {
                if (i !== item) {
                    i.classList.remove('active');
                }
            });
            
            // Alternar item atual
            item.classList.toggle('active');
        });
    });
});

// ============================================
// MODAIS
// ============================================

/**
 * Inicializa os modais
 */
document.addEventListener('DOMContentLoaded', () => {
    // Emergency Modal
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener('click', () => {
            notificationDropdown.classList.add('active');
        });
    }
    
    // Change Password Modal
    const changePasswordBtn = document.getElementById('changePasswordBtn');
    const passwordModal = document.getElementById('passwordModal');
    
    if (changePasswordBtn && passwordModal) {
        changePasswordBtn.addEventListener('click', () => {
            passwordModal.classList.add('active');
        });
    }
    
    // Fechar modais
    const modalCloseButtons = document.querySelectorAll('.modal-close');
    modalCloseButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.target.closest('.modal').classList.remove('active');
        });
    });
    
    // Fechar modal ao clicar fora
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    });
});

/**
 * Inicializa o formulário de senha
 */
document.addEventListener('DOMContentLoaded', () => {
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Senha alterada com sucesso!');
            document.getElementById('passwordModal').classList.remove('active');
            passwordForm.reset();
        });
    }
});

/**
 * Inicializa o formulário de suporte
 */
document.addEventListener('DOMContentLoaded', () => {
    const supportForm = document.getElementById('supportForm');
    if (supportForm) {
        supportForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Mensagem enviada com sucesso! Nossa equipe entrará em contato em breve.');
            supportForm.reset();
        });
    }
});

// ============================================
// EDITAR PERFIL
// ============================================

/**
 * Inicializa o botão de editar perfil
 */
document.addEventListener('DOMContentLoaded', () => {
    const editProfileBtn = document.getElementById('editProfileBtn');
    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', () => {
            alert('Funcionalidade de edição de perfil em desenvolvimento!');
        });
    }
});

// ============================================
// LOGOUT
// ============================================

/**
 * Inicializa o botão de logout
 */
document.addEventListener('DOMContentLoaded', () => {

    const logoutBtn = document.querySelector('.logout-btn');

    if (logoutBtn) {

        logoutBtn.addEventListener('click', () => {

            const confirmar = confirm(
                'Você tem certeza que deseja sair?'
            );

            if (confirmar) {

                // redireciona para logout real
                window.location.href =window.location.href =
                 "/SaudeFacil/MediLab-1.0.0/phpconexao/logout.php";
            }
        });
    }
});

// ============================================
// GRÁFICO DE CONSULTAS (Chart.js)
// ============================================

/**
 * Inicializa o gráfico de consultas
 */
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('appointmentChart');
    if (canvas) {
        drawSimpleChart();
    }
});

/**
 * Desenha um gráfico simples sem biblioteca externa
 */
function drawSimpleChart() {
    const canvas = document.getElementById('appointmentChart');
    const ctx = canvas.getContext('2d');
    
    // Ajustar tamanho do canvas
    const rect = canvas.parentElement.getBoundingClientRect();
    canvas.width = rect.width;
    canvas.height = rect.height;
    
    // Dados
    const data = [2, 3, 1, 4, 3, 5, 2];
    const labels = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab', 'Dom'];
    const maxValue = Math.max(...data);
    const barWidth = canvas.width / data.length;
    const padding = 40;
    const chartHeight = canvas.height - padding * 2;
    
    // Cores
    const isDarkMode = document.body.classList.contains('dark-mode');
    const barColor = '#0066cc';
    const textColor = isDarkMode ? '#ffffff' : '#1a1a1a';
    const gridColor = isDarkMode ? '#444444' : '#e0e0e0';
    
    // Desenhar grid
    ctx.strokeStyle = gridColor;
    ctx.lineWidth = 1;
    for (let i = 0; i <= 5; i++) {
        const y = padding + (chartHeight / 5) * i;
        ctx.beginPath();
        ctx.moveTo(padding, y);
        ctx.lineTo(canvas.width - padding, y);
        ctx.stroke();
        
        // Valores no eixo Y
        ctx.fillStyle = textColor;
        ctx.font = '12px sans-serif';
        ctx.textAlign = 'right';
        ctx.fillText(Math.round((maxValue / 5) * (5 - i)), padding - 10, y + 4);
    }
    
    // Desenhar barras
    data.forEach((value, index) => {
        const x = padding + barWidth * index + barWidth / 4;
        const barHeight = (value / maxValue) * chartHeight;
        const y = canvas.height - padding - barHeight;
        
        // Barra
        ctx.fillStyle = barColor;
        ctx.fillRect(x, y, barWidth / 2, barHeight);
        
        // Label
        ctx.fillStyle = textColor;
        ctx.font = 'bold 12px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(labels[index], x + barWidth / 4, canvas.height - padding + 20);
        
        // Valor na barra
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 11px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(value, x + barWidth / 4, y + barHeight / 2 + 4);
    });
    
    // Eixo X
    ctx.strokeStyle = textColor;
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(padding, canvas.height - padding);
    ctx.lineTo(canvas.width - padding, canvas.height - padding);
    ctx.stroke();
    
    // Eixo Y
    ctx.beginPath();
    ctx.moveTo(padding, padding);
    ctx.lineTo(padding, canvas.height - padding);
    ctx.stroke();
}

// Re-desenhar gráfico ao mudar para dark mode
document.addEventListener('DOMContentLoaded', () => {
    const originalEnableDarkMode = enableDarkMode;
    const originalDisableDarkMode = disableDarkMode;
    
    window.enableDarkMode = function() {
        originalEnableDarkMode();
        const canvas = document.getElementById('appointmentChart');
        if (canvas) {
            setTimeout(() => drawSimpleChart(), 0);
        }
    };
    
    window.disableDarkMode = function() {
        originalDisableDarkMode();
        const canvas = document.getElementById('appointmentChart');
        if (canvas) {
            setTimeout(() => drawSimpleChart(), 0);
        }
    };
});

// ============================================
// UTILIDADES
// ============================================

/**
 * Log de debug para v0
 * @param {string} message - Mensagem a logar
 * @param {*} data - Dados adicionais (opcional)
 */
function debugLog(message, data = null) {
    if (data) {
        console.log(`[v0] ${message}`, data);
    } else {
        console.log(`[v0] ${message}`);
    }
}


//«««««««««««««««««Listar especialidade
async function carregarEspecialidadesPaciente() {

    try {

        const res = await fetch(API_PACIENTE + "listar_especialidades.php");

        const data = await res.json();

        const select = document.getElementById("especialidadeConsulta");

        select.innerHTML = `
            <option value="">Selecione</option>
        `;

        data.data.forEach(esp => {

            select.innerHTML += `
                <option value="${esp.id}">
                    ${esp.nome}
                </option>
            `;
        });

    } catch (erro) {

        console.error(erro);
    }
}

//«««««««««««««««««««««««««««««««««Solicitar consultas
async function solicitarConsulta() {

    const especialidade_id =
        document.getElementById("especialidadeConsulta").value;

    const data_desejada =
        document.getElementById("selectedDate").value;

    const motivo =
        document.getElementById("motivoConsulta").value;

    // =========================================
    // 🔒 VALIDAÇÃO BASE
    // =========================================
    if (!especialidade_id || !data_desejada || !motivo) {
        alert("Preencha todos os campos!");
        return;
    }

    // =========================================
    // 🔒 ESPECIALIDADE OBRIGATÓRIA
    // =========================================
    if (!especialidade_id) {
        alert("Selecione primeiro a especialidade!");
        return;
    }

    // =========================================
    // 🔒 DATA DEVE EXISTIR NOS DIAS DISPONÍVEIS
    // =========================================
    if (
        typeof diasDisponiveis !== "undefined" &&
        diasDisponiveis.length > 0 &&
        !diasDisponiveis.includes(data_desejada)
    ) {
        alert("Data inválida ou não disponível!");
        return;
    }

    const payload = {
        especialidade_id,
        data_desejada,
        motivo
    };

    try {

        const res = await fetch(
            "assets/js/backpaciente/salvar_solicitacao.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            }
        );

        const result = await res.json();

        if (result.status === "ok") {

            alert("Solicitação enviada com sucesso!");

            // =========================================
            // 🧹 LIMPAR FORMULÁRIO
            // =========================================
            document.getElementById("especialidadeConsulta").value = "";

            document.getElementById("selectedDate").value = "";

            document.getElementById("selectedDateDisplay").value = "";

            document.getElementById("motivoConsulta").value = "";

            // limpar calendário
            diasDisponiveis = [];

            // remover seleção visual
            const selecionado =
                document.querySelector('.calendar-day.selected');

            if (selecionado) {
                selecionado.classList.remove('selected');
            }

            // redesenhar calendário bloqueado
            generateCalendar();

        } else {

            alert(result.msg || "Erro ao enviar solicitação");
        }

    } catch (error) {

        console.error(error);

        alert("Erro no servidor");
    }
}

//«««««««««««««««««««««««««««««««««««««««Carregar consulta na tabela
function carregarConsultas() {

    fetch("assets/js/backpaciente/listar_consultas.php")
        .then(res => res.json())
        .then(res => {

            console.log("DADOS:", res);

            const tbody = document.getElementById("consultasTableBody");

            tbody.innerHTML = "";

            if (!res.data || res.data.length === 0) {

                tbody.innerHTML = `
                    <tr>
                        <td colspan="7">
                            Nenhuma consulta encontrada
                        </td>
                    </tr>
                `;

                return;
            }

            res.data.forEach(item => {

                // =========================
                // MÉDICO
                // =========================
                let medico = item.medico_nome ?? 'Aguardando médico';

                // =========================
                // DATA DA CONSULTA
                // =========================
                let dataConsulta = item.data_hora_inicio
                    ? item.data_hora_inicio
                    : '—';

                // =========================
                // BOTÃO / ESTADO TELECONSULTA
                // =========================
                let acao = "";

                // =====================================
                // CONSULTA CANCELADA
                // =====================================
                if (item.estado_solicitacao === "cancelada") {

                    acao = `
                        <span class="status-cancelada">
                            Cancelada
                        </span>
                    `;

                }

                // =====================================
                // MÉDICO INICIOU CONSULTA
                // =====================================
                else if (item.pode_entrar === true) {

                    acao = `
                    <button
                        class="btn-teleconsulta"
                        onclick="abrirTeleconsulta('${item.teleconsulta_link}')">
                        Entrar
                    </button>
`;

                }

                // =====================================
                // AGUARDANDO MÉDICO
                // =====================================
                else if (
                    item.estado_consulta === "agendada" ||
                    item.estado_consulta === "confirmada"
                ) {

                    acao = `
                        <span class="aguardando">
                            Aguardando médico iniciar consulta
                        </span>
                    `;

                }

                // =====================================
                // SEM CONSULTA
                // =====================================
                else {

                    acao = `
                        <span class="aguardando">
                            Aguardando consulta
                        </span>
                    `;
                }

                // =====================================
                // TABELA
                // =====================================
                tbody.innerHTML += `
                    <tr>

                        <td>
                            <img 
                                src="https://i.pravatar.cc/150" 
                                class="doctor-avatar"
                            >
                        </td>

                        <td>${medico}</td>

                        <td>${item.especialidade ?? '-'}</td>

                        <td>${item.data_solicitacao ?? '-'}</td>

                        <td>${dataConsulta}</td>

                        <td>${acao}</td>

                        <td>

                            <button 
                                class="btn-cancelar"
                                onclick="cancelar(${item.solicitacao_id})"
                            >
                                Cancelar
                            </button>

                            <button 
                                class="btn-remarcar"
                                onclick="remarcar(${item.solicitacao_id})"
                            >
                                Remarcar
                            </button>

                        </td>

                    </tr>
                `;
            });

        })

        .catch(err => {

            console.error("ERRO:", err);

        });
}

//««««««««««««««««««««««««««««««««««««««Abrir video no modal
function abrirTeleconsulta(link) {

    document.getElementById("modalTeleconsulta").style.display = "flex";

    document.getElementById("iframeTeleconsulta").src = link;
}

function fecharTeleconsulta() {

    document.getElementById("iframeTeleconsulta").src = "";

    document.getElementById("modalTeleconsulta").style.display = "none";
}
//«««««««««««««««««««««««««««««««««««««««««««««««Cancelar solicitação
async function cancelarSolicitacao(id) {

    if (!confirm("Cancelar solicitação?")) return;

    const res = await fetch("assets/js/backpaciente/cancelar_solicitacao.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({id})
    });

    const r = await res.json();

    if (r.status === "ok") {
        carregarConsultas();
    }
}

//««««««««««««««««««««««««««««««««««««««««««««««««««««««««Fazer login
async function fazerLogin() {

    const login = document.getElementById("login").value;
    const senha = document.getElementById("senha").value;

    const res = await fetch("assets/js/backpaciente/login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ login, senha })
    });

    const r = await res.json();

    if (r.status === "ok") {

        localStorage.setItem("usuario_id", r.usuario_id);

        window.location.href = "paciente.html";

    } else {
        document.getElementById("msg").innerText = r.msg;
    }
}

//«««««««««««««««««««««««««««««««««««««««««««««««Cancelar solicitação
function cancelar(id) {

    fetch("assets/js/backpaciente/cancelar_solicitacao.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            solicitacao_id: id
        })
    })
    .then(res => res.json())
    .then(res => {

        if (res.status === "ok") {

            carregarConsultas();

        } else {

            console.error(res.msg);
        }

    })
    .catch(err => console.error(err));
}

//«««««««««««««««««««««««««««««Remarcar
function iniciarRemarcacao(id){

    localStorage.setItem("remarcar_id", id);

    mostrarSecao("agendamento-section");
}

// Log inicial
debugLog('Saúde Fácil iniciada com sucesso');
