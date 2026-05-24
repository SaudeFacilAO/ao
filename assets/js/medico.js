// ==================== UTILIDADES ==================== //

/**
 * Sistema completo de gerenciamento da dashboard médica
 * Saúde Fácil - Plataforma de Telemedicina
 */

const DashboardManager = {
  currentPage: 'dashboard',
  isDarkMode: localStorage.getItem('darkMode') === 'true',
  
  // Inicialização
  init() {
    this.loadDarkMode();
    this.setupEventListeners();
    this.renderCalendar();
    this.renderScheduleTimeline();
    this.setupCharts();
    console.log("[v0] Dashboard inicializado com sucesso");
    carregarPacientes();
  },

  // ==================== DARK MODE ==================== //
  loadDarkMode() {
    if (this.isDarkMode) {
      document.documentElement.classList.add('dark-mode');
    }
  },

  toggleDarkMode() {
    this.isDarkMode = !this.isDarkMode;
    localStorage.setItem('darkMode', this.isDarkMode);
    document.documentElement.classList.toggle('dark-mode');
  },

  // ==================== EVENT LISTENERS ==================== //
  setupEventListeners() {
    // Navegação Sidebar
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const page = link.getAttribute('data-page');
        this.navigateToPage(page);
      });
    });

    // Toggle Sidebar
    const toggleBtn = document.getElementById('toggleSidebar');
    if (toggleBtn) {
      toggleBtn.addEventListener('click', () => {
        document.querySelector('.sidebar').classList.toggle('collapsed');
      });
    }

    // Menu Toggle Mobile
    const menuToggle = document.getElementById('menuToggle');
    if (menuToggle) {
      menuToggle.addEventListener('click', () => {
        document.querySelector('.sidebar').classList.toggle('active');
      });
    }

    // Tema
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
      themeToggle.addEventListener('click', () => {
        this.toggleDarkMode();
        themeToggle.innerHTML = this.isDarkMode ? 
          '<i class="fas fa-sun"></i>' : 
          '<i class="fas fa-moon"></i>';
      });
    }

    // User Menu Dropdown
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    if (userMenuBtn && userDropdown) {
      userMenuBtn.addEventListener('click', () => {
        userDropdown.classList.toggle('active');
      });

      document.addEventListener('click', (e) => {
        if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
          userDropdown.classList.remove('active');
        }
      });
    }

    // Calendar Navigation
    const prevMonth = document.getElementById('prevMonth');
    const nextMonth = document.getElementById('nextMonth');
    if (prevMonth && nextMonth) {
      prevMonth.addEventListener('click', () => {
        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.renderCalendar();
      });

      nextMonth.addEventListener('click', () => {
        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.renderCalendar();
      });
    }

    // Chat
    const sendBtn = document.getElementById('sendBtn');
    const messageInput = document.getElementById('messageInput');
    if (sendBtn && messageInput) {
      sendBtn.addEventListener('click', () => this.sendMessage(messageInput));
      messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
          this.sendMessage(messageInput);
        }
      });
    }

    // Tabs
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const tabName = btn.getAttribute('data-tab');
        this.switchTab(tabName);
      });
    });

    // FAQ
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
      question.addEventListener('click', () => {
        question.classList.toggle('active');
        question.nextElementSibling.classList.toggle('active');
      });
    });

    // Chat Items
    const chatItems = document.querySelectorAll('.chat-item');
    chatItems.forEach(item => {
      item.addEventListener('click', () => {
        chatItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
      });
    });

    // Patient Selection
    const patientItems = document.querySelectorAll('.patient-item');
    patientItems.forEach(item => {
      item.addEventListener('click', () => {
        patientItems.forEach(i => i.classList.remove('selected'));
        item.classList.add('selected');
      });
    });

    // Notification Bell
    const notificationBtn = document.getElementById('notificationBtn');
    if (notificationBtn) {
      notificationBtn.addEventListener('click', () => {
        this.navigateToPage('notificacoes');
      });
    }
  },

  // ==================== NAVEGAÇÃO ==================== //
  navigateToPage(pageName) {
    // Update active nav link
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
      link.classList.remove('active');
      if (link.getAttribute('data-page') === pageName) {
        link.classList.add('active');
      }
    });

    // Hide all pages
    const pages = document.querySelectorAll('.page-content');
    pages.forEach(page => page.classList.remove('active'));

    // Show selected page
    const selectedPage = document.getElementById(pageName);
    if (selectedPage) {
      selectedPage.classList.add('active');
      this.currentPage = pageName;
      
      // Update breadcrumb
      const pageTitles = {
        'dashboard': 'Dashboard',
        'agenda': 'Agenda',
        'pacientes': 'Pacientes',
        'prontuario': 'Prontuário',
        'chat': 'Chat/Teleconsulta',
        'consultas': 'Consultas Online',
        'notificacoes': 'Notificações',
        'configuracoes': 'Configurações',
        'suporte': 'Suporte'
      };
      
      const breadcrumb = document.getElementById('breadcrumb');
      if (breadcrumb) {
        breadcrumb.textContent = pageTitles[pageName] || pageName;
      }

      // Close sidebar on mobile
      const sidebar = document.querySelector('.sidebar');
      if (sidebar && sidebar.classList.contains('active')) {
        sidebar.classList.remove('active');
      }
    }
  },

  // ==================== CALENDÁRIO ==================== //
  currentDate: new Date(),

  renderCalendar() {
    const year = this.currentDate.getFullYear();
    const month = this.currentDate.getMonth();
    
    // Update month/year display
    const monthNames = [
      'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
      'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
    ];
    
    const currentMonthEl = document.getElementById('currentMonth');
    if (currentMonthEl) {
      currentMonthEl.textContent = `${monthNames[month]} ${year}`;
    }

    // Get first day of month and number of days
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();

    const calendarDays = document.getElementById('calendarDays');
    if (!calendarDays) return;
    
    calendarDays.innerHTML = '';

    // Previous month days
    for (let i = firstDay - 1; i >= 0; i--) {
      const day = document.createElement('div');
      day.className = 'calendar-day other';
      day.textContent = daysInPrevMonth - i;
      calendarDays.appendChild(day);
    }

    // Current month days
    const today = new Date();
    for (let i = 1; i <= daysInMonth; i++) {
      const day = document.createElement('div');
      day.className = 'calendar-day';
      day.textContent = i;

      if (year === today.getFullYear() && month === today.getMonth() && i === today.getDate()) {
        day.classList.add('today');
      }

      day.addEventListener('click', () => {
        const selectedDate = new Date(year, month, i);
        this.selectCalendarDate(selectedDate);
      });

      calendarDays.appendChild(day);
    }

    // Next month days
    const totalCells = calendarDays.children.length;
    const remainingCells = 42 - totalCells;
    for (let i = 1; i <= remainingCells; i++) {
      const day = document.createElement('div');
      day.className = 'calendar-day other';
      day.textContent = i;
      calendarDays.appendChild(day);
    }
  },

  selectCalendarDate(date) {
    const monthNames = [
      'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
      'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
    ];
    
    const dayNames = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
    
    const dayName = dayNames[date.getDay()];
    const day = date.getDate();
    const month = monthNames[date.getMonth()];
    
    const selectedDate = document.getElementById('selectedDate');
    if (selectedDate) {
      selectedDate.textContent = `${day} de ${month}`;
    }

    this.renderScheduleTimeline();
  },

  // ==================== CRONOGRAMA ==================== //
  renderScheduleTimeline() {
    const scheduleTimeline = document.getElementById('scheduleTimeline');
    if (!scheduleTimeline) return;

    const timeSlots = [
      { time: '08:00', available: true },
      { time: '08:30', available: false },
      { time: '09:00', available: true },
      { time: '09:30', available: true },
      { time: '10:00', available: false },
      { time: '10:30', available: true },
      { time: '11:00', available: true },
      { time: '11:30', available: false },
      { time: '13:00', available: true },
      { time: '13:30', available: true },
      { time: '14:00', available: false },
      { time: '14:30', available: true },
      { time: '15:00', available: true },
      { time: '15:30', available: false },
      { time: '16:00', available: true },
      { time: '16:30', available: true },
      { time: '17:00', available: false },
      { time: '17:30', available: true }
    ];

    scheduleTimeline.innerHTML = '';

    timeSlots.forEach(slot => {
      const slotEl = document.createElement('div');
      slotEl.className = `appointment-item ${slot.available ? 'available' : 'occupied'}`;
      slotEl.style.opacity = slot.available ? '1' : '0.6';
      
      slotEl.innerHTML = `
        <div class="appointment-time">${slot.time}</div>
        <div class="appointment-details">
          <p class="appointment-patient">${slot.available ? 'Disponível' : 'Ocupado'}</p>
        </div>
        <div class="appointment-actions">
          <button class="btn btn-sm ${slot.available ? 'btn-primary' : 'btn-outline'}" ${!slot.available ? 'disabled' : ''}>
            ${slot.available ? 'Agendar' : 'Indisponível'}
          </button>
        </div>
      `;
      
      scheduleTimeline.appendChild(slotEl);
    });
  },

  // ==================== CHAT ==================== //
  sendMessage(input) {
    const message = input.value.trim();
    if (!message) return;

    const chatMessages = document.getElementById('chatMessages');
    const messageGroup = document.createElement('div');
    messageGroup.className = 'message-group';

    const messageEl = document.createElement('div');
    messageEl.className = 'message sent';
    
    const time = new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
    
    messageEl.innerHTML = `
      <p>${this.escapeHtml(message)}</p>
      <span class="message-time">${time}</span>
    `;

    messageGroup.appendChild(messageEl);
    chatMessages.appendChild(messageGroup);
    
    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;
    
    input.value = '';

    // Simulated response
    setTimeout(() => {
      const responseGroup = document.createElement('div');
      responseGroup.className = 'message-group';
      
      const responseEl = document.createElement('div');
      responseEl.className = 'message received';
      
      const responses = [
        'Obrigado pela mensagem!',
        'Entendi. Deixe-me verificar isso.',
        'Você está certo. Vou anotar isso.',
        'Perfeito! Continuaremos o acompanhamento.',
        'Tem mais alguma dúvida?'
      ];
      
      const randomResponse = responses[Math.floor(Math.random() * responses.length)];
      const responseTime = new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
      
      responseEl.innerHTML = `
        <p>${randomResponse}</p>
        <span class="message-time">${responseTime}</span>
      `;
      
      responseGroup.appendChild(responseEl);
      chatMessages.appendChild(responseGroup);
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }, 1000);
  },

  escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  },

  // ==================== ABAS ==================== //
  switchTab(tabName) {
    // Deactivate all tabs
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => btn.classList.remove('active'));
    tabContents.forEach(content => content.classList.remove('active'));

    // Activate selected tab
    document.querySelectorAll(`.tab-btn[data-tab="${tabName}"]`).forEach(btn => {
      btn.classList.add('active');
    });

    const selectedContent = document.getElementById(tabName);
    if (selectedContent) {
      selectedContent.classList.add('active');
    }
  },

  // ==================== GRÁFICOS ==================== //
  setupCharts() {
    this.drawWeekChart();
  },

  drawWeekChart() {
    const canvas = document.getElementById('weekChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    
    // Dados da semana
    const days = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab', 'Dom'];
    const data = [5, 8, 6, 9, 7, 4, 2];
    const padding = 40;
    const chartWidth = canvas.width - padding * 2;
    const chartHeight = canvas.height - padding * 2;
    const barWidth = chartWidth / days.length * 0.7;
    const barSpacing = chartWidth / days.length;
    const maxValue = Math.max(...data);

    // Background
    ctx.fillStyle = this.isDarkMode ? '#1f2937' : '#ffffff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Grid lines
    ctx.strokeStyle = this.isDarkMode ? '#374151' : '#e5e7eb';
    ctx.lineWidth = 1;
    
    for (let i = 0; i <= 5; i++) {
      const y = padding + (chartHeight / 5) * i;
      ctx.beginPath();
      ctx.moveTo(padding, y);
      ctx.lineTo(canvas.width - padding, y);
      ctx.stroke();
    }

    // Bars
    const gradient = ctx.createLinearGradient(0, padding, 0, padding + chartHeight);
    gradient.addColorStop(0, '#5588ff');
    gradient.addColorStop(1, '#3366ff');

    data.forEach((value, index) => {
      const x = padding + index * barSpacing + (barSpacing - barWidth) / 2;
      const barHeight = (value / maxValue) * chartHeight;
      const y = padding + chartHeight - barHeight;

      ctx.fillStyle = gradient;
      ctx.borderRadius = 4;
      ctx.fillRect(x, y, barWidth, barHeight);
    });

    // Labels
    ctx.fillStyle = this.isDarkMode ? '#9ca3af' : '#6b7280';
    ctx.textAlign = 'center';
    ctx.font = '12px ' + getComputedStyle(document.body).fontFamily;

    days.forEach((day, index) => {
      const x = padding + index * barSpacing + barSpacing / 2;
      ctx.fillText(day, x, canvas.height - padding + 20);
    });

    // Y-axis labels
    ctx.textAlign = 'right';
    for (let i = 0; i <= 5; i++) {
      const value = Math.round((maxValue / 5) * i);
      const y = padding + (chartHeight / 5) * (5 - i);
      ctx.fillText(value, padding - 10, y + 4);
    }
  }
};

// ==================== INICIALIZAÇÃO ==================== //
document.addEventListener('DOMContentLoaded', () => {
  DashboardManager.init();
  console.log("[v0] Aplicação carregada e pronta para uso");
});

// Handle window resize for responsive behavior
window.addEventListener('resize', () => {
  const sidebar = document.querySelector('.sidebar');
  if (window.innerWidth > 768) {
    sidebar.classList.remove('active');
  }
});

// Prevent context menu on production (opcional)
// document.addEventListener('contextmenu', (e) => e.preventDefault());
function carregarPacientes() {

    fetch("assets/js/backmedico/listar_pacientes.php")
    .then(res => res.json())
    .then(res => {

        console.log(res);

        const tbody = document.querySelector(".patients-table tbody");

        tbody.innerHTML = "";

        if (!res.data || res.data.length === 0) {

            tbody.innerHTML = `
                <tr>
                    <td colspan="6">
                        Nenhum paciente encontrado
                    </td>
                </tr>
            `;

            return;
        }

        res.data.forEach(item => {

            tbody.innerHTML += `
                <tr>

                    <td>
                        <img
                            src="https://i.pravatar.cc/40"
                            class="patient-avatar"
                        >
                    </td>

                    <td>${item.paciente_nome}</td>

                    <td>--</td>

                    <td>${item.telefone ?? '--'}</td>

                    <td>
                        ${item.data_hora_inicio}
                    </td>

                    <td>

                        <div class="action-buttons">

                            <button
                                class="btn btn-sm btn-outline"
                                onclick="iniciarConsulta(
                                    ${item.consulta_id},
                                    '${item.link}'
                                )"
                            >
                                <i class="fas fa-video"></i>
                            </button>

                        </div>

                    </td>

                </tr>
            `;
        });

    })
    .catch(err => {
        console.error(err);
    });
}

function iniciarConsulta(consulta_id, link) {

    const formData = new FormData();

    formData.append("consulta_id", consulta_id);
    formData.append("tipo_usuario", window.tipo_usuario);
    formData.append("nome", window.nome_usuario);

    fetch("assets/js/backmedico/iniciar_consulta.php", {
        method: "POST",
        body: formData
    })

    .then(res => res.json())
    .then(res => {

        if (res.status === "success") {

            const iframe = document.getElementById("iframeTeleconsulta");

            // usa SEMPRE o link vindo do backend (mais seguro)
            iframe.src = res.link || link;

            document.getElementById("modalTeleconsulta").style.display = "block";

            carregarPacientes();

        } else {
            alert(res.msg);
        }

    })
    .catch(err => {
        console.error(err);
        alert("Erro ao iniciar consulta");
    });
}
//«««««««««««««««««««««««««««««««««««««««««Fechar o modal de teleconsulta
function fecharTeleconsulta() {

    const iframe = document.getElementById("iframeTeleconsulta");

    iframe.src = "";

    document.getElementById("modalTeleconsulta").style.display = "none";
}
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