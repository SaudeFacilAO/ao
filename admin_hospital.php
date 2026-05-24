<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saúde Fácil - Painel Administrativo Hospitalar</title>
    <link rel="stylesheet" href="assets/css/admin_hospital.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Container Principal -->
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <!-- Logo -->
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon"><i class="fas fa-heartbeat"></i></div>
                    <div class="logo-text">
                        <h1>Saúde Fácil</h1>
                    </div>
                </div>
                <button class="menu-toggle" aria-label="Abrir menu">☰</button>
            </div>

            <!-- Navigation Menu -->
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li><a href="#" class="nav-link active" data-page="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="#" class="nav-link" data-page="relatorios"><i class="fas fa-file-alt"></i> Relatórios</a></li>
                    <li><a href="#" class="nav-link" data-page="usuarios"><i class="fas fa-users"></i> Usuários</a></li>
                    <li><a href="#" class="nav-link" data-page="especialidades"> <i class="fas fa-notes-medical"></i>Especialidades</a></li>
                    <li><a href="#" class="nav-link" data-page="escalas"><i class="fas fa-calendar-alt"></i> Gestão de Escalas Médicas</a></li>
                    <li><a href="#" class="nav-link" data-page="logs"><i class="fas fa-shield-alt"></i> Logs</a></li>
                    <li><a href="#" class="nav-link" data-page="notificacoes"><i class="fas fa-bell"></i> Notificações</a></li>
                    <li><a href="#" class="nav-link" data-page="configuracoes"><i class="fas fa-cog"></i> Configurações</a></li>
                    <li><a href="#" class="nav-link" data-page="suporte"><i class="fas fa-life-ring"></i> Suporte</a></li>
                </ul>
            </nav>

            <div class="sidebar-footer">
            <button class="btn-logout" id="logoutBtn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Sair</span>
            </button>
             </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header Bar -->
            <header class="top-header">
                <div class="header-left">
                    <h2 id="page-title">Dashboard</h2>
                </div>
                <div class="header-right">
                    <!-- Search -->
                    <div class="search-box">
                        <input type="text" id="globalSearch" placeholder="Buscar...">
                        <span class="search-icon"><i class="fas fa-search"></i></span>
                    </div>

                    <!-- Notifications -->
                    <div class="notification-bell">
                        <button class="bell-button" aria-label="Notificações">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge" id="notification-count">3</span>
                        </button>
                    </div>

                    <!-- User Profile -->
                    <div class="user-profile">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=32&h=32&fit=crop" alt="Perfil do usuário" class="user-avatar">
                        <div class="user-info">
                            <p class="user-name">Dr. João Silva</p>
                            <p class="user-role">Administrador</p>
                        </div>
                        <button class="profile-dropdown-btn" aria-label="Menu do perfil">▼</button>
                    </div>
                </div>

                <!-- Profile Dropdown Menu -->
                <div class="profile-dropdown" id="profileDropdown">
                    <a href="#">Editar Perfil</a>
                    <a href="#">Alterar Senha</a>
                    <a href="#">Preferências</a>
                    <hr>
                    <a href="#">Sair</a>
                </div>
            </header>

            <!-- Page Content -->
            <div class="content-wrapper">
                <!-- Dashboard Page -->
                <div id="dashboard" class="page-content active">
                    <!-- Dashboard Stats Cards -->
                    <section class="stats-section">
                        <h3>Visão Geral do Hospital</h3>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-notes-medical"></i></div>
                                <div class="stat-info">
                                    <p class="stat-label">Consultas Hoje</p>
                                    <p class="stat-value">24</p>
                                    <p class="stat-change positive">↑ 8% vs ontem</p>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                                <div class="stat-info">
                                    <p class="stat-label">Consultas Mês</p>
                                    <p class="stat-value">512</p>
                                    <p class="stat-change positive">↑ 12% vs mês anterior</p>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-user-injured"></i></div>
                                <div class="stat-info">
                                    <p class="stat-label">Pacientes Atendidos</p>
                                    <p class="stat-value">1,240</p>
                                    <p class="stat-change positive">↑ 5% vs período anterior</p>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-user-times"></i></div>
                                <div class="stat-info">
                                    <p class="stat-label">Taxa de Faltas</p>
                                    <p class="stat-value">3.2%</p>
                                    <p class="stat-change negative">↓ 2% vs período anterior</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Charts Section -->
                    <section class="charts-section">
                        <div class="chart-card">
                            <h4>Consultas por Dia</h4>
                            <div class="chart-placeholder">
                                <canvas id="chartConsultasPorDia"></canvas>
                            </div>
                        </div>
                        <div class="chart-card">
                            <h4>Especialidades Mais Procuradas</h4>
                            <div class="chart-placeholder">
                                <canvas id="chartEspecialidades"></canvas>
                            </div>
                        </div>
                    </section>

                    <!-- Recent Activities -->
                    <section class="activities-section">
                        <h3>Consultas Recentes</h3>
                        <div class="activities-list">
                            <div class="activity-item">
                                <div class="activity-avatar"><i class="fas fa-check-circle"></i></div>
                                <div class="activity-details">
                                    <p class="activity-title">Dr. Carlos Lima - Consulta Realizada</p>
                                    <p class="activity-time">Hoje às 14:30</p>
                                </div>
                                <span class="activity-status completed">Concluído</span>
                            </div>
                            <div class="activity-item">
                                <div class="activity-avatar"><i class="fas fa-clock"></i></div>
                                <div class="activity-details">
                                    <p class="activity-title">Dra. Maria Santos - Consulta Agendada</p>
                                    <p class="activity-time">Hoje às 15:00</p>
                                </div>
                                <span class="activity-status pending">Pendente</span>
                            </div>
                            <div class="activity-item">
                                <div class="activity-avatar"><i class="fas fa-times-circle"></i></div>
                                <div class="activity-details">
                                    <p class="activity-title">Dr. Roberto Costa - Consulta Cancelada</p>
                                    <p class="activity-time">Hoje às 16:45</p>
                                </div>
                                <span class="activity-status cancelled">Cancelado</span>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Relatórios Page -->
                <div id="relatorios" class="page-content">
                    <section class="reports-section">
                        <h3>Gerar Relatórios Gerenciais</h3>
                        
                        <div class="filters-section">
                            <h4>Filtros</h4>
                            <div class="filter-grid">
                                <div class="filter-group">
                                    <label>Data Inicial</label>
                                    <input type="date" id="filterDataInicial">
                                </div>
                                <div class="filter-group">
                                    <label>Data Final</label>
                                    <input type="date" id="filterDataFinal">
                                </div>
                                <div class="filter-group">
                                    <label>Médico</label>
                                    <select id="filterMedico">
                                        <option value="">Todos os médicos</option>
                                        <option value="carlos">Dr. Carlos Lima</option>
                                        <option value="maria">Dra. Maria Santos</option>
                                        <option value="joao">Dr. João Silva</option>
                                    </select>
                                </div>
                                <div class="filter-group">
                                    <label>Especialidade</label>
                                    <select id="filterEspecialidade">
                                        <option value="">Todas</option>
                                        <option value="cardiologia">Cardiologia</option>
                                        <option value="pediatria">Pediatria</option>
                                        <option value="ortopedia">Ortopedia</option>
                                    </select>
                                </div>
                                <div class="filter-group">
                                    <label>Tipo de Relatório</label>
                                    <select id="filterTipoRelatorio">
                                        <option value="">Selecione</option>
                                        <option value="consultas">Consultas Realizadas</option>
                                        <option value="faltas">Taxa de Faltas</option>
                                        <option value="canceladas">Consultas Canceladas</option>
                                        <option value="desempenho">Desempenho de Médicos</option>
                                        <option value="pacientes">Pacientes Atendidos</option>
                                    </select>
                                </div>
                                <div class="filter-actions">
                                    <button class="btn btn-primary" onclick="generateReport()">Gerar Relatório</button>
                                    <button class="btn btn-secondary" onclick="exportReport()">Exportar</button>
                                    <button class="btn btn-secondary" onclick="printReport()">Imprimir</button>
                                </div>
                            </div>
                        </div>

                        <div class="report-result">
                            <h4>Resultado do Relatório</h4>
                            <table class="report-table">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Médico</th>
                                        <th>Especialidade</th>
                                        <th>Paciente</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>01/03/2024</td>
                                        <td>Dr. Carlos Lima</td>
                                        <td>Cardiologia</td>
                                        <td>João Pedro</td>
                                        <td>Presencial</td>
                                        <td><span class="status-badge completed">Concluído</span></td>
                                    </tr>
                                    <tr>
                                        <td>01/03/2024</td>
                                        <td>Dra. Maria Santos</td>
                                        <td>Pediatria</td>
                                        <td>Ana Silva</td>
                                        <td>Online</td>
                                        <td><span class="status-badge completed">Concluído</span></td>
                                    </tr>
                                    <tr>
                                        <td>01/03/2024</td>
                                        <td>Dr. João Silva</td>
                                        <td>Ortopedia</td>
                                        <td>Carlos Costa</td>
                                        <td>Presencial</td>
                                        <td><span class="status-badge cancelled">Cancelado</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

                <!-- Usuários Page -->
                <div id="usuarios" class="page-content">
                    <section class="users-section">
                        <div class="section-header">
                            <button class="btn btn-primary" onclick="openUserForm()">+ Funcionário</button>
                        </div>

                        <table class="users-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Gênero</th>
                                <th>BI</th>
                                <th>Função</th>
                                <th>CRM / ID</th>
                                <th>Especialidade</th>
                                <th>Ativo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- JS vai preencher -->
                        </tbody>
                        </table>

                        <!-- User Form Modal -->
                        <div id="userFormModal" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeUserForm()">&times;</span>
                                <h4>Adicionar/Editar Usuário</h4>

                                <form id="formUsuario" class="user-form">

                                    <!-- NOME -->
                                    <div class="form-group">
                                        <label>Nome</label>
                                        <input type="text" id="nome" required>
                                    </div>

                                    <!-- EMAIL -->
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" id="email" required>
                                    </div>

                                    <!-- TELEFONE -->
                                    <div class="form-group">
                                        <label>Telefone</label>
                                        <input type="tel" id="telefone">
                                    </div>

                                    <!-- GÊNERO -->
                                    <div class="form-group">
                                        <label>Gênero</label>
                                        <select id="genero">
                                            <option value="">Selecione</option>
                                            <option value="masculino">Masculino</option>
                                            <option value="feminino">Feminino</option>
                                        </select>
                                    </div>

                                    <!-- BI (NOVO ✔) -->
                                    <div class="form-group">
                                        <label>BI</label>
                                        <input type="text" id="bi" placeholder="Número do BI">
                                    </div>

                                    <!-- FUNÇÃO -->
                                    <div class="form-group">
                                        <label>Função</label>
                                        <select id="userRole" required>
                                            <option value="">Selecione</option>
                                            <option value="admin">Administrador</option>
                                            <option value="medico">Médico</option>
                                            <option value="secretario">Secretário</option>
                                        </select>
                                    </div>

                                    <!-- HOSPITAL -->
                                    <div class="form-group">
                                        <label>Hospital</label>
                                        <select id="hospitalId" required>
                                            <option value="">Selecione o hospital</option>
                                        </select>
                                    </div>

                                    <!-- ================= MÉDICO ================= -->
                                    
                                    <div class="form-group medico-only" style="display:none;">
                                        <label>Especialidade</label>
                                        <select id="especialidadeId"></select>
                                    </div>

                                    <div class="form-group medico-only" style="display:none;">
                                        <label>Teleconsulta</label>
                                        <select id="teleconsulta">
                                            <option value="1">Sim</option>
                                            <option value="0">Não</option>
                                        </select>
                                    </div>


                                    <!-- SENHA -->
                                    <div class="form-group">
                                        <label>Senha Inicial</label>
                                        <input type="password" id="senha" required>
                                    </div>

                                    <!-- BOTÕES -->
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                        <button type="button" class="btn btn-secondary" onclick="closeUserForm()">Cancelar</button>
                                    </div>

                                </form>
                            </div>
                            </div>
                        </div>
                    </section>
                </div>

                    <!-- ================= ESPECIALIDADES ================= -->
           <div id="especialidades" class="page-content">
            <!-- FORMULÁRIO -->
             <div class="section-card">
                <h3>Cadastrar Especialidade</h3>
                <form id="formEspecialidade">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nome da Especialidade</label>
                            <input type="text" id="espNome" placeholder="Ex: Cardiologia" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea id="espDescricao" placeholder="Descrição opcional"></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Cadastrar
                    </button>
                </form>
            </div>
            
            <!-- LISTAGEM -->
            <div class="section-card">
                <h3>Especialidades Cadastradas</h3>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    
                    <tbody id="listaEspecialidades">
                        <!-- JS vai preencher -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <div id="escalas" class="page-content">
            <h3>Definir Escala Médica</h3>
            <!-- LINHA MÉDICO + ESPECIALIDADE -->
            
            <div class="form-row">
                <!-- MÉDICO -->
                <div class="form-group form-col">
                    <label>Médico</label>
                    <select id="medicoSelect" onchange="carregarEspecialidadeMedico()">
                        <option value="">Selecione o médico</option>
                    </select>
                </div>
                
                <!-- ESPECIALIDADE -->
                 
                <div class="form-group form-col">
                    <label>Especialidade</label>
                    <input type="text" id="especialidadeMedico" disabled>
                </div>
            </div>
            
            <!-- DIAS DE TRABALHO -->
             
            <div class="form-group">
                <label>Dias de Trabalho</label>
                
                <div class="dias-grid">
                    <label><input type="checkbox" value="segunda"> Segunda</label>
                    <label><input type="checkbox" value="terca"> Terça</label>
                    <label><input type="checkbox" value="quarta"> Quarta</label>
                    <label><input type="checkbox" value="quinta"> Quinta</label>
                    <label><input type="checkbox" value="sexta"> Sexta</label>
                    <label><input type="checkbox" value="sabado"> Sábado</label>
                </div>
                
                <label class="todos-dias">
                    <input type="checkbox" id="todosDias"> Todos os dias
                </label>
            </div>
            
            <!-- TURNO -->
             
            <div class="form-group">
                <label>Turno</label>
                <select id="turno" onchange="mostrarHorarioTurno()">
                    <option value="">Selecione</option>
                    <option value="manha">Manhã (08:00 - 12:00)</option>
                    <option value="tarde">Tarde (13:00 - 15:00)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Intervalo por Consulta (minutos)</label>
                <input type="text" id="intervaloConsulta">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Início do intervalo (Almoço)</label>
                    <input type="time" id="intervaloInicio">
                </div>
                
                <div class="form-group">
                    <label>Fim do intervalo (Almoço)</label>
                    <input type="time" id="intervaloFim">
                </div>
            </div>
            
            <!-- HORÁRIO GERADO -->
             <div class="form-group">
                <label>Horário Gerado</label>
                <textarea id="previewHorario" rows="5" disabled></textarea>
            </div>
            
            <!-- BOTÕES -->
             
            <div class="form-actions">
                <button type="button" class="btn btn-primary" onclick="gerarEscala()">Salvar Escala</button>
                <button type="button" class="btn btn-secondary" onclick="cancelarEscala()">Cancelar</button>
            </div>
            
            <!-- ================= LISTAGEM DE ESCALAS ================= -->
             
            <div class="escala-list-section">
                <h3>Escalas Criadas</h3>
                <!-- RESULTADO DINÂMICO -->
                 
                <div id="listaEscalas">

                </div>
            </div>
        </div>
        
        <!-- Logs de Auditoria -->
         
        <div id="logs" class="page-content">
                    <section class="logs-section">
                        <h3>Logs de Auditoria</h3>
                        
                        <div class="logs-filters">
                            <div class="filter-group">
                                <label>Data</label>
                                <input type="date" id="logDate">
                            </div>
                            <div class="filter-group">
                                <label>Usuário</label>
                                <input type="text" placeholder="Nome do usuário" id="logUser">
                            </div>
                            <div class="filter-group">
                                <label>Tipo de Ação</label>
                                <select id="logAction">
                                    <option value="">Todas</option>
                                    <option value="login">Login</option>
                                    <option value="criacao">Criação de Usuário</option>
                                    <option value="edicao">Alteração de Agenda</option>
                                    <option value="cancelamento">Cancelamento</option>
                                </select>
                            </div>
                            <button class="btn btn-primary" onclick="filterLogs()">Filtrar</button>
                        </div>

                        <table class="logs-table">
                            <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>Ação</th>
                                    <th>Data</th>
                                    <th>Hora</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Dr. João Silva</td>
                                    <td>Login no sistema</td>
                                    <td>01/03/2024</td>
                                    <td>08:30</td>
                                    <td>192.168.1.100</td>
                                </tr>
                                <tr>
                                    <td>Admin</td>
                                    <td>Criação de usuário</td>
                                    <td>01/03/2024</td>
                                    <td>09:15</td>
                                    <td>192.168.1.50</td>
                                </tr>
                                <tr>
                                    <td>Dra. Maria</td>
                                    <td>Alteração de agenda</td>
                                    <td>01/03/2024</td>
                                    <td>10:45</td>
                                    <td>192.168.1.75</td>
                                </tr>
                                <tr>
                                    <td>Secretária Ana</td>
                                    <td>Cancelamento de consulta</td>
                                    <td>01/03/2024</td>
                                    <td>14:20</td>
                                    <td>192.168.1.80</td>
                                </tr>
                            </tbody>
                        </table>
                    </section>
                </div>

                <!-- Notificações -->
                <div id="notificacoes" class="page-content">
                    <section class="notifications-section">
                        <h3>Central de Notificações</h3>
                        
                        <div class="notification-controls">
                            <button class="btn btn-secondary" onclick="markAllAsRead()">Marcar todos como lido</button>
                            <button class="btn btn-secondary" onclick="clearNotifications()">Limpar</button>
                        </div>

                        <div class="notifications-list">
                            <div class="notification-item unread">
                                <div class="notification-icon">✓</div>
                                <div class="notification-content">
                                    <p class="notification-title">Nova consulta agendada</p>
                                    <p class="notification-message">Dr. Carlos Lima agendou uma nova consulta para amanhã às 14:00</p>
                                    <p class="notification-time">Há 5 minutos</p>
                                </div>
                            </div>
                            <div class="notification-item unread">
                                <div class="notification-icon">✕</div>
                                <div class="notification-content">
                                    <p class="notification-title">Consulta cancelada</p>
                                    <p class="notification-message">O paciente João Pedro cancelou sua consulta de amanhã</p>
                                    <p class="notification-time">Há 1 hora</p>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notification-icon">ℹ</div>
                                <div class="notification-content">
                                    <p class="notification-title">Mensagem do sistema</p>
                                    <p class="notification-message">Sistema em manutenção preventiva agendada para segunda-feira à noite</p>
                                    <p class="notification-time">Há 2 horas</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Configurações -->
                <div id="configuracoes" class="page-content">
                    <section class="settings-section">
                        <h3>Configurações</h3>
                        
                        <div class="settings-group">
                            <h4>Perfil</h4>
                            <div class="setting-item">
                                <label>Nome</label>
                                <input type="text" value="Dr. João Silva">
                            </div>
                            <div class="setting-item">
                                <label>Email</label>
                                <input type="email" value="joao@saudefacil.com">
                            </div>
                            <button class="btn btn-primary" onclick="saveProfile()">Salvar Perfil</button>
                        </div>

                        <div class="settings-group">
                            <h4>Segurança</h4>
                            <div class="setting-item">
                                <label>Alterar Senha</label>
                                <input type="password" placeholder="Senha atual">
                                <input type="password" placeholder="Nova senha">
                                <input type="password" placeholder="Confirmar senha">
                            </div>
                            <button class="btn btn-primary" onclick="changePassword()">Alterar Senha</button>
                        </div>

                        <div class="settings-group">
                            <h4>Tema</h4>
                            <div class="theme-options">
                                <label class="theme-option">
                                    <input type="radio" name="theme" value="light" checked> Claro
                                </label>
                                <label class="theme-option">
                                    <input type="radio" name="theme" value="dark"> Escuro
                                </label>
                            </div>
                            <button class="btn btn-primary" onclick="applyTheme()">Aplicar Tema</button>
                        </div>

                        <div class="settings-group">
                            <h4>Notificações</h4>
                            <label class="checkbox-label">
                                <input type="checkbox" checked> Notificações por email
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" checked> Notificações no navegador
                            </label>
                            <button class="btn btn-primary" onclick="saveNotificationSettings()">Salvar</button>
                        </div>
                    </section>
                </div>

                <!-- Suporte -->
                <div id="suporte" class="page-content">
                    <section class="support-section">
                        <h3>Suporte / Ajuda</h3>
                        
                        <div class="support-tabs">
                            <button class="tab-btn active" onclick="switchTab('faq')">Perguntas Frequentes</button>
                            <button class="tab-btn" onclick="switchTab('contato')">Contato</button>
                            <button class="tab-btn" onclick="switchTab('reportar')">Reportar Problema</button>
                        </div>

                        <div id="faq" class="tab-content active">
                            <h4>Perguntas Frequentes</h4>
                            <div class="faq-item">
                                <h5>Como adicionar um novo médico?</h5>
                                <p>Acesse a seção Gestão de Usuários, clique em "Adicionar Usuário" e selecione a função "Médico". Preencha os dados solicitados.</p>
                            </div>
                            <div class="faq-item">
                                <h5>Como gerar um relatório?</h5>
                                <p>Acesse a seção Relatórios, configure os filtros desejados e clique em "Gerar Relatório". Você pode exportar ou imprimir o resultado.</p>
                            </div>
                            <div class="faq-item">
                                <h5>Como funciona o modo escuro?</h5>
                                <p>Acesse Configurações e escolha "Modo Escuro". A alteração será aplicada imediatamente em toda a plataforma.</p>
                            </div>
                        </div>

                        <div id="contato" class="tab-content">
                            <h4>Entre em Contato</h4>
                            <form class="contact-form">
                                <div class="form-group">
                                    <label>Nome</label>
                                    <input type="text" placeholder="Seu nome">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" placeholder="seu@email.com">
                                </div>
                                <div class="form-group">
                                    <label>Assunto</label>
                                    <input type="text" placeholder="Assunto da mensagem">
                                </div>
                                <div class="form-group">
                                    <label>Mensagem</label>
                                    <textarea placeholder="Descreva sua dúvida ou sugestão" rows="6"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
                            </form>
                        </div>

                        <div id="reportar" class="tab-content">
                            <h4>Reportar Problema Técnico</h4>
                            <form class="issue-form">
                                <div class="form-group">
                                    <label>Descrição do Problema</label>
                                    <textarea placeholder="Descreva o problema em detalhes" rows="6"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Passos para Reproduzir</label>
                                    <textarea placeholder="Quais passos levam ao problema?" rows="4"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Navegador</label>
                                    <select>
                                        <option value="">Selecione</option>
                                        <option value="chrome">Google Chrome</option>
                                        <option value="firefox">Firefox</option>
                                        <option value="safari">Safari</option>
                                        <option value="edge">Microsoft Edge</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Reportar</button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <script src="assets/js/admin_hospital.js"></script>
</body>
</html>
