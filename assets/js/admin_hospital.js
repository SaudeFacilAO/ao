
const API = "/SaudeFacil/MediLab-1.0.0/assets/js/backadmin_hospital/";
console.log("JS CARREGADO");
// ===== DOM ELEMENTS =====
const sidebar = document.querySelector('.sidebar');
const menuToggle = document.querySelector('.menu-toggle');
const navLinks = document.querySelectorAll('.nav-link');
const pageContents = document.querySelectorAll('.page-content');
const pageTitleElement = document.getElementById('page-title');
const profileDropdownBtn = document.querySelector('.profile-dropdown-btn');
const profileDropdown = document.getElementById('profileDropdown');
const userRoleSelect = document.getElementById('userRole');
const especialidadeGroup = document.getElementById('especialidadeGroup');
const userFormModal = document.getElementById('userFormModal');

// ===== PAGE TITLES =====
const pageTitles = {
    dashboard: 'Dashboard',
    relatorios: 'Relatórios Gerenciais',
    usuarios: 'Gestão de Usuários',
    especialidades: 'Especialidades',
    escalas: 'Gestão de Escalas medicas',
    logs: 'Logs de Auditoria',
    notificacoes: 'Central de Notificações',
    configuracoes: 'Configurações',
    suporte: 'Suporte / Ajuda'
};

// ===== INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
    const todos = document.getElementById("todosDias");
    const dias = document.querySelectorAll(".dias-grid input");

    if (todos) {
        todos.addEventListener("change", () => {
            dias.forEach(d => d.checked = todos.checked);
        });
    }
    document.getElementById("turno").addEventListener("change", gerarPreviewHorarios);
    document.getElementById("intervaloInicio").addEventListener("change", gerarPreviewHorarios);
    document.getElementById("intervaloFim").addEventListener("change", gerarPreviewHorarios);
    document.getElementById("intervaloConsulta").addEventListener("input", gerarPreviewHorarios);
    setupEventListeners();
    generateCalendar();
    drawCharts();
    setupThemeToggle();
    carregarHospitais();
    carregarEspecialidadesSelect();
    carregarUsuarios();
    carregarEspecialidadeMedico();
    carregarMedicos();
});

// ===== EVENT LISTENERS =====
function setupEventListeners() {
    // Menu Toggle
    menuToggle.addEventListener('click', toggleMenu);

    // Navigation Links
    navLinks.forEach(link => {
        link.addEventListener('click', handleNavigation);
    });

    // Profile Dropdown
    profileDropdownBtn.addEventListener('click', toggleProfileDropdown);
    document.addEventListener('click', closeProfileDropdownOnClickOutside);

    

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === userFormModal) {
            closeUserForm();
        }
    });
}

// ===== SIDEBAR TOGGLE =====
function toggleMenu() {
    sidebar.classList.toggle('active');
}

function closeMenu() {
    if (window.innerWidth <= 768) {
        sidebar.classList.remove('active');
    }
}

// ===== NAVIGATION =====
function handleNavigation(e) {
    e.preventDefault();

    // Remove active state from all links
    navLinks.forEach(link => link.classList.remove('active'));
    
    // Add active state to clicked link
    this.classList.add('active');

    // Get the page ID
    const pageId = this.getAttribute('data-page');

    // Hide all pages
    pageContents.forEach(page => page.classList.remove('active'));

    // Show selected page
    const selectedPage = document.getElementById(pageId);
    if (selectedPage) {
        selectedPage.classList.add('active');
        pageTitleElement.textContent = pageTitles[pageId] || 'Dashboard';
    }

    // Close menu on mobile
    closeMenu();

    // Redraw charts if needed
    if (pageId === 'indicadores' || pageId === 'relatorios') {
        setTimeout(() => {
            drawCharts();
        }, 300);
    }
}

// ===== PROFILE DROPDOWN =====
function toggleProfileDropdown(e) {
    e.stopPropagation();
    profileDropdown.classList.toggle('active');
}

function closeProfileDropdownOnClickOutside(e) {
    if (!e.target.closest('.user-profile')) {
        profileDropdown.classList.remove('active');
    }
}

// ===== CALENDAR =====
function generateCalendar() {
    const calendar = document.getElementById('calendar');
    const currentMonthElement = document.getElementById('currentMonth');

    if (!calendar) return;

    const today = new Date();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();

    // Display current month and year
    const monthNames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    currentMonthElement.textContent = `${monthNames[currentMonth]} ${currentYear}`;

    // Clear calendar
    calendar.innerHTML = '';

    // Add day headers
    const dayHeaders = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];
    dayHeaders.forEach(day => {
        const dayHeader = document.createElement('div');
        dayHeader.className = 'calendar-day header';
        dayHeader.textContent = day;
        dayHeader.style.fontWeight = 'bold';
        dayHeader.style.textAlign = 'center';
        dayHeader.style.borderColor = 'transparent';
        calendar.appendChild(dayHeader);
    });

    // Get first day of month and number of days
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    // Add empty cells for days before month starts
    for (let i = 0; i < firstDay; i++) {
        const emptyDay = document.createElement('div');
        emptyDay.className = 'calendar-day empty';
        calendar.appendChild(emptyDay);
    }

    // Add days of month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayCell = document.createElement('div');
        dayCell.className = 'calendar-day';
        dayCell.textContent = day;

        // Highlight today
        if (day === today.getDate() && currentMonth === today.getMonth()) {
            dayCell.style.background = 'linear-gradient(135deg, #2563eb, #0ea5e9)';
            dayCell.style.color = 'white';
            dayCell.style.fontWeight = 'bold';
        }

        dayCell.addEventListener('click', function() {
            alert(`Data selecionada: ${day}/${currentMonth + 1}/${currentYear}`);
        });

        calendar.appendChild(dayCell);
    }
}

function previousMonth() {
    // Implementation for previous month
    alert('Navegação para mês anterior implementada');
}

function nextMonth() {
    // Implementation for next month
    alert('Navegação para próximo mês implementada');
}

// ===== CHARTS (Simple Canvas Implementation) =====
function drawCharts() {
    drawConsultasChart();
    drawEspecialidadesChart();
    drawMedicoChart();
}

function drawConsultasChart() {
    const canvas = document.getElementById('chartConsultasPorDia');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const width = canvas.parentElement.offsetWidth;
    const height = 300;
    
    canvas.width = width;
    canvas.height = height;

    // Simple bar chart
    const data = [12, 15, 18, 22, 19, 24, 20];
    const labels = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab', 'Dom'];
    const maxValue = Math.max(...data);
    const barWidth = width / (data.length * 1.5);
    const chartHeight = height * 0.8;

    // Draw bars
    data.forEach((value, index) => {
        const x = (index * width / data.length) + (barWidth / 2);
        const barHeight = (value / maxValue) * chartHeight;
        const y = height - barHeight - 40;

        // Bar gradient
        const gradient = ctx.createLinearGradient(0, y, 0, height - 40);
        gradient.addColorStop(0, '#2563eb');
        gradient.addColorStop(1, '#0ea5e9');

        ctx.fillStyle = gradient;
        ctx.fillRect(x, y, barWidth * 0.8, barHeight);

        // Label
        ctx.fillStyle = '#64748b';
        ctx.font = '12px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(labels[index], x + barWidth * 0.4, height - 10);

        // Value
        ctx.fillStyle = '#1e293b';
        ctx.font = 'bold 12px sans-serif';
        ctx.fillText(value, x + barWidth * 0.4, y - 5);
    });

    // Draw axes
    ctx.strokeStyle = '#e2e8f0';
    ctx.lineWidth = 1;
    ctx.beginPath();
    ctx.moveTo(30, 40);
    ctx.lineTo(30, height - 40);
    ctx.lineTo(width, height - 40);
    ctx.stroke();
}

function drawEspecialidadesChart() {
    const canvas = document.getElementById('chartEspecialidades');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const width = canvas.parentElement.offsetWidth;
    const height = 300;

    canvas.width = width;
    canvas.height = height;

    // Simple pie chart
    const data = [45, 30, 20, 5];
    const labels = ['Cardiologia', 'Pediatria', 'Ortopedia', 'Outros'];
    const colors = ['#2563eb', '#0ea5e9', '#10b981', '#f59e0b'];
    
    const centerX = width / 2;
    const centerY = height / 2;
    const radius = Math.min(width, height) / 2.5;

    let currentAngle = -Math.PI / 2;

    data.forEach((value, index) => {
        const sliceAngle = (value / 100) * (Math.PI * 2);

        // Draw slice
        ctx.beginPath();
        ctx.moveTo(centerX, centerY);
        ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
        ctx.closePath();
        ctx.fillStyle = colors[index];
        ctx.fill();

        // Draw label
        const labelAngle = currentAngle + sliceAngle / 2;
        const labelX = centerX + Math.cos(labelAngle) * (radius * 0.7);
        const labelY = centerY + Math.sin(labelAngle) * (radius * 0.7);

        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 11px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(value + '%', labelX, labelY);

        currentAngle += sliceAngle;
    });

    // Draw legend
    let legendY = 20;
    labels.forEach((label, index) => {
        ctx.fillStyle = colors[index];
        ctx.fillRect(10, legendY, 10, 10);
        ctx.fillStyle = '#1e293b';
        ctx.font = '12px sans-serif';
        ctx.textAlign = 'left';
        ctx.fillText(label + ' (' + data[index] + '%)', 25, legendY + 9);
        legendY += 20;
    });
}

function drawMedicoChart() {
    const canvas = document.getElementById('chartConsultasPorMedico');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const width = canvas.parentElement.offsetWidth;
    const height = 300;

    canvas.width = width;
    canvas.height = height;

    // Horizontal bar chart
    const doctors = ['Dr. Carlos', 'Dra. Maria', 'Dr. João', 'Dra. Ana'];
    const consultations = [45, 38, 42, 35];
    const maxValue = Math.max(...consultations);

    doctors.forEach((doctor, index) => {
        const y = 60 + (index * 50);
        const barWidth = (consultations[index] / maxValue) * (width - 150);

        // Bar
        const gradient = ctx.createLinearGradient(130, y, 130 + barWidth, y);
        gradient.addColorStop(0, '#2563eb');
        gradient.addColorStop(1, '#0ea5e9');

        ctx.fillStyle = gradient;
        ctx.fillRect(130, y - 15, barWidth, 25);

        // Label
        ctx.fillStyle = '#1e293b';
        ctx.font = '12px sans-serif';
        ctx.textAlign = 'right';
        ctx.fillText(doctor, 120, y + 5);

        // Value
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 11px sans-serif';
        ctx.textAlign = 'left';
        ctx.fillText(consultations[index], 135 + barWidth + 5, y + 5);
    });
}

// ===== USER MANAGEMENT =====
function openUserForm() {
    userFormModal.classList.add('active');
}

function closeUserForm() {
    userFormModal.classList.remove('active');
}

function editUser(userId) {
    console.log('Editando usuário:', userId);
    openUserForm();
}

function deleteUser(userId) {
    if (confirm('Tem certeza que deseja remover este usuário?')) {
        console.log('Removendo usuário:', userId);
        alert('Usuário removido com sucesso!');
    }
}

// ===== REPORTS =====
function generateReport() {
    const dataInicial = document.getElementById('filterDataInicial').value;
    const dataFinal = document.getElementById('filterDataFinal').value;
    const medico = document.getElementById('filterMedico').value;
    const especialidade = document.getElementById('filterEspecialidade').value;
    const tipoRelatorio = document.getElementById('filterTipoRelatorio').value;

    if (!dataInicial || !dataFinal || !tipoRelatorio) {
        alert('Por favor, preencha todos os filtros obrigatórios!');
        return;
    }

    console.log('Gerando relatório:', {
        dataInicial,
        dataFinal,
        medico,
        especialidade,
        tipoRelatorio
    });

    alert('Relatório gerado com sucesso!');
}

function exportReport() {
    alert('Exportando relatório em formato CSV...');
    // Implementation for export
}

function printReport() {
    window.print();
}

// ===== LOGS =====
function filterLogs() {
    const date = document.getElementById('logDate').value;
    const user = document.getElementById('logUser').value;
    const action = document.getElementById('logAction').value;

    console.log('Filtrando logs:', { date, user, action });
    alert('Filtros aplicados com sucesso!');
}

// ===== NOTIFICATIONS =====
function markAllAsRead() {
    const notificationItems = document.querySelectorAll('.notification-item.unread');
    notificationItems.forEach(item => {
        item.classList.remove('unread');
    });

    // Update badge count
    document.getElementById('notification-count').textContent = '0';
    alert('Todas as notificações foram marcadas como lidas!');
}

function clearNotifications() {
    const notificationsList = document.querySelector('.notifications-list');
    if (notificationsList && confirm('Tem certeza que deseja limpar todas as notificações?')) {
        notificationsList.innerHTML = '<p style="text-align: center; color: #64748b;">Nenhuma notificação</p>';
        document.getElementById('notification-count').textContent = '0';
    }
}

// ===== SETTINGS =====
function saveProfile() {
    alert('Perfil salvo com sucesso!');
}

function changePassword() {
    alert('Senha alterada com sucesso!');
}

function saveNotificationSettings() {
    alert('Configurações de notificação salvas!');
}

function applyTheme() {
    const theme = document.querySelector('input[name="theme"]:checked').value;
    if (theme === 'dark') {
        document.documentElement.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
    }
    alert(`Tema ${theme === 'dark' ? 'escuro' : 'claro'} aplicado!`);
}

// ===== SUPPORT =====
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });

    // Remove active class from buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    // Show selected tab
    const selectedTab = document.getElementById(tabName);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }

    // Add active class to clicked button
    event.target.classList.add('active');
}

// ===== THEME PERSISTENCE =====
function setupThemeToggle() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark-mode');
        const darkRadio = document.querySelector('input[name="theme"][value="dark"]');
        if (darkRadio) {
            darkRadio.checked = true;
        }
    }
}

// ===== RESPONSIVE ADJUSTMENTS =====
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        sidebar.classList.remove('active');
    }
    drawCharts();
});

// ===== SMOOTH INTERACTIONS =====
document.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', function(e) {
        if (this.href === '#') {
            e.preventDefault();
        }
    });
});

// ===== UTILITY FUNCTIONS =====
function getFormattedDate(date) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('pt-BR', options);
}

function getFormattedTime(date) {
    const options = { hour: '2-digit', minute: '2-digit' };
    return date.toLocaleTimeString('pt-BR', options);
}

// ===== DEBUG =====
console.log('%c Saúde Fácil - Painel Administrativo Hospitalar', 'color: #2563eb; font-size: 16px; font-weight: bold;');
console.log('%c Sistema carregado com sucesso!', 'color: #10b981; font-size: 12px;');


// ============================================
// ESPECIALIDADES - ADMIN (VERSÃO CORRIGIDA)
// ============================================


// ============================================
// CARREGAR LISTA
// ============================================

async function carregarEspecialidades() {
    try {
        const res = await fetch(API + "list_especialidades.php");
        const dados = await res.json();

        const tbody = document.getElementById("listaEspecialidades");
        if (!tbody) return;

        tbody.innerHTML = "";

        if (!Array.isArray(dados) || dados.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" style="text-align:center; color:#888;">
                        Nenhuma especialidade cadastrada
                    </td>
                </tr>`;
            return;
        }

        dados.forEach(e => {
            tbody.innerHTML += `
                <tr>
                    <td contenteditable="true"
                        onblur="editarEspecialidade(${e.id}, this, 'nome')">
                        ${e.nome}
                    </td>

                    <td contenteditable="true"
                        onblur="editarEspecialidade(${e.id}, this, 'descricao')">
                        ${e.descricao ?? ''}
                    </td>

                    <td>
                        <select onchange="editarEspecialidade(${e.id}, this, 'ativa')">
                            <option value="1" ${e.ativa == 1 ? "selected" : ""}>Ativo</option>
                            <option value="0" ${e.ativa == 0 ? "selected" : ""}>Inativo</option>
                        </select>
                    </td>

                    <td>
                        <button class="btn btn-danger btn-sm"
                            onclick="eliminarEspecialidade(${e.id})">
                            🗑
                        </button>
                    </td>
                </tr>`;
        });

    } catch (error) {
        console.error("Erro ao carregar especialidades:", error);
        showToast("Erro ao carregar especialidades", true);
    }
}

// ============================================
// CADASTRAR
// ============================================

document.getElementById("formEspecialidade")?.addEventListener("submit", async (e) => {
    e.preventDefault();

    const nome = document.getElementById("espNome").value.trim();
    const descricao = document.getElementById("espDescricao").value.trim();

    if (!nome) {
        showToast("Informe o nome da especialidade", true);
        return;
    }

    try {
        const res = await fetch(API + "create_especialidade.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ nome, descricao })
        });

        const result = await res.json();

        if (result.status === "ok") {
            e.target.reset();
            carregarEspecialidades();
            showToast("Especialidade cadastrada com sucesso!");
        } else {
            showToast(result.msg || "Erro ao cadastrar", true);
        }

    } catch (error) {
        console.error("Erro ao cadastrar:", error);
        showToast("Erro de conexão com o servidor", true);
    }
});

// ============================================
// EDITAR INLINE
// ============================================

async function editarEspecialidade(id, elemento) {
    const linha = elemento.closest("tr");

    const nome = linha.children[0].innerText.trim();
    const descricao = linha.children[1].innerText.trim();
    const ativa = linha.children[2].querySelector("select").value;

    try {
        const res = await fetch(API + "update_especialidade.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id,
                nome,
                descricao,
                ativa
            })
        });

        const result = await res.json();

        if (result.status !== "ok") {
            showToast("Erro ao atualizar", true);
        } else {
            showToast("Atualizado");
        }

    } catch (error) {
        console.error("Erro ao atualizar:", error);
        showToast("Erro de conexão", true);
    }
}

// ============================================
// ELIMINAR
// ============================================

async function eliminarEspecialidade(id) {

    if (!confirm("Deseja eliminar esta especialidade?")) return;

    try {
        const res = await fetch(API + "delete_especialidade.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id })
        });

        const result = await res.json();

        if (result.status === "ok") {
            carregarEspecialidades();
            showToast("Especialidade removida!");
        } else {
            showToast(result.msg || "Erro ao remover", true);
        }

    } catch (error) {
        console.error("Erro ao eliminar:", error);
        showToast("Erro de conexão", true);
    }
}

// ============================================
// TOAST
// ============================================

function showToast(msg, erro = false) {
    const anterior = document.querySelector(".toast-message");
    if (anterior) anterior.remove();

    const toast = document.createElement("div");
    toast.className = "toast-message";

    toast.style.position = "fixed";
    toast.style.bottom = "20px";
    toast.style.right = "20px";
    toast.style.padding = "12px 18px";
    toast.style.borderRadius = "8px";
    toast.style.color = "#fff";
    toast.style.fontSize = "14px";
    toast.style.zIndex = "9999";
    toast.style.background = erro ? "#e74c3c" : "#2ecc71";

    toast.innerHTML = msg;

    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 3000);
}

// ============================================
// INIT
// ============================================

document.addEventListener("DOMContentLoaded", () => {
    carregarEspecialidades();
});

//«««««««««««««««««««««««««««««««««Medico«««««««««««
document.addEventListener("DOMContentLoaded", function () {

    const role = document.getElementById("userRole");
    const medicoFields = document.querySelectorAll(".medico-only");
    const secretarioFields = document.querySelectorAll(".secretario-only");

    if (!role) return;

    function resetFields() {
        medicoFields.forEach(el => {
            el.style.display = "none";
            el.querySelectorAll("input, select").forEach(i => i.value = "");
        });

        secretarioFields.forEach(el => {
            el.style.display = "none";
            el.querySelectorAll("input, select").forEach(i => i.value = "");
        });
    }

    function handleRoleChange() {
        resetFields();

        if (role.value === "medico") {
            medicoFields.forEach(el => el.style.display = "block");
        }

        if (role.value === "secretario") {
            secretarioFields.forEach(el => el.style.display = "block");
        }
    }

    role.addEventListener("change", handleRoleChange);

    // 🔥 estado inicial (IMPORTANTE)
    handleRoleChange();
});
//«««««««««««««««Buscar hospital«««««««««««««««
async function carregarHospitais() {
    try {
        const res = await fetch(API + "list_hospitais.php");

        // 🔥 VER SE O FETCH FALHOU
        if (!res.ok) {
            throw new Error("Erro HTTP: " + res.status);
        }

        const data = await res.json();

        const select = document.getElementById("hospitalId");
        if (!select) return;

        select.innerHTML = `<option value="">Selecione o hospital</option>`;

        if (data.status === "ok") {
            data.data.forEach(h => {
                select.innerHTML += `<option value="${h.id}">${h.nome}</option>`;
            });
        }

    } catch (error) {
        console.error("Erro REAL:", error);
        alert("Erro ao buscar hospitais");
    }
}
//««««««««««««««««««««Buscar especialidade«
async function carregarEspecialidadesSelect() {
    const res = await fetch(API + "get_especialidades_select.php");
    const result = await res.json();

    const select = document.getElementById("especialidadeId");
    if (!select) return;

    select.innerHTML = `<option value="">Selecione</option>`;

    if (result.status === "ok") {
        result.data.forEach(e => {
            select.innerHTML += `<option value="${e.id}">${e.nome}</option>`;
        });
    } else {
        console.error("Erro ao buscar especialidades");
    }
}
//««««««««««««««««««««Cadastrar usuario

const form = document.getElementById("formUsuario");
const btnSalvar = form.querySelector("button[type='submit']");

// =========================
// ESTADO
// =========================
const estado = {
    nome: false,
    email: false,
    emailExistente: false,
    telefone: false,
    bi: false,
    senha: false,
    tipo: false
};

// =========================
// UTIL ERRO
// =========================
function setError(input, msg) {

    let error = input.parentNode.querySelector(".error-msg");

    if (!error) {
        error = document.createElement("small");
        error.classList.add("error-msg");
        input.parentNode.appendChild(error);
    }

    error.innerText = msg;
    error.style.color = "red";
}

// =========================
// LIMPAR ERRO
// =========================
function clearError(input) {

    const error = input.parentNode.querySelector(".error-msg");
    if (error) error.remove();
}

// =========================
// BOTÃO SALVAR (CORRIGIDO)
// =========================
function toggleButton() {

    const valido = Object.values(estado).every(v => v === true);

    btnSalvar.disabled = !valido;
    btnSalvar.style.opacity = valido ? "1" : "0.5";
}

// =========================
// NOME
// =========================
document.getElementById("nome").addEventListener("input", (e) => {

    const v = e.target.value.trim();

    estado.nome = v.length >= 3;

    if (!estado.nome) setError(e.target, "Nome muito curto");
    else clearError(e.target);

    toggleButton();
});


document.getElementById("email").addEventListener("input", async (e) => {

    const v = e.target.value.trim();

    // =========================
    // 1. VERIFICAR MINÚSCULAS (OBRIGATÓRIO)
    // =========================
    const hasUppercase = /[A-Z]/.test(v);

    if (hasUppercase) {

        estado.email = false;
        estado.emailExistente = false;

        setError(e.target, "Email deve estar em minúsculas");

        toggleButton();
        return;
    }

    // =========================
    // 2. FORMATO DO EMAIL
    // =========================
    const regex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;

    if (!regex.test(v)) {

        estado.email = false;
        estado.emailExistente = false;

        setError(e.target, "Email inválido");

        toggleButton();
        return;
    }

    estado.email = true;
    clearError(e.target);

    // =========================
    // 3. VERIFICAR SE EXISTE NA BD
    // =========================
    try {

        
        const res = await fetch(
            "/SaudeFacil/MediLab-1.0.0/phpconexao/check_email.php",
            {
                method: "POST",
                headers: {
                "Content-Type": "application/json"
            },
                body: JSON.stringify({ email: v })
            }
        );

        const r = await res.json();

        if (!r || typeof r.exists === "undefined") {
            throw new Error("Resposta inválida");
        }

        estado.emailExistente = !r.exists;

        if (r.exists) {

            setError(e.target, "Email já existe");

        } else {

            clearError(e.target);
        }

    } catch (err) {

        console.error(err);

        estado.emailExistente = false;

        setError(e.target, "Erro ao verificar email");
    }

    toggleButton();
});
// =========================
// TELEFONE (9 DIGITOS + BLOQUEIO) verifica se exite na bd
// =========================

document.getElementById("telefone").addEventListener("input", async (e) => {

    let v = e.target.value.replace(/\D/g, "");

    // =========================
    // LIMITE 9 DÍGITOS
    // =========================
    v = v.slice(0, 9);

    e.target.value = v;

    // =========================
    // VALIDAÇÃO LOCAL
    // =========================
    const valido = /^9\d{8}$/.test(v);

    if (!valido) {

        estado.telefone = false;
        estado.telefoneExistente = false;

        setError(e.target, "Telefone inválido (9XXXXXXXX)");
        toggleButton();
        return;
    }

    estado.telefone = true;
    clearError(e.target);

    // =========================
    // VERIFICAR NA BD
    // =========================
    try {

        const res = await fetch(
            "/SaudeFacil/MediLab-1.0.0/phpconexao/check_telefone.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ telefone: v })
            }
        );

        const r = await res.json();

        if (!r || typeof r.exists === "undefined") {
            throw new Error("Resposta inválida");
        }

        estado.telefoneExistente = !r.exists;

        if (r.exists) {

            setError(e.target, "Este telefone já existe");

        } else {

            clearError(e.target);
        }

    } catch (err) {

        console.error(err);

        estado.telefoneExistente = false;

        setError(e.target, "Erro ao verificar telefone");
    }

    toggleButton();
});


document.getElementById("bi").addEventListener("input", async (e) => {

    let v = e.target.value.toUpperCase().replace(/\s/g, "");

    // =========================
    // LIMITE 14 CARACTERES
    // =========================
    if (v.length > 14) v = v.slice(0, 14);

    e.target.value = v;

    // =========================
    // VALIDAÇÃO LOCAL
    // =========================
    const validoFormato = /^00\d{7}[A-Z]{2}\d{3}$/.test(v);

    if (!validoFormato) {

        estado.bi = false;
        estado.biExistente = false;

        setError(e.target, "BI inválido (formato: 00XXXXXXXXXX)");
        toggleButton();
        return;
    }

    estado.bi = true;
    clearError(e.target);

    // =========================
    // VERIFICAR NA BD
    // =========================
    try {

        const res = await fetch(
            "/SaudeFacil/MediLab-1.0.0/phpconexao/check_bi.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ bi: v })
            }
        );

        const r = await res.json();

        if (!r || typeof r.exists === "undefined") {
            throw new Error("Resposta inválida");
        }

        estado.biExistente = !r.exists;

        if (r.exists) {

            setError(e.target, "Este BI já existe");

        } else {

            clearError(e.target);
        }

    } catch (err) {

        console.error(err);

        estado.biExistente = false;

        setError(e.target, "Erro ao verificar BI");
    }

    toggleButton();
});

// =========================
// SENHA (4 DIGITOS BLOQUEADO)
// =========================
document.getElementById("senha").addEventListener("input", (e) => {

    let v = e.target.value.replace(/\D/g, "");

    v = v.slice(0, 4);

    e.target.value = v;

    estado.senha = /^\d{4}$/.test(v);

    if (!estado.senha) setError(e.target, "Senha deve ter 4 dígitos");
    else clearError(e.target);

    toggleButton();
});

// =========================
// TIPO USUÁRIO
// =========================
document.getElementById("userRole").addEventListener("change", (e) => {

    estado.tipo = !!e.target.value;
    toggleButton();
});

// inicial
toggleButton();


// =========================
// SUBMIT FINAL CORRIGIDO
// =========================
form.addEventListener("submit", async function (e) {

    e.preventDefault();

    // =========================
    // BLOQUEIO DO BOTÃO
    // =========================
    if (btnSalvar.disabled) {
        alert("Preencha todos os campos corretamente");
        return;
    }

    // =========================
    // VALIDAÇÃO EMAIL EXISTENTE
    // =========================
    if (estado.emailExistente === false) {
        alert("Email já existe");
        return;
    }

    const tipoUsuario = document.getElementById("userRole").value;

    // =========================
    // DADOS BASE
    // =========================
    const data = {
        nome: document.getElementById("nome").value,
        email: document.getElementById("email").value,
        telefone: document.getElementById("telefone").value,
        genero: document.getElementById("genero").value,
        bi: document.getElementById("bi").value,
        senha: document.getElementById("senha").value,
        tipo_usuario: tipoUsuario,
        hospital_id: document.getElementById("hospitalId").value,

        crm: null,
        especialidade_id: null,
        id_funcionario: null,
        teleconsulta: null
    };

    // =========================
    // MÉDICO
    // =========================
    if (tipoUsuario === "medico") {

        data.especialidade_id =
            document.getElementById("especialidadeId").value;

        data.teleconsulta =
            document.getElementById("teleconsulta").value;
    }

    // =========================
    // SECRETÁRIO (REMOVIDO ID INPUT BUG)
    // =========================
    // NÃO precisa id_funcionario no front (é gerado no backend)
    // data.id_funcionario removido

    try {

        const res = await fetch(API + "create_usuario.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        const result = await res.json();

        console.log(result);

        if (result.status === "ok") {

            alert("Usuário criado com sucesso!");

            this.reset();

            // =========================
            // RESET SEGURO DO ESTADO
            // =========================
            for (let key in estado) {
                estado[key] = false;
            }

            toggleButton();

            closeUserForm();

        } else {
            alert(result.msg || "Erro ao criar usuário");
        }

    } catch (err) {

        console.error(err);
        alert("Erro na comunicação com o servidor");
    }
});
//««««««««««««««««««««Listar Usuario«««««««««««««««««««««««««
async function carregarUsuarios() {
    try {
        const res = await fetch(API + "list_usuarios.php");
        const result = await res.json();

        const tbody = document.querySelector(".users-table tbody");
        tbody.innerHTML = "";

        if (result.status !== "ok") {
            alert(result.msg || "Erro ao carregar usuários");
            return;
        }

        result.data.forEach(u => {

            const ativoTexto = u.ativo == 1 ? "Ativo" : "Inativo";
            const ativoClass = u.ativo == 1 ? "active" : "inactive";

            tbody.innerHTML += `
                <tr>
                    <td>${u.nome}</td>
                    <td>${u.email}</td>
                    <td>${u.telefone || "-"}</td>
                    <td>${u.genero || "-"}</td>
                    <td>${u.bi || "-"}</td>
                    <td>${u.tipo_usuario}</td>

                    <td>${u.identificacao || "-"}</td>
                    <td>${u.especialidade || "-"}</td>

                    <td>
                        <span class="status-badge ${ativoClass}">
                            ${ativoTexto}
                        </span>
                    </td>

                    <td>
                        <button class="action-btn edit" onclick="editUser(${u.id})">Editar</button>
                        <button class="action-btn delete" onclick="deleteUser(${u.id})">Remover</button>
                    </td>
                </tr>
            `;
        });

    } catch (error) {
        console.error("Erro ao carregar usuários:", error);
    }
}
//««««««««««««««««««««««««««Editar usuario«««««««««««««««««««««««««««««««««
function editUser(id) {
    alert("Editar usuário ID: " + id);

    // aqui depois vamos:
    // - abrir modal
    // - preencher formulário
    // - atualizar no backend
}
//«««««««««««««««««««««««««««««««««Remover usuario««««««««««««««««««
async function deleteUser(id) {

    if (!confirm("Tens certeza que queres remover este usuário?")) return;

    try {
        const res = await fetch(API + "delete_usuario.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id })
        });

        const result = await res.json();

        if (result.status === "ok") {
            alert("Usuário removido!");
            carregarUsuarios(); // recarrega tabela
        } else {
            alert(result.msg || "Erro ao remover");
        }

    } catch (error) {
        console.error(error);
    }
}

//«««««««««««««««««««««««««««««««Editar usuario
async function editUser(id) {

    try {
        const res = await fetch(API + "editar_usuario.php?id=" + id);
        const result = await res.json();

        if (result.status !== "ok") {
            alert(result.msg);
            return;
        }

        const u = result.usuario;
        const extra = result.extra || {};

        // abrir modal
        openUserForm();

        // preencher base
        document.getElementById("nome").value = u.nome;
        document.getElementById("email").value = u.email;
        document.getElementById("telefone").value = u.telefone || "";
        document.getElementById("genero").value = u.genero || "";
        document.getElementById("bi").value = u.bi || "";
        document.getElementById("userRole").value = u.tipo_usuario;
        document.getElementById("hospitalId").value = extra.hospital_id || "";

        // disparar mudança de role (mostrar campos)
        document.getElementById("userRole").dispatchEvent(new Event("change"));

        // preencher médico
        if (u.tipo_usuario === "medico") {
            document.getElementById("especialidadeId").value = extra.especialidade_id || "";
        }

        // preencher secretário
        if (u.tipo_usuario === "secretario") {
            document.getElementById("idFuncionario").value = extra.id_funcionario || "";
        }

        // guardar ID para update
        document.getElementById("formUsuario").setAttribute("data-id", id);

    } catch (error) {
        console.error(error);
    }
}
//«««««««««««««««««««««««««««««««««««««««Carregar especilidade medico
async function carregarEspecialidadeMedico() {
    const medicoId = document.getElementById("medicoSelect").value;
    const input = document.getElementById("especialidadeMedico");

    console.log("ID MÉDICO:", medicoId); // DEBUG

    if (!medicoId) {
        input.value = "";
        return;
    }

    try {
        const res = await fetch(API + "obter_especialidade_medico.php?medico_id=" + medicoId);
        const data = await res.json();

        console.log("RESPOSTA:", data); // DEBUG

        if (data.status === "ok" && data.data) {
            input.value = data.data.nome;
        } else {
            input.value = "Sem especialidade";
        }

    } catch (error) {
        console.error("ERRO:", error);
        input.value = "Erro";
    }
}
//«««««««««««««««««««««««««««««««««««««««««««««Carregar medicos
async function carregarMedicos() {
    try {
        const res = await fetch(API + "list_medicos_select.php");
        const data = await res.json();

        console.log("MEDICOS:", data); // DEBUG

        const select = document.getElementById("medicoSelect");

        if (!select) {
            console.error("Select medicoSelect não encontrado");
            return;
        }

        select.innerHTML = `<option value="">Selecione o médico</option>`;

        data.data.forEach(m => {
            select.innerHTML += `
                <option value="${m.id}">${m.nome}</option>
            `;
        });

    } catch (error) {
        console.error("Erro ao carregar médicos:", error);
    }
}

//««««««««««««««««««««««««««««««««««««««««««CONTROLAR “TODOS OS DIAS”
function controlarTodosDias() {
    const todos = document.getElementById("todosDias");
    const dias = document.querySelectorAll(".dias-grid input");

    todos.addEventListener("change", () => {
        dias.forEach(d => d.checked = todos.checked);
    });
}
//««««««««««««««««««««««««««««««««««««««OBTER DIAS SELECIONADOS
function obterDiasSelecionados() {
    const dias = document.querySelectorAll(".dias-grid input:checked");

    const mapa = {
        segunda: 1,
        terca: 2,
        quarta: 3,
        quinta: 4,
        sexta: 5,
        sabado: 6
    };

    return Array.from(dias).map(d => mapa[d.value]);
}
//«««««««««««««««««««««««««««««««««««««««««««««DEFINIR HORÁRIO PELO TURNO
function obterHorarioTurno() {
    const turno = document.getElementById("turno").value;

    if (turno === "manha") {
        return { inicio: "08:00", fim: "12:00" };
    }

    if (turno === "tarde") {
        return { inicio: "13:00", fim: "15:00" };
    }

    return null;
}

//««««««««««««««««««««««««««««««««««««««««««««««AUXILIARES
function toMin(hora) {
    const [h, m] = hora.split(":").map(Number);
    return h * 60 + m;
}

function formatHora(min) {
    const h = Math.floor(min / 60);
    const m = min % 60;
    return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
}
//««««««««««««««««««««««««««««««««««««««««««««««««SALVAR ESCALA
async function gerarEscala() {

    const medico_id = document.getElementById("medicoSelect").value;
    const dias = obterDiasSelecionados();
    const intervalo = document.getElementById("intervaloConsulta").value;

    const pausaInicio = document.getElementById("intervaloInicio").value;
    const pausaFim = document.getElementById("intervaloFim").value;

    const turno = obterHorarioTurno();

    if (!medico_id || dias.length === 0 || !turno) {
        alert("Preencha todos os campos!");
        return;
    }

    const payload = {
        medico_id,
        dias,
        hora_inicio: turno.inicio,
        hora_fim: turno.fim,
        intervalo,
        pausaInicio,
        pausaFim
    };

    const res = await fetch(API + "salvar_escala.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    });

    const result = await res.json();

    if (result.status === "ok") {
        alert("Escala salva com sucesso!");
    } else {
        alert(result.msg);
    }
}
//««««««««««««««««««««««««««««««««««««««««««««««««««mostrarHorarioTurno
function gerarPreviewHorarios() {

    const intervalo = parseInt(document.getElementById("intervaloConsulta").value);

    if (!intervalo || intervalo <= 0) {
        document.getElementById("previewHorario").value = "Defina o intervalo (em minutos)";
        return;
    }

    const turno = obterHorarioTurno();
    if (!turno) return;

    const pausaInicio = document.getElementById("intervaloInicio").value;
    const pausaFim = document.getElementById("intervaloFim").value;

    let atual = toMin(turno.inicio);
    const limite = toMin(turno.fim);

    const pausaIni = pausaInicio ? toMin(pausaInicio) : null;
    const pausaF = pausaFim ? toMin(pausaFim) : null;

    let lista = [];

    while (atual + intervalo <= limite) {

        let proximo = atual + intervalo;

        // 🔥 ignora almoço
        if (pausaIni && pausaF && atual < pausaF && proximo > pausaIni) {
            atual = pausaF;
            continue;
        }

        lista.push(formatHora(atual) + " - " + formatHora(proximo));
        atual = proximo;
    }

    document.getElementById("previewHorario").value = lista.join("\n");
}
//««««««««««««««««««««««««««««««««««««««pesquisar
document.getElementById("globalSearch").addEventListener("input", function () {

    const value = this.value;

    const currentPage = document.querySelector(".page-content.active");

    if (!currentPage) return;

    if (currentPage.id === "escalas") {
        carregarEscalas(value);
    }
});

function carregarEscalas(search = "") {

    fetch(API + "list_escalas.php?search=" + search)
        .then(res => res.json())
        .then(data => renderEscalas(data.data));
    
}
//«««««««««««««««««««LISTAR ESCALAS
async function carregarEscalas(search = "") {

    const res = await fetch(API + "list_escalas.php?search=" + search);
    const data = await res.json();

    const container = document.getElementById("listaEscalas");
    container.innerHTML = "";

    if (data.status !== "ok") return;

    let agrupado = {};

    data.data.forEach(item => {

        const key = item.medico_id;

        if (!agrupado[key]) {
            agrupado[key] = {
                nome: item.medico_nome,
                especialidade: item.especialidade,
                dias: {}
            };
        }

        const dia = item.dia_semana;

        if (!agrupado[key].dias[dia]) {
            agrupado[key].dias[dia] = [];
        }

        agrupado[key].dias[dia].push({
            inicio: item.slot_inicio,
            fim: item.slot_fim,
            status: item.status
        });
    });

    Object.values(agrupado).forEach(medico => {

        let htmlDias = "";

        Object.keys(medico.dias).forEach(dia => {

            let horarios = medico.dias[dia]
                .map(h => `
                    <span class="horario ${h.status}">
                        ${h.inicio} - ${h.fim}
                    </span>
                `).join("");

            htmlDias += `
                <div class="dia-item">
                    <strong>Dia ${dia}</strong>
                    <div class="horarios">${horarios}</div>
                </div>
            `;
        });

        container.innerHTML += `
            <div class="escala-card">

                <div class="escala-header">
                    <h4>${medico.nome}</h4>
                    <span class="especialidade">${medico.especialidade}</span>
                </div>

                <div class="escala-body">
                    ${htmlDias}
                </div>

                <div class="escala-actions">
                    <button class="btn-edit">Editar</button>
                    <button class="btn-delete">Eliminar</button>
                </div>

            </div>
        `;
    });
}
//««««««««««««««««««««««««««««««««Render escalas
function renderEscalas(dados) {

    const container = document.getElementById("listaEscalas");
    container.innerHTML = "";

    if (!dados || dados.length === 0) {
        container.innerHTML = "<p>Nenhuma escala encontrada</p>";
        return;
    }

    let agrupado = {};

    dados.forEach(item => {

        if (!agrupado[item.medico_id]) {
            agrupado[item.medico_id] = {
                nome: item.medico_nome,
                especialidade: item.especialidade,
                dias: {}
            };
        }

        if (!agrupado[item.medico_id].dias[item.dia_semana]) {
            agrupado[item.medico_id].dias[item.dia_semana] = [];
        }

        agrupado[item.medico_id].dias[item.dia_semana].push(item);
    });

    Object.values(agrupado).forEach(medico => {

        let diasHTML = "";

        Object.keys(medico.dias).forEach(dia => {

            let horarios = medico.dias[dia].map(h => `
                <span class="horario ${h.status}">
                    ${h.slot_inicio} - ${h.slot_fim}
                </span>
            `).join("");

            diasHTML += `
                <div class="dia-item">
                    <strong>Dia ${dia}</strong>
                    <div class="horarios">${horarios}</div>
                </div>
            `;
        });

        container.innerHTML += `
            <div class="escala-card">

                <div class="escala-header">
                    <h4>${medico.nome}</h4>
                    <span class="especialidade">${medico.especialidade}</span>
                </div>

                <div class="escala-body">
                    ${diasHTML}
                </div>

                <div class="escala-actions">
                    <button class="btn-edit">Editar</button>
                    <button class="btn-delete">Eliminar</button>
                </div>

            </div>
        `;
    });
}
//««««««««««««««««««««««««««««««««Editar escala
async function editarEscala(id) {

    alert("Editar escala ID: " + id);

    // depois vamos carregar os dados no formulário
}

//««««««««««««««««««««««««««««««««Eliminar escala
async function eliminarEscala(id) {

    const confirmar = confirm("Deseja eliminar esta escala?");

    if (!confirmar) return;

    try {

        const res = await fetch(API + "delete_escala.php?id=" + id);

        const data = await res.json();

        if (data.status === "ok") {

            alert("Escala eliminada!");

            carregarEscalas();

        } else {

            alert(data.msg);
        }

    } catch (erro) {

        console.error(erro);

        alert("Erro ao eliminar escala");
    }
}

//Botao sair

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