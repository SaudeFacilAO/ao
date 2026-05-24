<?php
session_start();

// não logado
if (!isset($_SESSION['usuario_id'])) {

    http_response_code(404);

    exit("
    <h1>404 - Página não encontrada</h1>
    <p>O recurso solicitado não existe.</p>
    ");
}

// impedir acesso de outros tipos
if ($_SESSION['tipo_usuario'] !== 'secretario') {

    http_response_code(404);

    exit("
    <h1>404 - Página não encontrada</h1>
    <p>O recurso solicitado não existe.</p>
    ");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saúde Fácil - Painel Secretário</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/sec.css">
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-heartbeat"></i>
                <span>Saúde Fácil</span>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <a href="#" class="nav-item active" data-page="dashboard">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="nav-item" data-page="solicitacoes">
                <i class="fas fa-clipboard-list"></i>
                <span>Solicitações</span>
            </a>
            <a href="#" class="nav-item" data-page="relatorios">
                <i class="fas fa-file-chart-bar"></i>
                <span>Relatórios</span>
            </a>
            <a href="#" class="nav-item" data-page="medicos">
                <i class="fas fa-user-md"></i>
                <span>Médicos</span>
            </a>
            <a href="#" class="nav-item" data-page="pacientes">
                <i class="fas fa-users"></i>
                <span>Pacientes</span>
            </a>
            <a href="#" class="nav-item" data-page="perfil">
                <i class="fas fa-user-circle"></i>
                <span>Perfil</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <button class="btn-logout" id="logoutBtn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Sair</span>
            </button>
        </div>
    </aside>

    <!-- OVERLAY PARA MOBILE -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- TOP HEADER -->
        <header class="top-header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 id="pageTitle">Dashboard</h1>
            </div>

            <div class="header-center">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Buscar pacientes, médicos, solicitações...">
                    <div class="search-results" id="searchResults"></div>
                </div>
            </div>

            <div class="header-right">
                <button class="dark-mode-toggle" id="darkModeToggle" title="Modo escuro">
                    <i class="fas fa-moon"></i>
                </button>
                <div class="user-info">
                    <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="Perfil" class="user-avatar">
                    <div>
                        <p class="user-name">Ana Oliveira</p>
                        <p class="user-role">Secretária</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- PAGES CONTAINER -->
        <div class="pages-container">
            <!-- DASHBOARD -->
            <section id="dashboard" class="page active">
                <div class="page-header">
                    <div>
                        <h2>Bem-vindo de volta, Ana!</h2>
                        <p>Aqui está um resumo das suas atividades</p>
                    </div>
                    <div class="date-range">
                        <span id="currentDate"></span>
                    </div>
                </div>

                <!-- KPI CARDS -->
                <div class="kpi-grid">
                    <div class="kpi-card">
                        <div class="kpi-header">
                            <h3>Solicitações Pendentes</h3>
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="kpi-value">12</div>
                        <div class="kpi-footer">
                            <span class="kpi-change negative">↑ 2 hoje</span>
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-header">
                            <h3>Atendidos Hoje</h3>
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="kpi-value">28</div>
                        <div class="kpi-footer">
                            <span class="kpi-change positive">↑ 8 vs ontem</span>
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-header">
                            <h3>Pacientes Ativos</h3>
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="kpi-value">145</div>
                        <div class="kpi-footer">
                            <span class="kpi-change positive">↑ 5 novos</span>
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-header">
                            <h3>Taxa de Cancelamento</h3>
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="kpi-value">3.2%</div>
                        <div class="kpi-footer">
                            <span class="kpi-change positive">↓ 0.5%</span>
                        </div>
                    </div>
                </div>

                
                <!-- RECENT ACTIVITY -->
                <div class="card activity-card">
                    <div class="card-header">
                        <h3>Atividades Recentes</h3>
                        <a href="#" class="link-text">Ver todas</a>
                    </div>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <p class="timeline-title">Novo paciente cadastrado</p>
                                <p class="timeline-desc">Maria Silva - CPF: 123.456.789-00</p>
                                <p class="timeline-time">Há 15 minutos</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <p class="timeline-title">Solicitação atendida</p>
                                <p class="timeline-desc">João Santos com Dr. Carlos Mendes</p>
                                <p class="timeline-time">Há 2 horas</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <p class="timeline-title">Médico adicionado ao sistema</p>
                                <p class="timeline-desc">Dra. Paula Rodrigues - Cardiologista</p>
                                <p class="timeline-time">Há 1 dia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SOLICITAÇÕES -->
<section id="solicitacoes" class="page">

    <div class="page-header">

        <h2>Solicitações de Atendimento</h2>

        <div class="filters">

            <select id="filterStatus"
                class="filter-select">

                <option value="">
                    Todos os Status
                </option>

                <option value="pendente">
                    Pendentes
                </option>

                <option value="confirmada">
                    Confirmadas
                </option>

                <option value="cancelada">
                    Canceladas
                </option>

            </select>

        </div>

    </div>

    <div class="card">

        <div class="table-responsive">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>Paciente</th>

                        <th>Especialidade</th>

                        <th>Data Desejada</th>

                        <th>Status</th>

                        <th>Ações</th>

                    </tr>

                </thead>

                <tbody id="solicitacoesTable">

                    <!-- PREENCHIDO VIA JS -->

                </tbody>

            </table>

        </div>

    </div>

</section>
            <!-- RELATÓRIOS -->
            <section id="relatorios" class="page">
                <div class="page-header">
                    <h2>Relatórios e Análises</h2>
                    <div class="filters">
                        <select id="filterPeriodo" class="filter-select">
                            <option value="mes">Este Mês</option>
                            <option value="trimestre">Este Trimestre</option>
                            <option value="ano">Este Ano</option>
                        </select>
                    </div>
                </div>

                <!-- REPORT CARDS -->
                <div class="reports-grid">
                    <div class="card report-card">
                        <div class="card-header">
                            <h3>Relatório de Atendimentos</h3>
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="report-stats">
                            <div class="stat">
                                <span class="stat-label">Total</span>
                                <span class="stat-value">284</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Médias por Dia</span>
                                <span class="stat-value">12.7</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Cancelados</span>
                                <span class="stat-value">9</span>
                            </div>
                        </div>
                        <button class="btn-secondary">Ver Detalhes</button>
                    </div>

                    <div class="card report-card">
                        <div class="card-header">
                            <h3>Satisfação do Paciente</h3>
                            <i class="fas fa-smile"></i>
                        </div>
                        <div class="report-stats">
                            <div class="stat">
                                <span class="stat-label">Média</span>
                                <span class="stat-value">4.7/5</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Avaliações</span>
                                <span class="stat-value">156</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Excelente</span>
                                <span class="stat-value">89%</span>
                            </div>
                        </div>
                        <button class="btn-secondary">Ver Detalhes</button>
                    </div>

                    <div class="card report-card">
                        <div class="card-header">
                            <h3>Desempenho Médicos</h3>
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="report-stats">
                            <div class="stat">
                                <span class="stat-label">Média Atendimentos</span>
                                <span class="stat-value">47</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Melhor</span>
                                <span class="stat-value">Dr. Rafael</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Crescimento</span>
                                <span class="stat-value">+15%</span>
                            </div>
                        </div>
                        <button class="btn-secondary">Ver Detalhes</button>
                    </div>

                    <div class="card report-card">
                        <div class="card-header">
                            <h3>Análise Financeira</h3>
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="report-stats">
                            <div class="stat">
                                <span class="stat-label">Receita</span>
                                <span class="stat-value">R$ 28.5k</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Ticket Médio</span>
                                <span class="stat-value">R$ 180</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Pendente</span>
                                <span class="stat-value">R$ 3.2k</span>
                            </div>
                        </div>
                        <button class="btn-secondary">Ver Detalhes</button>
                    </div>
                </div>

                <!-- DETAILED REPORTS -->
                <div class="card">
                    <div class="card-header">
                        <h3>Atendimentos Detalhados</h3>
                        <button class="btn-icon"><i class="fas fa-download"></i></button>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Paciente</th>
                                    <th>Médico</th>
                                    <th>Tipo</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="relatoriosTable">
                                <!-- Preenchido por JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- MÉDICOS -->
            <section id="medicos" class="page">
                <div class="page-header">
                    <h2>Equipe Médica</h2>
                    
                </div>

                <div class="medicos-grid" id="medicosGrid">
                    <!-- Preenchido por JS -->
                </div>
            </section>

            <!-- PACIENTES -->
            <section id="pacientes" class="page">
                <div class="page-header">
                    <h2>Pacientes Cadastrados</h2>
                    
                </div>

                <div class="card">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>Telefone</th>
                                    <th>Email</th>
                                    <th>Data Cadastro</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="pacientesTable">
                                <!-- Preenchido por JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- PERFIL -->
            <section id="perfil" class="page">
                <div class="page-header">
                    <h2>Meu Perfil</h2>
                </div>

                <div class="perfil-container">
                    <div class="card perfil-card">
                        <div class="perfil-header">
                            <img src="https://randomuser.me/api/portraits/women/50.jpg" alt="Perfil" class="perfil-avatar">
                            <div class="perfil-info">
                                <h2>Ana Oliveira</h2>
                                <p>Secretária Executiva</p>
                                <p class="perfil-department">Departamento de Atendimento</p>
                            </div>
                            <button class="btn-primary" id="editPerfilBtn">
                                <i class="fas fa-edit"></i> Editar Perfil
                            </button>
                        </div>

                        <div class="perfil-details">
                            <div class="detail-group">
                                <label>Email</label>
                                <p id="perfilEmail">ana.oliveira@saudefacil.com</p>
                            </div>
                            <div class="detail-group">
                                <label>Telefone</label>
                                <p id="perfilTelefone">(11) 98765-4321</p>
                            </div>
                            <div class="detail-group">
                                <label>Departamento</label>
                                <p id="perfilDepartamento">Atendimento</p>
                            </div>
                            <div class="detail-group">
                                <label>Data de Admissão</label>
                                <p id="perfilAdmissao">15/03/2022</p>
                            </div>
                            <div class="detail-group">
                                <label>Cargo</label>
                                <p id="perfilCargo">Secretária Executiva</p>
                            </div>
                            <div class="detail-group">
                                <label>Ativo desde</label>
                                <p id="perfilAtivo">Sim - 2 anos</p>
                            </div>
                        </div>
                    </div>

                    <div class="card perfil-settings">
                        <h3>Configurações de Segurança</h3>
                        <div class="settings-group">
                            <label>Alterar Senha</label>
                            <button class="btn-secondary" id="changePasswordBtn">
                                <i class="fas fa-lock"></i> Alterar Senha
                            </button>
                        </div>
                        <div class="settings-group">
                            <label>Duas Autenticação</label>
                            <button class="btn-secondary" id="twoFactorBtn">
                                <i class="fas fa-shield-alt"></i> Ativar 2FA
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- MODAL ATENDIMENTO -->
<div class="modal" id="modalAgendamento">

    <div class="modal-content">

        <!-- TOPO -->
        <div class="modal-header">

            <h2>Confirmar Atendimento</h2>

            <button class="modal-close"
                onclick="fecharModalAgendamento()">

                <i class="fas fa-times"></i>

            </button>

        </div>

        <!-- FORM -->
        <form id="formAgendamento" class="modal-form">

            <!-- ID DA SOLICITAÇÃO -->
            <input type="hidden" id="agendamentoSolicitacaoId">

            <!-- PACIENTE -->
            <div class="form-group">

                <label>Paciente</label>

                <input type="text"
                    id="agendamentoPaciente"
                    readonly>

            </div>

            <!-- ESPECIALIDADE -->
            <div class="form-group">

                <label>Especialidade</label>

                <input type="text"
                    id="agendamentoEspecialidade"
                    readonly>

            </div>

            <!-- MÉDICO -->
            <div class="form-group">

                <label>Médico *</label>

                <select id="agendamentoMedico" required>

                    <option value="">
                        Selecione...
                    </option>

                </select>

            </div>

            <!-- DATA -->
            <div class="form-group">

                <label>Data *</label>

                <input type="date"
                    id="agendamentoData"
                    required>

            </div>

            <!-- HORÁRIO -->
            <div class="form-group">

                <label>Horário *</label>

                <select id="agendamentoHorario" required>

                    <option value="">
                        Selecione...
                    </option>

                </select>

            </div>

            

            <!-- BOTÕES -->
            <div class="modal-footer">

                <button type="button"
                    class="btn-secondary"
                    onclick="fecharModalAgendamento()">

                    Cancelar

                </button>

                <button type="submit"
                    class="btn-primary">

                    Confirmar Atendimento

                </button>

            </div>

        </form>

    </div>

</div>

    <!-- MODAL EDITAR PERFIL -->
    <div class="modal" id="modalEditPerfil">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Editar Perfil</h2>
                <button class="modal-close" id="closeEditPerfil">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="formEditPerfil" class="modal-form">
                <div class="form-group">
                    <label>Nome Completo *</label>
                    <input type="text" id="editNome" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" id="editEmail" required>
                </div>
                <div class="form-group">
                    <label>Telefone</label>
                    <input type="tel" id="editTelefone">
                </div>
                <div class="form-group">
                    <label>Departamento</label>
                    <input type="text" id="editDepartamento">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="cancelEditPerfil">Cancelar</button>
                    <button type="submit" class="btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CONFIRMAÇÃO -->
    <div class="modal" id="modalConfirm">
        <div class="modal-content modal-small">
            <div class="modal-header">
                <h2 id="confirmTitle">Confirmar</h2>
                <button class="modal-close" id="closeConfirm">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p id="confirmMessage" style="padding: 20px; text-align: center;"></p>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="cancelConfirm">Cancelar</button>
                <button type="button" class="btn-primary" id="confirmAction">Confirmar</button>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div class="toast" id="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage"></span>
    </div>

    
    <script src="assets/js/sec.js"></script>
</body>
</html>
