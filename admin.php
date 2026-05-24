<?php
session_start();

require_once __DIR__ . "/phpconexao/nocache.php";
require_once __DIR__ . "/phpconexao/conexao.php";

// segurança de login
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(404);
    exit("404 - Página não encontrada");
}

// bloqueio por tipo
if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403);
    exit("Acesso negado");
}

$stmt = $pdo->prepare("SELECT * FROM usuarios 
                       WHERE nome = :nome AND tipo_usuario like 'admin'");

$stmt->execute(['nome' => $_SESSION['nome']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Saúde Fácil - Sistema de Gestão Hospitalar">
    <meta name="theme-color" content="#2563eb">
    <title>Saúde Fácil - Painel Administrativo</title>
    <script>
        window.tipo_usuario = "<?= $_SESSION['tipo_usuario'] ?>";
        window.nome_usuario = "<?= $_SESSION['nome'] ?>";
    </script>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" role="navigation" aria-label="Menu de navegação">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon"><i class="fas fa-stethoscope" aria-hidden="true"></i></div>
                    <div class="logo-text">
                        <h1>Saúde Fácil</h1>
                    </div>
                </div>
                <button class="menu-toggle" aria-label="Alternar menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li><a href="#" class="nav-link active" data-page="dashboard" title="Dashboard"><i class="fas fa-chart-line"></i><span>Dashboard</span></a></li>
                    <li><a href="#" class="nav-link" data-page="relatorios" title="Relatórios"><i class="fas fa-file-pdf"></i><span>Relatórios</span></a></li>
                    <li><a href="#" class="nav-link" data-page="usuarios" title="Usuários"><i class="fas fa-user-md"></i><span>Usuários</span></a></li>
                    <li><a href="#" class="nav-link" data-page="especialidades" title="Especialidades"><i class="fas fa-heartbeat"></i><span>Especialidades</span></a></li>
                    <li><a href="#" class="nav-link" data-page="escalas" title="Escalas"><i class="fas fa-calendar-check"></i><span>Escalas</span></a></li>
                    <li><a href="#" class="nav-link" data-page="logs" title="Logs"><i class="fas fa-history"></i><span>Logs</span></a></li>
                    <li><a href="#" class="nav-link" data-page="notificacoes" title="Notificações"><i class="fas fa-bell"></i><span>Notificações</span></a></li>
                    <li><a href="#" class="nav-link" data-page="configuracoes" title="Configurações"><i class="fas fa-sliders-h"></i><span>Configurações</span></a></li>
                    <li><a href="#" class="nav-link" data-page="suporte" title="Suporte"><i class="fas fa-headset"></i><span>Suporte</span></a></li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <button class="theme-toggle" id="themeToggle" aria-label="Alternar tema escuro">
                    <i class="fas fa-moon"></i>
                </button>
                <button class="btn-logout" id="logoutBtn" aria-label="Sair">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Sair</span>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-left">
                    <h2 id="page-title">Dashboard</h2>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <input type="text" id="globalSearch" placeholder="Buscar..." aria-label="Pesquisar">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </div>

                    <div class="header-actions">
                        <button class="bell-button" aria-label="Notificações" id="notificationBtn">
                            <i class="fas fa-bell"></i>
                            <span class="badge" id="notification-count">3</span>
                        </button>

                        <div class="user-profile">
                            <img src="https://ui-avatars.com/api/?name=Dr+Joao+Silva&background=2563eb&color=fff" alt="Perfil do usuário" class="user-avatar">
                            <div class="user-info">
                                <p class="user-name"><?= $_SESSION['nome'] ?></p>
                                <p class="user-role">Administrador</p>
                            </div>
                            <button class="profile-dropdown-btn" aria-label="Menu do perfil">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="profile-dropdown" id="profileDropdown">
                                <a href="#"><i class="fas fa-user"></i> Editar Perfil</a>
                                <a href="#"><i class="fas fa-key"></i> Alterar Senha</a>
                                <a href="#"><i class="fas fa-cog"></i> Preferências</a>
                                <hr>
                                <a href="#" class="logout-link"><i class="fas fa-sign-out-alt"></i> Sair</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <!-- Dashboard Page -->
                <div id="dashboard" class="page-content active">
                    <section class="stats-section">
                        <h3>Visão Geral do Hospital</h3>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon blue"><i class="fas fa-stethoscope"></i></div>
                                <div class="stat-info">
                                    <p class="stat-label">Consultas Hoje</p>
                                    <?php $stmtConsultaHoje = $pdo->prepare('SELECT COUNT(*) as quant from consultas') ?>
                                    <?php $num_hoje = $stmtConsultaHoje->execute() ?>
                                    <p class="stat-value"><?= $num_hoje ?></p>
                                    <p class="stat-change positive"><i class="fas fa-arrow-up"></i> 8% vs ontem</p>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon cyan"><i class="fas fa-calendar-alt"></i></div>
                                <div class="stat-info">
                                    <?php $stmtConsultaMes = $pdo->prepare("SELECT COUNT(*) as quant from consultas WHERE DATE(data_hora_inicio) LIMIT 1"); ?>
                                    <?php $num_mes = $stmtConsultaMes->execute() ?>
                                    <p class="stat-label">Consultas Mês</p>
                                    <p class="stat-value"><?= $num_mes ?></p>
                                    <p class="stat-change positive"><i class="fas fa-arrow-up"></i> 12% vs mês anterior</p>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon green"><i class="fas fa-user-injured"></i></div>
                                <div class="stat-info">
                                    <?php $stmtPaciente = $pdo->prepare("SELECT COUNT(*) as quant from pacientes LIMIT 1"); ?>
                                    <?php $num_paciente = $stmtPaciente->execute() ?>
                                    <p class="stat-label">Pacientes Atendidos</p>
                                    <p class="stat-value"><?= $num_paciente ?></p>
                                    <p class="stat-change positive"><i class="fas fa-arrow-up"></i> 5% vs período anterior</p>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon orange"><i class="fas fa-exclamation-circle"></i></div>
                                <div class="stat-info">
                                    <?php $stmtFalta = $pdo->prepare('SELECT COUNT(*) as quant from consultas WHERE estado like "ausente" LIMIT 1'); ?>
                                    <?php $PerceFaltas = $stmtPaciente->execute() ?>
                                    <p class="stat-label">Taxa de Faltas</p>
                                    <p class="stat-value">3.2%</p>
                                    <p class="stat-change negative"><i class="fas fa-arrow-down"></i> 2% vs período anterior</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="charts-section">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h4>Consultas por Dia da Semana</h4>
                                <div class="chart-controls">
                                    <button class="chart-btn" aria-label="Última semana"><i class="fas fa-chevron-left"></i></button>
                                    <button class="chart-btn" aria-label="Próxima semana"><i class="fas fa-chevron-right"></i></button>
                                </div>
                            </div>
                            <canvas id="chartConsultasPorDia"></canvas>
                        </div>
                        <div class="chart-card">
                            <h4>Especialidades Mais Procuradas</h4>
                            <canvas id="chartEspecialidades"></canvas>
                        </div>
                    </section>


                </div>

                <!-- Reports Page -->
                <div id="relatorios" class="page-content">
                    <section class="reports-section">
                        <h3>Gerar Relatórios Gerenciais</h3>

                        <div class="card filters-card">
                            <div class="card-header">
                                <h4><i class="fas fa-filter"></i> Filtros</h4>
                            </div>
                            <div class="filter-grid">
                                <div class="form-group">
                                    <label>Data Inicial</label>
                                    <input type="date" id="filterDataInicial">
                                </div>
                                <div class="form-group">
                                    <label>Data Final</label>
                                    <input type="date" id="filterDataFinal">
                                </div>
                                <div class="form-group">
                                    <label>Médico</label>
                                    <select id="filterMedico">
                                        <option value="">Todos os médicos</option>
                                        <option value="carlos">Dr. Carlos Lima</option>
                                        <option value="maria">Dra. Maria Santos</option>
                                        <option value="joao">Dr. João Silva</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Especialidade</label>
                                    <select id="filterEspecialidade">
                                        <option value="">Todas</option>
                                        <option value="cardiologia">Cardiologia</option>
                                        <option value="pediatria">Pediatria</option>
                                        <option value="ortopedia">Ortopedia</option>
                                    </select>
                                </div>
                                <div class="form-group full-width">
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
                                    <button class="btn btn-primary" onclick="generateReport()"><i class="fas fa-file-csv"></i> Gerar</button>
                                    <button class="btn btn-outline" onclick="exportReport()"><i class="fas fa-download"></i> Exportar</button>
                                    <button class="btn btn-outline" onclick="printReport()"><i class="fas fa-print"></i> Imprimir</button>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fas fa-list"></i> Resultado do Relatório</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Médico</th>
                                            <th>Especialidade</th>
                                            <th>Paciente</th>
                                            <th>Tipo</th>
                                            <th>Status</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportTableBody">
                                        <tr>
                                            <td>01/03/2024</td>
                                            <td>Dr. Carlos Lima</td>
                                            <td>Cardiologia</td>
                                            <td>João Pedro</td>
                                            <td><span class="badge-pill presencial">Presencial</span></td>
                                            <td><span class="badge-status completed"><i class="fas fa-check-circle"></i> Concluído</span></td>
                                            <td>
                                                <button class="btn-action view-report" title="Visualizar relatório">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>01/03/2024</td>
                                            <td>Dra. Maria Santos</td>
                                            <td>Pediatria</td>
                                            <td>Ana Silva</td>
                                            <td><span class="badge-pill online">Online</span></td>
                                            <td><span class="badge-status completed"><i class="fas fa-check-circle"></i> Concluído</span></td>
                                            <td>
                                                <button class="btn-action view-report" title="Visualizar relatório">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>01/03/2024</td>
                                            <td>Dr. João Silva</td>
                                            <td>Ortopedia</td>
                                            <td>Carlos Costa</td>
                                            <td><span class="badge-pill presencial">Presencial</span></td>
                                            <td><span class="badge-status cancelled"><i class="fas fa-times-circle"></i> Cancelado</span></td>
                                            <td>
                                                <button class="btn-action view-report" title="Visualizar relatório">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Users Page -->
                <div id="usuarios" class="page-content">
                    <section class="users-section">
                        <div class="section-header">
                            <h3>Gestão de Usuários</h3>
                            <button class="btn btn-primary" onclick="openUserForm()"><i class="fas fa-plus"></i> Novo Usuário</button>
                        </div>

                        <div class="card">
                            <div class="table-responsive">
                                <table class="users-table data-table compact">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                            <th>Gênero</th>
                                            <th>BI</th>
                                            <th>Função</th>
                                            <th>Especialidade</th>
                                            <th>Hospital</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="usuariosTableBody">
                                        <tr>
                                            <td><strong>Dr. Carlos Lima</strong></td>
                                            <td>carlos.lima@hospital.com</td>
                                            <td>+244 912 345 678</td>
                                            <td>Masculino</td>
                                            <td>00123456AZ001</td>
                                            <td><span class="badge-role doctor">Médico</span></td>
                                            <td>Cardiologia</td>
                                            <td>Hospital Central</td>
                                            <td><span class="status-indicator active"></span> Ativo</td>
                                            <td>
                                                <button class="btn-action edit" title="Editar"><i class="fas fa-edit"></i></button>
                                                <button class="btn-action delete" title="Deletar"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dra. Maria Santos</strong></td>
                                            <td>maria.santos@hospital.com</td>
                                            <td>+244 912 345 679</td>
                                            <td>Feminino</td>
                                            <td>00123456AZ002</td>
                                            <td><span class="badge-role doctor">Médico</span></td>
                                            <td>Pediatria</td>
                                            <td>Hospital Central</td>
                                            <td><span class="status-indicator active"></span> Ativo</td>
                                            <td>
                                                <button class="btn-action edit" title="Editar"><i class="fas fa-edit"></i></button>
                                                <button class="btn-action delete" title="Deletar"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- User Form Modal -->
                        <div id="userFormModal" class="modal-overlay">
                            <div class="modal-dialog">
                                <div class="modal-header">
                                    <h3><i class="fas fa-user-plus"></i> Adicionar/Editar Usuário</h3>
                                    <button class="modal-close" onclick="closeUserForm()" aria-label="Fechar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="formUsuario" class="user-form">
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label><i class="fas fa-user"></i> Nome</label>
                                                <input type="text" id="nome" required>
                                            </div>
                                            <div class="form-group">
                                                <label><i class="fas fa-envelope"></i> Email</label>
                                                <input type="email" id="email" required>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label><i class="fas fa-phone"></i> Telefone</label>
                                                <input type="tel" id="telefone">
                                            </div>
                                            <div class="form-group">
                                                <label><i class="fas fa-venus-mars"></i> Gênero</label>
                                                <select id="genero">
                                                    <option value="">Selecione</option>
                                                    <option value="masculino">Masculino</option>
                                                    <option value="feminino">Feminino</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label><i class="fas fa-id-card"></i> BI</label>
                                                <input type="text" id="bi" placeholder="Número do BI">
                                            </div>
                                            <div class="form-group">
                                                <label><i class="fas fa-user-md"></i> Função</label>
                                                <select id="userRole" required>
                                                    <option value="">Selecione</option>
                                                    <option value="admin">Administrador</option>
                                                    <option value="medico">Médico</option>
                                                    <option value="secretario">Secretário</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group" id="especialidadeGroup" style="display:none;">
                                                <label><i class="fas fa-stethoscope"></i> Especialidade</label>
                                                <select id="especialidadeId">
                                                    <option value="">Selecione</option>
                                                </select>
                                            </div>

                                            <div class="form-group medico-only" id="teleconsultaGroup" style="display:none;">
                                                <label><i class="fas fa-video"></i>Teleconsulta</label>
                                                <select id="teleconsulta">
                                                    <option value="1">Sim</option>
                                                    <option value="0">Não</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label><i class="fas fa-hospital"></i> Hospital</label>
                                                <select id="hospitalId" required>
                                                    <option value="">Selecione o hospital</option>
                                                </select>
                                            </div>

                                            <!-- SENHA -->
                                            <div class="form-group">
                                                <label><i class="fas fa-lock"></i>Senha Inicial</label>
                                                <input type="password" id="senha" required>
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-check"></i> Salvar
                                            </button>
                                            <button type="button" class="btn btn-outline" onclick="closeUserForm()">
                                                <i class="fas fa-times"></i> Cancelar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Specialties Page -->
                <div id="especialidades" class="page-content">
                    <section class="especialidades-section">
                        <div class="section-header">
                            <h3>Gerenciar Especialidades</h3>
                            <button class="btn btn-primary" id="btnCadastrarEsp"><i class="fas fa-plus"></i> Cadastrar</button>
                        </div>

                        <div class="card">
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Descrição</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listaEspecialidades">
                                        <tr>
                                            <td><strong>Cardiologia</strong></td>
                                            <td>Doenças do coração e circulação</td>
                                            <td><span class="status-indicator active"></span> Ativo</td>
                                            <td>
                                                <button class="btn-action edit" title="Editar"><i class="fas fa-edit"></i></button>
                                                <button class="btn-action delete" title="Deletar"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Specialty Form Modal -->
                        <div id="especialidadeModal" class="modal-overlay">
                            <div class="modal-dialog">
                                <div class="modal-header">
                                    <h3><i class="fas fa-plus-circle"></i> Cadastrar Especialidade</h3>
                                    <button class="modal-close" onclick="closeEspecialidadeForm()" aria-label="Fechar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="formEspecialidade" class="form">
                                        <div class="form-group">
                                            <label><i class="fas fa-heading"></i> Nome da Especialidade</label>
                                            <input type="text" id="espNome" required placeholder="Ex: Cardiologia">
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fas fa-align-left"></i> Descrição</label>
                                            <textarea id="espDescricao" required placeholder="Descreva a especialidade..."></textarea>
                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-check"></i> Salvar
                                            </button>
                                            <button type="button" class="btn btn-outline" onclick="closeEspecialidadeForm()">
                                                <i class="fas fa-times"></i> Cancelar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Medical Schedules Page -->
                <div id="escalas" class="page-content">
                    <section class="escalas-section">
                        <div class="section-header">
                            <h3>Gestão de Escalas Médicas</h3>
                            <button class="btn btn-primary" id="btnNovaEscala"><i class="fas fa-calendar-plus"></i> Nova Escala</button>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fas fa-calendar"></i> Escalas Criadas</h4>
                            </div>
                            <div class="escalas-container" id="escalasContainer">
                                <div class="escala-card">
                                    <div class="escala-header">

                                    </div>
                                    <div class="escala-details">

                                    </div>
                                    <div class="week-schedule">
                                        <div class="day-slot available">

                                        </div>
                                        <div class="day-slot available">

                                        </div>
                                        <div class="day-slot available">
                                            <span class="day-name">Qua</span>
                                            <span class="time-range">08:00 - 12:00</span>
                                        </div>
                                        <div class="day-slot unavailable">
                                            <span class="day-name">Qui</span>
                                            <span class="time-range">Indisponível</span>
                                        </div>
                                        <div class="day-slot available">
                                            <span class="day-name">Sex</span>
                                            <span class="time-range">08:00 - 12:00</span>
                                        </div>
                                        <div class="day-slot unavailable">
                                            <span class="day-name">Sab</span>
                                            <span class="time-range">Indisponível</span>
                                        </div>
                                        <div class="day-slot unavailable">
                                            <span class="day-name">Dom</span>
                                            <span class="time-range">Indisponível</span>
                                        </div>
                                    </div>
                                    <div class="escala-actions">
                                        <button class="btn-action edit" onclick="openScaleForm()"><i class="fas fa-edit"></i> Editar</button>
                                        <button class="btn-action delete"><i class="fas fa-trash"></i> Deletar</button>
                                    </div>
                                </div>
                                <div class="escala-card">
                                    <div class="escala-header">
                                        <h5><i class="fas fa-user-md"></i> Dra. Maria Santos</h5>
                                        <span class="badge-status active">Ativo</span>
                                    </div>
                                    <div class="escala-details">
                                        <p><strong>Especialidade:</strong> Pediatria</p>
                                        <p><strong>Turno:</strong> Tarde (12:00 - 18:00)</p>
                                    </div>
                                    <div class="week-schedule">
                                        <div class="day-slot available">
                                            <span class="day-name">Seg</span>
                                            <span class="time-range">12:00 - 18:00</span>
                                        </div>
                                        <div class="day-slot unavailable">
                                            <span class="day-name">Ter</span>
                                            <span class="time-range">Indisponível</span>
                                        </div>
                                        <div class="day-slot available">
                                            <span class="day-name">Qua</span>
                                            <span class="time-range">12:00 - 18:00</span>
                                        </div>
                                        <div class="day-slot available">
                                            <span class="day-name">Qui</span>
                                            <span class="time-range">12:00 - 18:00</span>
                                        </div>
                                        <div class="day-slot available">
                                            <span class="day-name">Sex</span>
                                            <span class="time-range">12:00 - 18:00</span>
                                        </div>
                                        <div class="day-slot unavailable">
                                            <span class="day-name">Sab</span>
                                            <span class="time-range">Indisponível</span>
                                        </div>
                                        <div class="day-slot unavailable">
                                            <span class="day-name">Dom</span>
                                            <span class="time-range">Indisponível</span>
                                        </div>
                                    </div>
                                    <div class="escala-actions">
                                        <button class="btn-action edit" onclick="openScaleForm()"><i class="fas fa-edit"></i> Editar</button>
                                        <button class="btn-action delete"><i class="fas fa-trash"></i> Deletar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scale Form Modal -->
                        <div id="scaleFormModal" class="modal-overlay">
                            <div class="modal-dialog">
                                <div class="modal-header">
                                    <h3><i class="fas fa-calendar-plus"></i> Criar Nova Escala</h3>
                                    <button class="modal-close" onclick="closeScaleForm()" aria-label="Fechar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="formEscala" class="form">
                                        <div class="form-group">
                                            <label><i class="fas fa-user-md"></i> Médico</label>
                                            <select id="medicoSelect" onchange="carregarEspecialidadeMedico()">

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fas fa-stethoscope"></i> Especialidade</label>
                                            <input type="text" id="especialidadeMedico" disabled>

                                        </div>

                                        <div class="form-group">
                                            <label>Intervalo por Consulta (minutos)</label>
                                            <input type="text" id="intervaloConsulta">
                                        </div>

                                        <div class="form-group">
                                            <label><i class="fas fa-clock"></i> Turno</label>
                                            <select id="turno" required>
                                                <option value="">Selecione turno</option>
                                                <option value="manha">Manhã (08:00 - 12:00)</option>
                                                <option value="tarde">Tarde (13:00 - 15:00)</option>
                                                <option value="integral">Integral (08:00 - 15:00)</option>
                                            </select>
                                        </div>

                                        <!-- HORÁRIO GERADO -->
                                        <div class="form-group">
                                            <label>Horário Gerado</label>
                                            <textarea id="previewHorario" rows="5" disabled></textarea>
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


                                        <div class="form-group">
                                            <label><i class="fas fa-calendar-check"></i> Dias Disponíveis</label>
                                            <div class="checkbox-group">
                                                <label class="checkbox-item">
                                                    <input type="checkbox" value="seg"> Segunda-feira
                                                </label>
                                                <label class="checkbox-item">
                                                    <input type="checkbox" value="ter"> Terça-feira
                                                </label>
                                                <label class="checkbox-item">
                                                    <input type="checkbox" value="qua"> Quarta-feira
                                                </label>
                                                <label class="checkbox-item">
                                                    <input type="checkbox" value="qui"> Quinta-feira
                                                </label>
                                                <label class="checkbox-item">
                                                    <input type="checkbox" value="sex"> Sexta-feira
                                                </label>
                                                <label class="checkbox-item">
                                                    <input type="checkbox" value="sab"> Sábado
                                                </label>
                                                <label class="checkbox-item">
                                                    <input type="checkbox" id="todosDias"> Todos os dias
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-check"></i> Salvar Escala
                                            </button>
                                            <button type="button" class="btn btn-outline" onclick="closeScaleForm()">
                                                <i class="fas fa-times"></i> Cancelar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Logs Page -->
                <div id="logs" class="page-content">
                    <section class="logs-section">
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-history"></i> Logs de Auditoria</h3>
                            </div>
                            <div class="filter-group">
                                <input type="date" id="logDate" placeholder="Data">
                                <select id="logUser">
                                    <option value="">Todos os usuários</option>
                                </select>
                                <select id="logAction">
                                    <option value="">Todas as ações</option>
                                </select>
                                <button class="btn btn-primary" onclick="filterLogs()"><i class="fas fa-search"></i> Filtrar</button>
                            </div>
                        </div>

                        <div class="card">
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Data/Hora</th>
                                            <th>Usuário</th>
                                            <th>Ação</th>
                                            <th>Descrição</th>
                                            <th>IP</th>
                                        </tr>
                                    </thead>
                                    <tbody id="logsTableBody">
                                        <tr>
                                            <td>01/03/2024 14:32</td>
                                            <td>Dr. João Silva</td>
                                            <td><span class="badge-action create"><i class="fas fa-plus"></i> CREATE</span></td>
                                            <td>Usuário "Maria Santos" criado</td>
                                            <td>192.168.1.100</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Notifications Page -->
                <div id="notificacoes" class="page-content">
                    <section class="notifications-section">
                        <div class="section-header">
                            <h3><i class="fas fa-bell"></i> Central de Notificações</h3>
                            <div class="notification-controls">
                                <button class="btn btn-outline" id="markAllRead"><i class="fas fa-check-double"></i> Marcar como Lida</button>
                                <button class="btn btn-outline" id="clearNotifications"><i class="fas fa-trash"></i> Limpar</button>
                            </div>
                        </div>

                        <div class="notifications-list" id="notificationsList">
                            <div class="notification-item unread">
                                <div class="notification-icon info">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="notification-content">
                                    <h5>Novo Paciente Registrado</h5>
                                    <p>João Santos foi registrado no sistema</p>
                                    <small>Há 5 minutos</small>
                                </div>
                            </div>
                            <div class="notification-item unread">
                                <div class="notification-icon success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="notification-content">
                                    <h5>Consulta Concluída</h5>
                                    <p>A consulta com Dr. Carlos Lima foi finalizada</p>
                                    <small>Há 15 minutos</small>
                                </div>
                            </div>
                            <div class="notification-item unread">
                                <div class="notification-icon warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="notification-content">
                                    <h5>Falha de Agendamento</h5>
                                    <p>Não foi possível agendar consulta para Ana Silva</p>
                                    <small>Há 1 hora</small>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Settings Page -->
                <div id="configuracoes" class="page-content">
                    <section class="settings-section">
                        <h3><i class="fas fa-sliders-h"></i> Configurações</h3>

                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fas fa-user"></i> Meu Perfil</h4>
                            </div>
                            <form class="settings-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Nome</label>
                                        <input type="text" value="<?= $_SESSION['nome'] ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" value="<?= $_SESSION['email'] ?>" disabled>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn btn-primary" onclick="editProfile()">
                                        <i class="fas fa-edit"></i> Editar Perfil
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fas fa-key"></i> Segurança</h4>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-primary" onclick="changePassword()">
                                    <i class="fas fa-lock"></i> Alterar Senha
                                </button>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fas fa-palette"></i> Aparência</h4>
                            </div>
                            <div class="theme-options">
                                <label class="theme-option">
                                    <input type="radio" name="theme" value="light" checked>
                                    <span><i class="fas fa-sun"></i> Claro</span>
                                </label>
                                <label class="theme-option">
                                    <input type="radio" name="theme" value="dark">
                                    <span><i class="fas fa-moon"></i> Escuro</span>
                                </label>
                                <label class="theme-option">
                                    <input type="radio" name="theme" value="auto">
                                    <span><i class="fas fa-circle-half-stroke"></i> Automático</span>
                                </label>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-primary" onclick="applyTheme()">
                                    <i class="fas fa-check"></i> Aplicar Tema
                                </button>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fas fa-bell"></i> Notificações</h4>
                            </div>
                            <form class="notification-preferences">
                                <label class="checkbox-group">
                                    <input type="checkbox" checked>
                                    <span>Notificações por Email</span>
                                </label>
                                <label class="checkbox-group">
                                    <input type="checkbox" checked>
                                    <span>Notificações no Sistema</span>
                                </label>
                                <label class="checkbox-group">
                                    <input type="checkbox">
                                    <span>Alertas de Segurança</span>
                                </label>
                            </form>
                            <div class="form-actions">
                                <button type="button" class="btn btn-primary" onclick="saveNotificationSettings()">
                                    <i class="fas fa-save"></i> Salvar Configurações
                                </button>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Support Page -->
                <div id="suporte" class="page-content">
                    <section class="support-section">
                        <h3><i class="fas fa-headset"></i> Suporte e Ajuda</h3>

                        <div class="support-grid">
                            <div class="support-card">
                                <div class="support-icon"><i class="fas fa-book"></i></div>
                                <h4>Documentação</h4>
                                <p>Acesse a documentação completa do sistema</p>
                                <a href="#" class="btn btn-outline"><i class="fas fa-external-link-alt"></i> Acessar</a>
                            </div>
                            <div class="support-card">
                                <div class="support-icon"><i class="fas fa-video"></i></div>
                                <h4>Tutoriais em Vídeo</h4>
                                <p>Aprenda com nossos tutoriais em vídeo</p>
                                <a href="#" class="btn btn-outline"><i class="fas fa-external-link-alt"></i> Ver Vídeos</a>
                            </div>
                            <div class="support-card">
                                <div class="support-icon"><i class="fas fa-envelope"></i></div>
                                <h4>Suporte por Email</h4>
                                <p>Envie sua dúvida para nossa equipe</p>
                                <a href="mailto:suporte@saudefacil.com" class="btn btn-outline"><i class="fas fa-envelope"></i> Enviar</a>
                            </div>
                            <div class="support-card">
                                <div class="support-icon"><i class="fas fa-phone"></i></div>
                                <h4>Suporte por Telefone</h4>
                                <p>Ligue para nosso suporte 24/7</p>
                                <a href="tel:+244912345678" class="btn btn-outline"><i class="fas fa-phone"></i> Ligar</a>
                            </div>
                        </div>

                        <div class="card faq-section">
                            <div class="card-header">
                                <h4><i class="fas fa-question-circle"></i> Perguntas Frequentes</h4>
                            </div>
                            <div class="faq-items">
                                <div class="faq-item">
                                    <button class="faq-question">
                                        <span>Como alterar minha senha?</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div class="faq-answer" style="display:none;">
                                        <p>Vá para Configurações > Segurança > Alterar Senha e siga as instruções na tela.</p>
                                    </div>
                                </div>
                                <div class="faq-item">
                                    <button class="faq-question">
                                        <span>Como exportar um relatório?</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div class="faq-answer" style="display:none;">
                                        <p>Na seção Relatórios, selecione os filtros desejados e clique em "Exportar".</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <!-- Report Modal -->
    <div id="reportModal" class="modal-overlay">
        <div class="modal-dialog lg">
            <div class="modal-header">
                <h3><i class="fas fa-file-pdf"></i> Relatório do Paciente</h3>
                <button class="modal-close" onclick="closeReportModal()" aria-label="Fechar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="report-document">
                    <div class="report-header">
                        <h2>Relatório de Consulta</h2>
                        <p class="report-date" id="reportDate"></p>
                    </div>
                    <div class="report-patient-info">
                        <h4>Informações do Paciente</h4>
                        <div class="info-grid">
                            <p><strong>Nome:</strong> <span id="reportPatientName"></span></p>
                            <p><strong>Data de Nascimento:</strong> <span id="reportPatientDOB"></span></p>
                            <p><strong>Médico:</strong> <span id="reportDoctorName"></span></p>
                            <p><strong>Especialidade:</strong> <span id="reportSpecialty"></span></p>
                        </div>
                    </div>
                    <div class="report-section">
                        <h4>Motivo da Consulta</h4>
                        <p id="reportReason"></p>
                    </div>
                    <div class="report-section">
                        <h4>Diagnóstico</h4>
                        <p id="reportDiagnosis"></p>
                    </div>
                    <div class="report-section">
                        <h4>Observações</h4>
                        <p id="reportNotes"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="printReport()"><i class="fas fa-print"></i> Imprimir</button>
                <button class="btn btn-outline" onclick="exportReportPDF()"><i class="fas fa-file-pdf"></i> Exportar PDF</button>
                <button class="btn btn-outline" onclick="closeReportModal()"><i class="fas fa-times"></i> Fechar</button>
            </div>
        </div>
    </div>

    <script src="assets/js/admin.js"></script>
</body>

</html>