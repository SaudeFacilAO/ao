/* ============================================================================
   SAÚDE FÁCIL - PAINEL SECRETÁRIO
   JAVASCRIPT PURO - TODA LÓGICA SIMULADA
   ============================================================================ */

// ============================================================================
// DADOS SIMULADOS
// ============================================================================

const mockData = {
    user: {
        name: 'Ana Oliveira',
        email: 'ana.oliveira@saudefacil.com',
        role: 'Secretária Executiva',
        phone: '(11) 98765-4321',
        department: 'Atendimento',
        joinDate: '15/03/2022'
    },

    medicos: [
        {
            id: 1,
            name: 'Dr. Carlos Mendes',
            specialty: 'Cardiologia',
            phone: '(11) 99999-0001',
            crm: '123456/SP',
            consultations: 245,
            avatar: 'https://randomuser.me/api/portraits/men/1.jpg'
        },
        {
            id: 2,
            name: 'Dra. Paula Rodrigues',
            specialty: 'Dermatologia',
            phone: '(11) 99999-0002',
            crm: '123457/SP',
            consultations: 198,
            avatar: 'https://randomuser.me/api/portraits/women/1.jpg'
        },
        {
            id: 3,
            name: 'Dr. Rafael Santos',
            specialty: 'Oftalmologia',
            phone: '(11) 99999-0003',
            crm: '123458/SP',
            consultations: 267,
            avatar: 'https://randomuser.me/api/portraits/men/2.jpg'
        },
        {
            id: 4,
            name: 'Dra. Juliana Costa',
            specialty: 'Pediatria',
            phone: '(11) 99999-0004',
            crm: '123459/SP',
            consultations: 187,
            avatar: 'https://randomuser.me/api/portraits/women/2.jpg'
        },
        {
            id: 5,
            name: 'Dr. Felipe Oliveira',
            specialty: 'Orthopedia',
            phone: '(11) 99999-0005',
            crm: '123460/SP',
            consultations: 212,
            avatar: 'https://randomuser.me/api/portraits/men/3.jpg'
        }
    ],

    pacientes: [
        {
            id: 1,
            name: 'João Silva',
            cpf: '123.456.789-00',
            phone: '(11) 91234-5678',
            email: 'joao@email.com',
            joinDate: '10/01/2023',
            status: 'ativo'
        },
        {
            id: 2,
            name: 'Maria Santos',
            cpf: '987.654.321-11',
            phone: '(11) 91234-5679',
            email: 'maria@email.com',
            joinDate: '15/02/2023',
            status: 'ativo'
        },
        {
            id: 3,
            name: 'Pedro Oliveira',
            cpf: '456.789.123-22',
            phone: '(11) 91234-5680',
            email: 'pedro@email.com',
            joinDate: '20/03/2023',
            status: 'ativo'
        },
        {
            id: 4,
            name: 'Ana Costa',
            cpf: '789.123.456-33',
            phone: '(11) 91234-5681',
            email: 'ana@email.com',
            joinDate: '05/04/2023',
            status: 'ativo'
        },
        {
            id: 5,
            name: 'Carlos Mendes',
            cpf: '321.654.987-44',
            phone: '(11) 91234-5682',
            email: 'carlos@email.com',
            joinDate: '12/05/2023',
            status: 'ativo'
        }
    ],

    solicitacoes: [
        {
            id: 1,
            paciente: 'João Silva',
            pacienteId: 1,
            telefone: '(11) 91234-5678',
            medico: 'Dr. Carlos Mendes',
            dataSolicitacao: '10/11/2024',
            status: 'pendente'
        },
        {
            id: 2,
            paciente: 'Maria Santos',
            pacienteId: 2,
            telefone: '(11) 91234-5679',
            medico: 'Dra. Paula Rodrigues',
            dataSolicitacao: '11/11/2024',
            status: 'atendido'
        },
        {
            id: 3,
            paciente: 'Pedro Oliveira',
            pacienteId: 3,
            telefone: '(11) 91234-5680',
            medico: 'Dr. Rafael Santos',
            dataSolicitacao: '09/11/2024',
            status: 'pendente'
        },
        {
            id: 4,
            paciente: 'Ana Costa',
            pacienteId: 4,
            telefone: '(11) 91234-5681',
            medico: 'Dra. Juliana Costa',
            dataSolicitacao: '08/11/2024',
            status: 'pendente'
        },
        {
            id: 5,
            paciente: 'Carlos Mendes',
            pacienteId: 5,
            telefone: '(11) 91234-5682',
            medico: 'Dr. Felipe Oliveira',
            dataSolicitacao: '07/11/2024',
            status: 'cancelado'
        },
        {
            id: 6,
            paciente: 'Julia Ferreira',
            pacienteId: 6,
            telefone: '(11) 91234-5683',
            medico: 'Dr. Carlos Mendes',
            dataSolicitacao: '12/11/2024',
            status: 'pendente'
        }
    ],

    relatorios: [
        {
            id: 1,
            data: '10/11/2024',
            paciente: 'João Silva',
            medico: 'Dr. Carlos Mendes',
            tipo: 'Consulta',
            valor: 150
        },
        {
            id: 2,
            data: '11/11/2024',
            paciente: 'Maria Santos',
            medico: 'Dra. Paula Rodrigues',
            tipo: 'Consulta',
            valor: 180
        },
        {
            id: 3,
            data: '09/11/2024',
            paciente: 'Pedro Oliveira',
            medico: 'Dr. Rafael Santos',
            tipo: 'Procedimento',
            valor: 350
        },
        {
            id: 4,
            data: '08/11/2024',
            paciente: 'Ana Costa',
            medico: 'Dra. Juliana Costa',
            tipo: 'Consulta',
            valor: 150
        },
        {
            id: 5,
            data: '07/11/2024',
            paciente: 'Carlos Mendes',
            medico: 'Dr. Felipe Oliveira',
            tipo: 'Consulta',
            valor: 200
        }
    ]
};

// ============================================================================
// APP STATE
// ============================================================================

const appState = {
    currentPage: 'dashboard',
    darkMode: localStorage.getItem('darkMode') === 'true',
    editingProfile: false,
    filteredSolicitacoes: [...mockData.solicitacoes],
    filteredRelatorios: [...mockData.relatorios],
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
    setupModalListeners();
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

    // Hide all pages
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));

    // Show selected page
    document.getElementById(page).classList.add('active');

    // Update nav active state
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`[data-page="${page}"]`).classList.add('active');

    // Update page title
    const titles = {
        dashboard: 'Dashboard',
        solicitacoes: 'Solicitações de Atendimento',
        relatorios: 'Relatórios e Análises',
        medicos: 'Equipe Médica',
        pacientes: 'Pacientes Cadastrados',
        perfil: 'Meu Perfil'
    };
    document.getElementById('pageTitle').textContent = titles[page];

    // Render page content
    switch (page) {
        case 'solicitacoes':
            renderSolicitacoes();
            break;
        case 'relatorios':
            renderRelatorios();
            break;
        case 'medicos':
            renderMedicos();
            break;
        case 'pacientes':
            renderPacientes();
            break;
        case 'perfil':
            renderPerfil();
            break;
    }

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

function renderSolicitacoes() {
    const tbody = document.getElementById('solicitacoesTable');
    tbody.innerHTML = appState.filteredSolicitacoes.map(s => `
        <tr>
            <td><strong>${s.paciente}</strong></td>
            <td>${s.telefone}</td>
            <td>${s.medico}</td>
            <td>${s.dataSolicitacao}</td>
            <td>
                <span class="badge badge-${s.status}">
                    ${s.status.charAt(0).toUpperCase() + s.status.slice(1)}
                </span>
            </td>
            <td>
                ${s.status === 'pendente' ? `
                    <button class="btn-primary" onclick="openModalAtendimento(${s.id})">
                        <i class="fas fa-check"></i> Atender
                    </button>
                ` : s.status === 'atendido' ? `
                    <span style="color: var(--success); font-weight: 600;">✓ Realizado</span>
                ` : `
                    <span style="color: var(--danger); font-weight: 600;">✗ Cancelado</span>
                `}
            </td>
        </tr>
    `).join('');
}

function filterSolicitacoes() {
    const status = document.getElementById('filterStatus').value;
    const medico = document.getElementById('filterMedico').value;

    appState.filteredSolicitacoes = mockData.solicitacoes.filter(s => {
        const statusMatch = !status || s.status === status;
        const medicoMatch = !medico || s.medico === medico;
        return statusMatch && medicoMatch;
    });

    renderSolicitacoes();
}

function openModalAtendimento(id) {
    const solicitacao = mockData.solicitacoes.find(s => s.id === id);
    if (!solicitacao) return;

    appState.modalAtendimentoData = solicitacao;

    document.getElementById('pacienteAtendimento').value = solicitacao.paciente;
    document.getElementById('medicoAtendimento').value = '';
    document.getElementById('consultorioAtendimento').value = '';
    document.getElementById('dataAtendimento').value = '';
    document.getElementById('horaAtendimento').value = '';
    document.getElementById('observacoesAtendimento').value = '';

    document.getElementById('modalAtendimento').classList.add('active');
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

function setupModalListeners() {
    // Atendimento modal
    document.getElementById('closeModal').addEventListener('click', () => {
        document.getElementById('modalAtendimento').classList.remove('active');
    });
    document.getElementById('cancelAtendimento').addEventListener('click', () => {
        document.getElementById('modalAtendimento').classList.remove('active');
    });
    document.getElementById('formAtendimento').addEventListener('submit', handleAtendimentoSubmit);

    // Edit perfil modal
    document.getElementById('closeEditPerfil').addEventListener('click', () => {
        document.getElementById('modalEditPerfil').classList.remove('active');
    });
    document.getElementById('cancelEditPerfil').addEventListener('click', () => {
        document.getElementById('modalEditPerfil').classList.remove('active');
    });
    document.getElementById('formEditPerfil').addEventListener('submit', handleEditPerfilSubmit);

    // Confirm modal
    document.getElementById('closeConfirm').addEventListener('click', () => {
        document.getElementById('modalConfirm').classList.remove('active');
    });
    document.getElementById('cancelConfirm').addEventListener('click', () => {
        document.getElementById('modalConfirm').classList.remove('active');
    });
    document.getElementById('confirmAction').addEventListener('click', () => {
        if (window.confirmCallback) {
            window.confirmCallback();
        }
        document.getElementById('modalConfirm').classList.remove('active');
    });
}

function handleAtendimentoSubmit(e) {
    e.preventDefault();

    const medico = document.getElementById('medicoAtendimento').value;
    const consultorio = document.getElementById('consultorioAtendimento').value;
    const data = document.getElementById('dataAtendimento').value;
    const hora = document.getElementById('horaAtendimento').value;

    if (!medico || !consultorio || !data || !hora) {
        showToast('Preencha todos os campos obrigatórios!', 'warning');
        return;
    }

    // Simula atualização do status
    const solicitacao = appState.modalAtendimentoData;
    const solicitacaoIndex = mockData.solicitacoes.findIndex(s => s.id === solicitacao.id);
    if (solicitacaoIndex !== -1) {
        mockData.solicitacoes[solicitacaoIndex].status = 'atendido';
        appState.filteredSolicitacoes = [...mockData.solicitacoes];
    }

    document.getElementById('modalAtendimento').classList.remove('active');
    showToast(`Atendimento confirmado com sucesso! ${solicitacao.paciente} com ${medico} em ${data}`);
    renderSolicitacoes();
}

function handleEditPerfilSubmit(e) {
    e.preventDefault();

    mockData.user.name = document.getElementById('editNome').value;
    mockData.user.email = document.getElementById('editEmail').value;
    mockData.user.phone = document.getElementById('editTelefone').value;
    mockData.user.department = document.getElementById('editDepartamento').value;

    document.getElementById('modalEditPerfil').classList.remove('active');
    showToast('Perfil atualizado com sucesso!');

    // Atualiza informações na tela
    document.querySelector('.user-name').textContent = mockData.user.name;
    document.getElementById('perfilEmail').textContent = mockData.user.email;
    document.getElementById('perfilTelefone').textContent = mockData.user.phone;
    document.getElementById('perfilDepartamento').textContent = mockData.user.department;
}

function openConfirmModal(title, message, callback) {
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    window.confirmCallback = callback;
    document.getElementById('modalConfirm').classList.add('active');
}

// ============================================================================
// LOGOUT
// ============================================================================

function handleLogout() {
    openConfirmModal('Confirmar Logout', 'Tem certeza de que deseja sair da aplicação?', () => {
        showToast('Desconectado com sucesso!');
        setTimeout(() => {
            alert('Logout simulado! (Em produção, você seria redirecionado para o login)');
        }, 500);
    });
}

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
