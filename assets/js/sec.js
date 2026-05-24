
//««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««
/* ============================================================================
   SAÚDE FÁCIL - PAINEL SECRETÁRIO
   JAVASCRIPT PURO - TODA LÓGICA SIMULADA
   ============================================================================ */

// ============================================================================
// DADOS SIMULADOS
// ============================================================================


// ============================================================================
// APP STATE
// ============================================================================

const appState = {
    currentPage: 'dashboard',
    darkMode: localStorage.getItem('darkMode') === 'true',
    editingProfile: false,
    searchResults: [],
    modalAtendimentoData: null
};

// ============================================================================
// INICIALIZAÇÃO
// ============================================================================

document.addEventListener('DOMContentLoaded', () => {
    initializeDarkMode();
    setupEventListeners();
    renderDashboard();
    setCurrentDate();
});

// ============================================================================
// DARK MODE
// ============================================================================

function initializeDarkMode() {
    if (appState.darkMode) {
        document.body.classList.add('dark-mode');
        updateDarkModeIcon();
    }
}

function setupEventListeners() {
    // Navigation
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', handleNavigation);
    });

    // Dark mode
    document.getElementById('darkModeToggle').addEventListener('click', toggleDarkMode);

    // Sidebar
    document.getElementById('menuToggle').addEventListener('click', toggleSidebar);
    document.getElementById('sidebarToggle').addEventListener('click', closeSidebar);
    document.getElementById('sidebarOverlay').addEventListener('click', closeSidebar);

    // Logout
    document.getElementById('logoutBtn').addEventListener('click', handleLogout);

    // Search
    document.getElementById('searchInput').addEventListener('input', handleSearch);

    // Solicitações
    document.getElementById('filterStatus').addEventListener('change', filterSolicitacoes);
    document.getElementById('filterMedico').addEventListener('change', filterSolicitacoes);

    // Relatórios
    document.getElementById('filterPeriodo').addEventListener('change', filterRelatorios);

    // Modals
    
}

function toggleDarkMode() {
    appState.darkMode = !appState.darkMode;
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', appState.darkMode);
    updateDarkModeIcon();
}

function updateDarkModeIcon() {
    const icon = document.getElementById('darkModeToggle').querySelector('i');
    icon.className = appState.darkMode ? 'fas fa-sun' : 'fas fa-moon';
}

// ============================================================================
// NAVIGATION
// ============================================================================

function handleNavigation(e) {
    e.preventDefault();
    const page = e.currentTarget.getAttribute('data-page');
    navigateTo(page);
}

function navigateTo(page) {

    appState.currentPage = page;

    // ==============================
    // MOSTRAR / ESCONDER PÁGINAS
    // ==============================
    document.querySelectorAll('.page')
        .forEach(p => p.classList.remove('active'));

    const target = document.getElementById(page);
    if (target) target.classList.add('active');

    // ==============================
    // SIDEBAR ACTIVE
    // ==============================
    document.querySelectorAll('.nav-item')
        .forEach(item => item.classList.remove('active'));

    const active = document.querySelector(`[data-page="${page}"]`);
    if (active) active.classList.add('active');

    // ==============================
    // TÍTULO DA PÁGINA
    // ==============================
    const titles = {
        dashboard: 'Dashboard',
        solicitacoes: 'Solicitações de Atendimento',
        relatorios: 'Relatórios e Análises',
        medicos: 'Equipe Médica',
        pacientes: 'Pacientes Cadastrados',
        perfil: 'Meu Perfil'
    };

    const titleEl = document.getElementById('pageTitle');
    if (titleEl) titleEl.textContent = titles[page] || '';

    // ==============================
    // CARREGAMENTO INTELIGENTE (BACKEND ONLY)
    // ==============================

    if (page === 'solicitacoes') {
        carregarSolicitacoes(); // 🔥 backend PHP
    }

    if (page === 'medicos') {
        renderMedicos(); // pode continuar local ou depois backend
    }

    if (page === 'pacientes') {
        renderPacientes(); // idem
    }

    if (page === 'relatorios') {
        renderRelatorios(); // depois podes migrar para backend também
    }

    if (page === 'perfil') {
        renderPerfil();
    }

    // ==============================
    // FECHAR SIDEBAR MOBILE
    // ==============================
    closeSidebar();
}

// ============================================================================
// SIDEBAR
// ============================================================================

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
}

// ============================================================================
// SEARCH
// ============================================================================

function handleSearch(e) {
    const query = e.target.value.toLowerCase();
    const resultsDiv = document.getElementById('searchResults');

    if (query.length < 2) {
        resultsDiv.classList.remove('active');
        return;
    }

    let results = [];

    // Search in pacientes
    mockData.pacientes.forEach(p => {
        if (p.name.toLowerCase().includes(query) || p.cpf.includes(query)) {
            results.push({
                type: 'paciente',
                icon: 'fa-user',
                title: p.name,
                desc: `CPF: ${p.cpf}`,
                data: p
            });
        }
    });

    // Search in medicos
    mockData.medicos.forEach(m => {
        if (m.name.toLowerCase().includes(query) || m.specialty.toLowerCase().includes(query)) {
            results.push({
                type: 'medico',
                icon: 'fa-user-md',
                title: m.name,
                desc: m.specialty,
                data: m
            });
        }
    });

    // Search in solicitacoes
    mockData.solicitacoes.forEach(s => {
        if (s.paciente.toLowerCase().includes(query)) {
            results.push({
                type: 'solicitacao',
                icon: 'fa-clipboard-list',
                title: `Solicitação - ${s.paciente}`,
                desc: `Médico: ${s.medico}`,
                data: s
            });
        }
    });

    appState.searchResults = results.slice(0, 5);
    displaySearchResults();
}

function displaySearchResults() {
    const resultsDiv = document.getElementById('searchResults');

    if (appState.searchResults.length === 0) {
        resultsDiv.innerHTML = '<div style="padding: 15px; text-align: center; color: var(--text-light);">Nenhum resultado encontrado</div>';
        resultsDiv.classList.add('active');
        return;
    }

    resultsDiv.innerHTML = appState.searchResults.map(result => `
        <div class="search-result-item" onclick="handleSearchResult('${result.type}', ${result.data.id})">
            <div class="search-result-icon">
                <i class="fas ${result.icon}"></i>
            </div>
            <div class="search-result-info">
                <h4>${result.title}</h4>
                <p>${result.desc}</p>
            </div>
        </div>
    `).join('');

    resultsDiv.classList.add('active');
}

function handleSearchResult(type, id) {
    document.getElementById('searchInput').value = '';
    document.getElementById('searchResults').classList.remove('active');

    if (type === 'paciente') {
        navigateTo('pacientes');
    } else if (type === 'medico') {
        navigateTo('medicos');
    } else if (type === 'solicitacao') {
        navigateTo('solicitacoes');
    }
}

// ============================================================================
// DASHBOARD
// ============================================================================

function setCurrentDate() {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date().toLocaleDateString('pt-BR', options);
    const dateSpan = document.querySelector('.date-range span');
    if (dateSpan) {
        dateSpan.textContent = date;
    }
}

function renderDashboard() {
    setCurrentDate();
    // Dashboard já está renderizado no HTML
}

// ============================================================================
// SOLICITAÇÕES
// ============================================================================


function filterSolicitacoes() {
    const status = document.getElementById('filterStatus').value;
    const medico = document.getElementById('filterMedico').value;

    appState.filteredSolicitacoes = mockData.solicitacoes.filter(s => {
        const statusMatch = !status || s.status === status;
        const medicoMatch = !medico || s.medico === medico;
        return statusMatch && medicoMatch;
    });

}


// ============================================================================
// RELATÓRIOS
// ============================================================================

function renderRelatorios() {
    const tbody = document.getElementById('relatoriosTable');
    tbody.innerHTML = appState.filteredRelatorios.map(r => `
        <tr>
            <td>${r.data}</td>
            <td>${r.paciente}</td>
            <td>${r.medico}</td>
            <td>${r.tipo}</td>
            <td>R$ ${r.valor.toFixed(2)}</td>
            <td><span class="badge badge-atendido">Realizado</span></td>
        </tr>
    `).join('');
}

function filterRelatorios() {
    // Simula filtro por período
    appState.filteredRelatorios = [...mockData.relatorios];
    renderRelatorios();
}

// ============================================================================
// MÉDICOS
// ============================================================================

function renderMedicos() {
    const grid = document.getElementById('medicosGrid');
    grid.innerHTML = mockData.medicos.map(m => `
        <div class="medico-card">
            <div class="medico-header">
                <img src="${m.avatar}" alt="${m.name}" class="medico-avatar">
                <h3 class="medico-name">${m.name}</h3>
                <p class="medico-spec">${m.specialty}</p>
            </div>
            <div class="medico-body">
                <div class="medico-info">
                    <div class="medico-info-item">
                        <i class="fas fa-id-card"></i>
                        <div>
                            <span class="medico-info-label">CRM:</span>
                            <span class="medico-info-value">${m.crm}</span>
                        </div>
                    </div>
                    <div class="medico-info-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <span class="medico-info-label">Telefone:</span>
                            <span class="medico-info-value">${m.phone}</span>
                        </div>
                    </div>
                    <div class="medico-info-item">
                        <i class="fas fa-stethoscope"></i>
                        <div>
                            <span class="medico-info-label">Consultas:</span>
                            <span class="medico-info-value">${m.consultations}</span>
                        </div>
                    </div>
                </div>
                <div class="medico-actions">
                    <button class="btn-primary" style="flex: 1;" onclick="showToast('Editar médico simulado')">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="btn-secondary" style="flex: 1;" onclick="showToast('Visualizar agenda do médico')">
                        <i class="fas fa-calendar"></i> Agenda
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// ============================================================================
// PACIENTES
// ============================================================================

function renderPacientes() {
    const tbody = document.getElementById('pacientesTable');
    tbody.innerHTML = mockData.pacientes.map(p => `
        <tr>
            <td><strong>${p.name}</strong></td>
            <td>${p.cpf}</td>
            <td>${p.phone}</td>
            <td>${p.email}</td>
            <td>${p.joinDate}</td>
            <td>
                <span class="badge badge-ativo">Ativo</span>
            </td>
            <td>
                <button class="btn-secondary" onclick="showToast('Editar paciente simulado')">
                    <i class="fas fa-edit"></i> Editar
                </button>
            </td>
        </tr>
    `).join('');
}

// ============================================================================
// PERFIL
// ============================================================================

function renderPerfil() {
    // Perfil já está renderizado no HTML
    setupProfileListeners();
}

function setupProfileListeners() {
    document.getElementById('editPerfilBtn').addEventListener('click', openEditPerfilModal);
    document.getElementById('changePasswordBtn').addEventListener('click', () => {
        openConfirmModal('Alterar Senha', 'Simulando alteração de senha. A senha foi alterada com sucesso!', () => {
            showToast('Senha alterada com sucesso!');
        });
    });
    document.getElementById('twoFactorBtn').addEventListener('click', () => {
        openConfirmModal('Autenticação de Dois Fatores', 'Deseja ativar a autenticação de dois fatores?', () => {
            showToast('2FA ativado com sucesso!');
        });
    });
}

function openEditPerfilModal() {
    document.getElementById('editNome').value = mockData.user.name;
    document.getElementById('editEmail').value = mockData.user.email;
    document.getElementById('editTelefone').value = mockData.user.phone;
    document.getElementById('editDepartamento').value = mockData.user.department;

    document.getElementById('modalEditPerfil').classList.add('active');
}

// ============================================================================
// MODALS
// ============================================================================

function abrirModalAgendamento(item) {

    // abrir modal
    document.getElementById("modalAgendamento").classList.add("active");

    // preencher dados básicos
    document.getElementById("agendamentoSolicitacaoId").value = item.id;
    document.getElementById("agendamentoPaciente").value = item.paciente;
    document.getElementById("agendamentoEspecialidade").value = item.especialidade;
    document.getElementById("agendamentoData").value = item.data_desejada;

    // limpar selects para evitar lixo antigo
    const medicoSelect = document.getElementById("agendamentoMedico");
    const horarioSelect = document.getElementById("agendamentoHorario");

    medicoSelect.innerHTML = `<option value="">Selecione médico</option>`;
    horarioSelect.innerHTML = `<option value="">Selecione horário</option>`;

    // carregar médicos baseado na especialidade do input
    carregarMedicosPorEspecialidade();
}
//««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««
function fecharModalAgendamento() {

    // esconder modal
    document.getElementById("modalAgendamento").classList.remove("active");

    // limpar formulário (opcional mas recomendado)
    document.getElementById("formAgendamento").reset();

    // reset selects
    document.getElementById("agendamentoMedico").innerHTML =
        `<option value="">Selecione médico</option>`;

    document.getElementById("agendamentoHorario").innerHTML =
        `<option value="">Selecione horário</option>`;
}

// ============================================================================
// LOGOUT
// ============================================================================


//««««Botão sair
/**
 * Logout do médico
 */
document.addEventListener('DOMContentLoaded', () => {

    const logoutBtn = document.getElementById('logoutBtn');

    if (logoutBtn) {

        logoutBtn.addEventListener('click', () => {

            const confirmar = confirm(
                'Você tem certeza que deseja sair?'
            );

            if (confirmar) {

                // redireciona para logout real
                window.location.href =
                    "/SaudeFacil/MediLab-1.0.0/phpconexao/logout.php";
            }
        });
    }
});

// ============================================================================
// TOAST NOTIFICATIONS
// ============================================================================

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');

    toastMessage.textContent = message;

    // Change icon based on type
    const icon = toast.querySelector('i');
    if (type === 'warning') {
        icon.className = 'fas fa-exclamation-circle';
        toast.style.background = 'var(--warning)';
    } else if (type === 'error') {
        icon.className = 'fas fa-times-circle';
        toast.style.background = 'var(--danger)';
    } else {
        icon.className = 'fas fa-check-circle';
        toast.style.background = 'var(--success)';
    }

    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// Close search results on click outside
document.addEventListener('click', (e) => {
    if (!e.target.closest('.search-container')) {
        document.getElementById('searchResults').classList.remove('active');
    }
});

//«««««««««««««««««««««««««««««««««««««««««««««««««««««««Listar solicitações
// =====================================================
// SOLICITAÇÕES - LISTAR + FILTRAR
// =====================================================

async function carregarSolicitacoes(estado = "") {

    try {

        let url = `assets/js/backsecretario/solicitacoes.php?acao=filtrar`;

        if (estado) {
            url += `&estado=${estado}`;
        }

        const response = await fetch(url);
        const resultado = await response.json();

        const tbody = document.getElementById("solicitacoesTable");

        tbody.innerHTML = "";

        if (resultado.status !== "success") {

            tbody.innerHTML = `
                <tr>
                    <td colspan="5">Erro ao carregar solicitações</td>
                </tr>
            `;
            return;
        }

        resultado.data.forEach(item => {

            let botoes = "";

            if (item.estado === "pendente") {

                botoes = `
                    <button class="btn-primary"
                        onclick='abrirModalAgendamento(${JSON.stringify(item)})'>
                        <i class="fas fa-check"></i> Atender
                    </button>

                    <button class="btn-cancelar"
                        onclick="cancelarSolicitacao(${item.id})">
                        Cancelar
                    </button>
                `;

            } else if (item.estado === "confirmada") {

                botoes = `<span class="acao-finalizada">Confirmada</span>`;

            } else {

                botoes = `<span class="acao-finalizada">Cancelada</span>`;
            }

            tbody.innerHTML += `
                <tr>
                    <td>${item.paciente}</td>
                    <td>${item.especialidade}</td>
                    <td>${item.data_desejada}</td>
                    <td>
                        <span class="status-${item.estado}">
                            ${item.estado}
                        </span>
                    </td>
                    <td>${botoes}</td>
                </tr>
            `;
        });

    } catch (erro) {
        console.error("Erro ao carregar solicitações:", erro);
    }
}

document.getElementById("filterStatus").addEventListener("change", function () {
    carregarSolicitacoes(this.value);
});
//«««««««««««««««««««««««««««««««««««««««««««««««««««Novos
function abrirModalAgendamento(item) {

    // abrir modal
    document.getElementById("modalAgendamento").classList.add("active");

    // preencher dados básicos
    document.getElementById("agendamentoSolicitacaoId").value = item.id;
    document.getElementById("agendamentoPaciente").value = item.paciente;
    document.getElementById("agendamentoEspecialidade").value = item.especialidade;
    document.getElementById("agendamentoData").value = item.data_desejada;

    // limpar selects para evitar lixo antigo
    const medicoSelect = document.getElementById("agendamentoMedico");
    const horarioSelect = document.getElementById("agendamentoHorario");

    medicoSelect.innerHTML = `<option value="">Selecione médico</option>`;
    horarioSelect.innerHTML = `<option value="">Selecione horário</option>`;

    // carregar médicos baseado na especialidade do input
    carregarMedicosPorEspecialidade();
}
//««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««
function fecharModalAgendamento() {

    // esconder modal
    document.getElementById("modalAgendamento").classList.remove("active");

    // limpar formulário (opcional mas recomendado)
    document.getElementById("formAgendamento").reset();

    // reset selects
    document.getElementById("agendamentoMedico").innerHTML =
        `<option value="">Selecione médico</option>`;

    document.getElementById("agendamentoHorario").innerHTML =
        `<option value="">Selecione horário</option>`;
}
// =====================================================
async function carregarMedicosPorEspecialidade() {

    const select = document.getElementById("agendamentoMedico");

    const especialidade = document.getElementById("agendamentoEspecialidade")?.value;
    const data_consulta = document.getElementById("agendamentoData")?.value;

    // limpar sempre
    select.innerHTML = `<option value="">Selecionar médico</option>`;

    // segurança
    if (!especialidade) {
        select.innerHTML += `<option disabled>Especialidade não definida</option>`;
        return;
    }

    try {

        const res = await fetch(
            `assets/js/backsecretario/listar_medicos.php?especialidade=${encodeURIComponent(especialidade)}&data=${data_consulta || ''}`
        );

        const result = await res.json();

        console.log("MEDICOS RESPONSE:", result);

        if (result.status !== "success" || !result.data || result.data.length === 0) {
            select.innerHTML += `<option disabled>Nenhum médico disponível</option>`;
            return;
        }

        result.data.forEach(medico => {

            const option = document.createElement("option");
            option.value = medico.id;
            option.textContent = medico.nome;

            select.appendChild(option);
        });

    } catch (error) {

        console.error("Erro ao carregar médicos:", error);

        select.innerHTML += `<option disabled>Erro ao carregar médicos</option>`;
    }
}
// =====================================================
document.getElementById("agendamentoMedico")
.addEventListener("change", function () {

    const medico_id = this.value;

    if (!medico_id) return;

    carregarHorarios(medico_id);
});
//««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««
async function carregarHorarios(medico_id) {

    const select = document.getElementById("agendamentoHorario");

    select.innerHTML = `<option>Carregando...</option>`;

    try {

        const res = await fetch(
            `assets/js/backsecretario/listar_horarios.php?medico_id=${medico_id}`
        );

        const json = await res.json();

        select.innerHTML = `<option value="">Selecione horário</option>`;

        if (!json.data || json.data.length === 0) {

            select.innerHTML = `<option>Nenhum horário disponível</option>`;
            return;
        }

        json.data.forEach(h => {

            select.innerHTML += `
                <option value="${h.id}">
                    ${h.hora_inicio} - ${h.hora_fim}
                </option>
            `;
        });

    } catch (e) {

        console.error(e);
        select.innerHTML = `<option>Erro ao carregar horários</option>`;
    }
}
//«««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««««

document.getElementById("formAgendamento").addEventListener("submit", async function (e) {

    e.preventDefault();

    const data = {
        solicitacao_id: document.getElementById("agendamentoSolicitacaoId").value,
        medico_id: document.getElementById("agendamentoMedico").value,
        horario_id: document.getElementById("agendamentoHorario").value,
        data_inicio: document.getElementById("agendamentoData").value + " 08:00:00",
        data_fim: document.getElementById("agendamentoData").value + " 08:30:00"
    };

    const dataSelecionada = document.getElementById("agendamentoData").value;

    const dataObj = new Date(dataSelecionada);
    const diaSemana = dataObj.getDay(); // 0=domingo, 1=segunda...

    const medicoSelect = document.getElementById("agendamentoMedico");
    const medicoId = medicoSelect.value;

// exemplo simples (podes depois puxar do backend)
const horariosMedicos = {
    dermatologia: [1, 3, 5], // segunda, quarta, sexta
};

    const res = await fetch("assets/js/backsecretario/agendar_consulta.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams(data)
    });

    const json = await res.json();

    if (json.status === "success") {

        alert("Consulta agendada!");

        fecharModalAgendamento();

        carregarSolicitacoes();
    } else {
        alert(json.msg);
    }
});

//«««««««««««««««««Cancelar Solicitação
async function cancelarSolicitacao(id) {

    const confirmar = confirm("Deseja cancelar esta solicitação?");

    if (!confirmar) return;

    try {

        const response = await fetch(
            "assets/js/backsecretario/cancelar_solicitacao.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `id=${id}`
            }
        );

        const resultado = await response.json();

        if (resultado.status === "success") {

            alert("Solicitação cancelada com sucesso!");

            // recarregar lista
            carregarSolicitacoes();

        } else {

            alert(resultado.msg || "Erro ao cancelar");

        }

    } catch (erro) {

        console.error(erro);

        alert("Erro no servidor");

    }
}
