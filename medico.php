<?php
require_once "phpconexao/protect.php";

// apenas secretário
if ($_SESSION['tipo_usuario'] !== 'medico') {

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
<script>
    window.tipo_usuario = "<?= $_SESSION['tipo_usuario'] ?>";
    window.nome_usuario = "<?= $_SESSION['nome'] ?>";
</script>

    <title>Saúde Fácil - Dashboard Médico</title>
    <link rel="stylesheet" href="assets/css/medico.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Container Principal -->
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-heart-pulse"></i>
                    <span>Saúde Fácil</span>
                </div>
                <button class="toggle-sidebar" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li><a href="#" class="nav-link active" data-page="dashboard"><i class="fas fa-chart-line"></i> <span>Dashboard</span></a></li>
                    <li><a href="#" class="nav-link" data-page="agenda"><i class="fas fa-calendar"></i> <span>Agenda</span></a></li>
                    <li><a href="#" class="nav-link" data-page="pacientes"><i class="fas fa-users"></i> <span>Pacientes</span></a></li>
                    <li><a href="#" class="nav-link" data-page="notificacoes"><i class="fas fa-bell"></i> <span>Notificações</span></a></li>
                </ul>

                <hr class="nav-divider">

                <ul class="nav-list">
                    <li><a href="#" class="nav-link" data-page="configuracoes"><i class="fas fa-gear"></i> <span>Configurações</span></a></li>
                    <li><a href="#" class="nav-link" data-page="suporte"><i class="fas fa-headset"></i> <span>Suporte</span></a></li>
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
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="top-bar-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="breadcrumb" id="breadcrumb">
                        Dashboard
                    </div>
                </div>

                <div class="top-bar-right">
                    <div class="notification-bell">
                        <button id="notificationBtn" class="bell-btn">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge" id="notificationBadge">3</span>
                        </button>
                    </div>

                    <div class="theme-toggle">
                        <button id="themeToggle" class="theme-btn">
                            <i class="fas fa-moon"></i>
                        </button>
                    </div>

                    <div class="user-menu">
                        <img src="https://i.pravatar.cc/40?img=1" alt="Perfil" class="user-avatar">
                        <div class="user-info">
                            <span class="user-name">Dr. João Silva</span>
                            <span class="user-role">Cardiologista</span>
                        </div>
                        <button class="user-menu-btn" id="userMenuBtn">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" id="userDropdown">
                            <a href="#" class="dropdown-item"><i class="fas fa-user"></i> Perfil</a>
                            <a href="#" class="dropdown-item"><i class="fas fa-key"></i> Alterar Senha</a>
                            <hr>
                            <a href="#" class="dropdown-item logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="content-wrapper">
                <!-- Dashboard Page -->
                <div id="dashboard" class="page-content active">
                    <div class="page-header">
                        <h1>Dashboard</h1>
                        <p class="text-muted">Bem-vindo de volta! Aqui está o resumo de sua atividade.</p>
                    </div>

                    <!-- Stats Cards -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon blue">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <p class="stat-label">Consultas Hoje</p>
                                <h3 class="stat-value">12</h3>
                                <span class="stat-change positive">↑ 2 a mais que ontem</span>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon green">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <p class="stat-label">Pacientes Atendidos</p>
                                <h3 class="stat-value">284</h3>
                                <span class="stat-change positive">↑ 8 novos esta semana</span>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon purple">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <p class="stat-label">Próxima Consulta</p>
                                <h3 class="stat-value">14:30</h3>
                                <span class="stat-change neutral">Em 45 minutos</span>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon orange">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-content">
                                <p class="stat-label">Avaliação Média</p>
                                <h3 class="stat-value">4.8/5</h3>
                                <span class="stat-change positive">↑ 12 avaliações</span>
                            </div>
                        </div>
                    </div>

                    <!-- Charts and Lists Section -->
                    <div class="dashboard-grid">
                        <!-- Chart -->
                        <div class="card">
                            <div class="card-header">
                                <h2>Consultas da Semana</h2>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="weekChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Appointments -->
                        <div class="card">
                            <div class="card-header">
                                <h2>Próximas Consultas</h2>
                                <a href="#" class="text-link">Ver Tudo</a>
                            </div>
                            <div class="card-body">
                                <div class="appointment-list">
                                    <div class="appointment-item">
                                        <div class="appointment-time">14:30</div>
                                        <div class="appointment-details">
                                            <p class="appointment-patient">Maria Santos</p>
                                            <p class="appointment-reason">Consulta de rotina</p>
                                        </div>
                                        <div class="appointment-actions">
                                            <button class="btn btn-sm btn-primary">Iniciar</button>
                                        </div>
                                    </div>

                                    <div class="appointment-item">
                                        <div class="appointment-time">15:15</div>
                                        <div class="appointment-details">
                                            <p class="appointment-patient">Carlos Mendes</p>
                                            <p class="appointment-reason">Seguimento cardíaco</p>
                                        </div>
                                        <div class="appointment-actions">
                                            <button class="btn btn-sm btn-primary">Iniciar</button>
                                        </div>
                                    </div>

                                    <div class="appointment-item">
                                        <div class="appointment-time">16:00</div>
                                        <div class="appointment-details">
                                            <p class="appointment-patient">Ana Costa</p>
                                            <p class="appointment-reason">Avaliação de exames</p>
                                        </div>
                                        <div class="appointment-actions">
                                            <button class="btn btn-sm btn-primary">Iniciar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Notifications -->
                        <div class="card">
                            <div class="card-header">
                                <h2>Notificações Recentes</h2>
                            </div>
                            <div class="card-body">
                                <div class="notification-list">
                                    <div class="notification-item">
                                        <div class="notification-icon blue">
                                            <i class="fas fa-message"></i>
                                        </div>
                                        <div class="notification-content">
                                            <p class="notification-title">Nova mensagem de Pedro</p>
                                            <p class="notification-time">Há 5 minutos</p>
                                        </div>
                                    </div>

                                    <div class="notification-item">
                                        <div class="notification-icon green">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="notification-content">
                                            <p class="notification-title">Consulta confirmada com Julia</p>
                                            <p class="notification-time">Há 15 minutos</p>
                                        </div>
                                    </div>

                                    <div class="notification-item">
                                        <div class="notification-icon orange">
                                            <i class="fas fa-flask"></i>
                                        </div>
                                        <div class="notification-content">
                                            <p class="notification-title">Novo exame enviado por Roberto</p>
                                            <p class="notification-time">Há 1 hora</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agenda Page -->
                <div id="agenda" class="page-content">
                    <div class="page-header">
                        <h1>Agenda Médica</h1>
                        <p class="text-muted">Gerencie sua agenda de atendimentos</p>
                    </div>

                    <div class="calendar-container">
                        <div class="calendar-widget">
                            <div class="calendar-header">
                                <button class="calendar-nav-btn" id="prevMonth"><i class="fas fa-chevron-left"></i></button>
                                <h2 id="currentMonth">Março 2025</h2>
                                <button class="calendar-nav-btn" id="nextMonth"><i class="fas fa-chevron-right"></i></button>
                            </div>
                            <div class="calendar-weekdays">
                                <span>Dom</span>
                                <span>Seg</span>
                                <span>Ter</span>
                                <span>Qua</span>
                                <span>Qui</span>
                                <span>Sex</span>
                                <span>Sab</span>
                            </div>
                            <div class="calendar-days" id="calendarDays"></div>
                        </div>
                    </div>

                    <div class="schedule-grid">
                        <div class="card">
                            <div class="card-header">
                                <h2>Horários - <span id="selectedDate">10 de Março</span></h2>
                                <button class="btn btn-sm btn-primary">+ Novo Horário</button>
                            </div>
                            <div class="card-body">
                                <div class="schedule-timeline" id="scheduleTimeline"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pacientes Page -->
                <div id="pacientes" class="page-content">
                    <div class="page-header">
                        <h1>Pacientes</h1>
                        <p class="text-muted">Visualize e gerencie seus pacientes</p>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Buscar paciente...">
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="patients-table">
                                    <thead>
                                        <tr>
                                            <th>Foto</th>
                                            <th>Nome</th>
                                            <th>Idade</th>
                                            <th>Telefone</th>
                                            <th>Última Consulta</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><img src="https://i.pravatar.cc/40?img=2" alt="Paciente" class="patient-avatar"></td>
                                            <td>Maria Santos</td>
                                            <td>32 anos</td>
                                            <td>(11) 98765-4321</td>
                                            <td>10 Mar 2025</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-sm btn-outline"><i class="fas fa-user"></i></button>
                                                    <button class="btn btn-sm btn-outline"><i class="fas fa-comments"></i></button>
                                                    <button class="btn btn-sm btn-outline"><i class="fas fa-video"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><img src="https://i.pravatar.cc/40?img=3" alt="Paciente" class="patient-avatar"></td>
                                            <td>Carlos Mendes</td>
                                            <td>48 anos</td>
                                            <td>(11) 97654-3210</td>
                                            <td>08 Mar 2025</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-sm btn-outline"><i class="fas fa-user"></i></button>
                                                    <button class="btn btn-sm btn-outline"><i class="fas fa-comments"></i></button>
                                                    <button class="btn btn-sm btn-outline"><i class="fas fa-video"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><img src="https://i.pravatar.cc/40?img=4" alt="Paciente" class="patient-avatar"></td>
                                            <td>Ana Costa</td>
                                            <td>26 anos</td>
                                            <td>(11) 96543-2109</td>
                                            <td>05 Mar 2025</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-sm btn-outline"><i class="fas fa-user"></i></button>
                                                    <button class="btn btn-sm btn-outline"><i class="fas fa-comments"></i></button>
                                                    <button class="btn btn-sm btn-outline"><i class="fas fa-video"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prontuário Page -->
                <div id="prontuario" class="page-content">
                    <div class="page-header">
                        <h1>Prontuário Médico</h1>
                        <p class="text-muted">Visualize e registre informações médicas dos pacientes</p>
                    </div>

                    <div class="grid-2">
                        <div class="card">
                            <div class="card-header">
                                <h2>Selecione um Paciente</h2>
                            </div>
                            <div class="card-body">
                                <div class="patient-list-slim">
                                    <div class="patient-item selected">
                                        <img src="https://i.pravatar.cc/50?img=2" alt="Maria">
                                        <div>
                                            <p class="patient-name">Maria Santos</p>
                                            <p class="patient-id">ID: 001</p>
                                        </div>
                                    </div>
                                    <div class="patient-item">
                                        <img src="https://i.pravatar.cc/50?img=3" alt="Carlos">
                                        <div>
                                            <p class="patient-name">Carlos Mendes</p>
                                            <p class="patient-id">ID: 002</p>
                                        </div>
                                    </div>
                                    <div class="patient-item">
                                        <img src="https://i.pravatar.cc/50?img=4" alt="Ana">
                                        <div>
                                            <p class="patient-name">Ana Costa</p>
                                            <p class="patient-id">ID: 003</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2>Prontuário de Maria Santos</h2>
                            </div>
                            <div class="card-body">
                                <div class="tabs">
                                    <button class="tab-btn active" data-tab="personal">Pessoal</button>
                                    <button class="tab-btn" data-tab="history">Histórico</button>
                                    <button class="tab-btn" data-tab="exams">Exames</button>
                                </div>

                                <div id="personal" class="tab-content active">
                                    <div class="form-group">
                                        <label>Data de Nascimento</label>
                                        <input type="text" value="15/03/1992" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Alergias</label>
                                        <input type="text" value="Penicilina, Látex" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Doenças Crônicas</label>
                                        <input type="text" value="Hipertensão" readonly>
                                    </div>
                                </div>

                                <div id="history" class="tab-content">
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <span class="timeline-date">10 Mar 2025</span>
                                            <div class="timeline-content">
                                                <h4>Consulta de Rotina</h4>
                                                <p>Paciente apresenta pressão normal. Prescrito continuação do medicamento.</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <span class="timeline-date">28 Feb 2025</span>
                                            <div class="timeline-content">
                                                <h4>Avaliação de Exames</h4>
                                                <p>Resultados dos exames de sangue normais. Sem alterações significativas.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="exams" class="tab-content">
                                    <div class="exam-list">
                                        <div class="exam-item">
                                            <i class="fas fa-file-pdf"></i>
                                            <div>
                                                <p>Exame de Sangue</p>
                                                <small>10 Mar 2025</small>
                                            </div>
                                            <button class="btn btn-sm btn-outline"><i class="fas fa-download"></i></button>
                                        </div>
                                        <div class="exam-item">
                                            <i class="fas fa-file-pdf"></i>
                                            <div>
                                                <p>Eletrocardiograma</p>
                                                <small>28 Feb 2025</small>
                                            </div>
                                            <button class="btn btn-sm btn-outline"><i class="fas fa-download"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat/Teleconsulta Page -->
                <div id="chat" class="page-content">
                    <div class="page-header">
                        <h1>Chat / Teleconsulta</h1>
                        <p class="text-muted">Comunique-se com seus pacientes</p>
                    </div>

                    <div class="chat-container">
                        <div class="chat-sidebar">
                            <div class="chat-search">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Buscar paciente...">
                            </div>
                            <div class="chat-list" id="chatList">
                                <div class="chat-item active">
                                    <img src="https://i.pravatar.cc/40?img=2" alt="Maria">
                                    <div class="chat-item-content">
                                        <p class="chat-name">Maria Santos</p>
                                        <p class="chat-preview">Obrigado pelo atendimento...</p>
                                    </div>
                                    <span class="chat-time">14:30</span>
                                </div>
                                <div class="chat-item">
                                    <img src="https://i.pravatar.cc/40?img=3" alt="Carlos">
                                    <div class="chat-item-content">
                                        <p class="chat-name">Carlos Mendes</p>
                                        <p class="chat-preview">Tenho dúvida sobre a receita...</p>
                                    </div>
                                    <span class="chat-time">12:15</span>
                                </div>
                            </div>
                        </div>

                        <div class="chat-main">
                            <div class="chat-header">
                                <div class="chat-header-info">
                                    <img src="https://i.pravatar.cc/40?img=2" alt="Maria">
                                    <div>
                                        <h3>Maria Santos</h3>
                                        <p class="text-muted">Ativo agora</p>
                                    </div>
                                </div>
                                <div class="chat-header-actions">
                                    <button class="btn btn-icon" title="Iniciar chamada de voz">
                                        <i class="fas fa-phone"></i>
                                    </button>
                                    <button class="btn btn-icon" title="Iniciar chamada de vídeo">
                                        <i class="fas fa-video"></i>
                                    </button>
                                    <button class="btn btn-icon" title="Mais opções">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="chat-messages" id="chatMessages">
                                <div class="message-group">
                                    <div class="message received">
                                        <p>Olá doutor, tudo bem?</p>
                                        <span class="message-time">14:25</span>
                                    </div>
                                    <div class="message received">
                                        <p>Tenho dúvida sobre a medicação</p>
                                        <span class="message-time">14:26</span>
                                    </div>
                                </div>

                                <div class="message-group">
                                    <div class="message sent">
                                        <p>Oi Maria! Claro, como posso ajudar?</p>
                                        <span class="message-time">14:27</span>
                                    </div>
                                </div>

                                <div class="message-group">
                                    <div class="message received">
                                        <p>Qual é a dosagem correta?</p>
                                        <span class="message-time">14:28</span>
                                    </div>
                                </div>
                            </div>

                            <div class="chat-input">
                                <button class="btn btn-icon" title="Adicionar arquivo">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <input type="text" placeholder="Digite sua mensagem..." id="messageInput">
                                <button class="btn btn-icon primary" id="sendBtn" title="Enviar">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consultas Online Page -->
                <div id="consultas" class="page-content">
                    <div class="page-header">
                        <h1>Consultas Online</h1>
                        <p class="text-muted">Gerenciar suas videoconsultas</p>
                    </div>

                    <div class="grid-2">
                        <div class="card">
                            <div class="card-header">
                                <h2>Consultas Agendadas</h2>
                            </div>
                            <div class="card-body">
                                <div class="consultation-list">
                                    <div class="consultation-item">
                                        <div class="consultation-header">
                                            <img src="https://i.pravatar.cc/40?img=2" alt="Maria">
                                            <div>
                                                <p class="consultation-patient">Maria Santos</p>
                                                <p class="consultation-time"><i class="fas fa-clock"></i> 14:30 - 15:00</p>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary w-full">Iniciar Consulta</button>
                                    </div>

                                    <div class="consultation-item">
                                        <div class="consultation-header">
                                            <img src="https://i.pravatar.cc/40?img=3" alt="Carlos">
                                            <div>
                                                <p class="consultation-patient">Carlos Mendes</p>
                                                <p class="consultation-time"><i class="fas fa-clock"></i> 15:15 - 15:45</p>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary w-full">Iniciar Consulta</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2>Consulta em Progresso</h2>
                            </div>
                            <div class="card-body">
                                <div class="video-room">
                                    <div class="video-container">
                                        <div class="video-grid">
                                            <div class="video-stream doctor">
                                                <i class="fas fa-video"></i>
                                                <p>Você</p>
                                            </div>
                                            <div class="video-stream patient">
                                                <i class="fas fa-video"></i>
                                                <p>Maria Santos</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="video-controls">
                                        <button class="btn btn-icon" title="Microfone">
                                            <i class="fas fa-microphone"></i>
                                        </button>
                                        <button class="btn btn-icon" title="Câmera">
                                            <i class="fas fa-video"></i>
                                        </button>
                                        <button class="btn btn-icon danger" title="Encerrar chamada">
                                            <i class="fas fa-phone-slash"></i>
                                        </button>
                                        <button class="btn btn-icon" title="Compartilhar tela">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>

                                    <div class="video-chat-sidebar">
                                        <h4>Chat</h4>
                                        <div class="video-chat-messages">
                                            <div class="message received">
                                                <p>Tenho sentido dor no peito</p>
                                            </div>
                                            <div class="message sent">
                                                <p>Vamos avaliar com cuidado</p>
                                            </div>
                                        </div>
                                        <input type="text" placeholder="Mensagem...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <!-- Notificações Page -->
                <div id="notificacoes" class="page-content">
                    <div class="page-header">
                        <h1>Notificações</h1>
                        <p class="text-muted">Gerencie suas notificações</p>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h2>Todas as Notificações</h2>
                            <button class="btn btn-sm btn-outline">Marcar tudo como lido</button>
                        </div>
                        <div class="card-body">
                            <div class="notifications-full">
                                <div class="notification-full unread">
                                    <div class="notification-full-icon blue">
                                        <i class="fas fa-message"></i>
                                    </div>
                                    <div class="notification-full-content">
                                        <p class="notification-full-title">Nova mensagem de Maria Santos</p>
                                        <p class="notification-full-text">Tenho uma dúvida sobre minha medicação</p>
                                        <p class="notification-full-time">Há 5 minutos</p>
                                    </div>
                                </div>

                                <div class="notification-full unread">
                                    <div class="notification-full-icon green">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="notification-full-content">
                                        <p class="notification-full-title">Consulta confirmada</p>
                                        <p class="notification-full-text">Carlos Mendes confirmou sua consulta para 15 de Março</p>
                                        <p class="notification-full-time">Há 2 horas</p>
                                    </div>
                                </div>

                                <div class="notification-full">
                                    <div class="notification-full-icon orange">
                                        <i class="fas fa-flask"></i>
                                    </div>
                                    <div class="notification-full-content">
                                        <p class="notification-full-title">Novo exame enviado</p>
                                        <p class="notification-full-text">Ana Costa enviou seus resultados de exame de sangue</p>
                                        <p class="notification-full-time">Há 1 dia</p>
                                    </div>
                                </div>

                                <div class="notification-full">
                                    <div class="notification-full-icon purple">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div class="notification-full-content">
                                        <p class="notification-full-title">Lembrança de consulta</p>
                                        <p class="notification-full-text">Você tem uma consulta agendada em 2 horas</p>
                                        <p class="notification-full-time">Há 2 dias</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configurações Page -->
                <div id="configuracoes" class="page-content">
                    <div class="page-header">
                        <h1>Configurações</h1>
                        <p class="text-muted">Gerencie sua conta e preferências</p>
                    </div>

                    <div class="settings-container">
                        <div class="card">
                            <div class="card-header">
                                <h2>Perfil Profissional</h2>
                            </div>
                            <div class="card-body">
                                <div class="settings-section">
                                    <div class="form-group">
                                        <label>Nome Completo</label>
                                        <input type="text" value="Dr. João Silva">
                                    </div>

                                    <div class="form-group">
                                        <label>Especialidade</label>
                                        <input type="text" value="Cardiologista">
                                    </div>

                                    <div class="form-group">
                                        <label>CRM</label>
                                        <input type="text" value="123456/SP">
                                    </div>

                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" value="joao.silva@saudefacil.com">
                                    </div>

                                    <button class="btn btn-primary">Salvar Alterações</button>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2>Segurança</h2>
                            </div>
                            <div class="card-body">
                                <div class="settings-section">
                                    <div class="form-group">
                                        <label>Senha Atual</label>
                                        <input type="password" placeholder="••••••••">
                                    </div>

                                    <div class="form-group">
                                        <label>Nova Senha</label>
                                        <input type="password" placeholder="••••••••">
                                    </div>

                                    <div class="form-group">
                                        <label>Confirmar Senha</label>
                                        <input type="password" placeholder="••••••••">
                                    </div>

                                    <button class="btn btn-primary">Alterar Senha</button>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2>Preferências</h2>
                            </div>
                            <div class="card-body">
                                <div class="settings-section">
                                    <div class="settings-item">
                                        <div>
                                            <p class="settings-label">Modo Escuro</p>
                                            <p class="text-muted">Ativar tema escuro automático</p>
                                        </div>
                                        <div class="toggle-switch">
                                            <input type="checkbox" id="darkModeToggle">
                                            <label for="darkModeToggle"></label>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="settings-item">
                                        <div>
                                            <p class="settings-label">Notificações por Email</p>
                                            <p class="text-muted">Receber notificações importantes por email</p>
                                        </div>
                                        <div class="toggle-switch">
                                            <input type="checkbox" id="emailNotifications" checked>
                                            <label for="emailNotifications"></label>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="settings-item">
                                        <div>
                                            <p class="settings-label">Notificações por SMS</p>
                                            <p class="text-muted">Receber alertas urgentes por SMS</p>
                                        </div>
                                        <div class="toggle-switch">
                                            <input type="checkbox" id="smsNotifications" checked>
                                            <label for="smsNotifications"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suporte Page -->
                <div id="suporte" class="page-content">
                    <div class="page-header">
                        <h1>Suporte e Ajuda</h1>
                        <p class="text-muted">Encontre respostas e entre em contato com nosso suporte</p>
                    </div>

                    <div class="grid-2">
                        <div class="card">
                            <div class="card-header">
                                <h2>Perguntas Frequentes</h2>
                            </div>
                            <div class="card-body">
                                <div class="faq-list">
                                    <div class="faq-item">
                                        <button class="faq-question">
                                            <span>Como faço para agendar uma consulta?</span>
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                        <div class="faq-answer">
                                            <p>Para agendar uma consulta, vá à seção "Agenda" e clique em "Novo Horário". Selecione a data e hora desejadas.</p>
                                        </div>
                                    </div>

                                    <div class="faq-item">
                                        <button class="faq-question">
                                            <span>Como envio uma receita digital?</span>
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                        <div class="faq-answer">
                                            <p>Vá à seção "Receitas Médicas" e clique em "Nova Receita". Preencha os dados e clique em "Gerar Receita".</p>
                                        </div>
                                    </div>

                                    <div class="faq-item">
                                        <button class="faq-question">
                                            <span>Como visualizar o histórico de um paciente?</span>
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                        <div class="faq-answer">
                                            <p>Acesse a seção "Prontuário" e selecione o paciente. Você verá todo o histórico clínico e exames.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2>Contato com Suporte</h2>
                            </div>
                            <div class="card-body">
                                <form class="support-form">
                                    <div class="form-group">
                                        <label>Assunto</label>
                                        <select>
                                            <option>Selecione um assunto</option>
                                            <option>Problema Técnico</option>
                                            <option>Dúvida sobre Funcionalidade</option>
                                            <option>Reportar Bug</option>
                                            <option>Feedback</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Mensagem</label>
                                        <textarea placeholder="Descreva seu problema ou dúvida..." rows="6"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-full">Enviar Mensagem</button>
                                </form>

                                <hr>

                                <div class="support-info">
                                    <h3>Informações de Contato</h3>
                                    <p><i class="fas fa-envelope"></i> suporte@saudefacil.com</p>
                                    <p><i class="fas fa-phone"></i> (11) 3000-1000</p>
                                    <p><i class="fas fa-clock"></i> Seg - Sex: 08:00 - 18:00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="modalTeleconsulta" class="modal-teleconsulta">
    <div class="modal-conteudo">

        <div class="teleconsulta-aviso">
            <i class="fas fa-info-circle"></i>
            Use o botão <strong>Fechar Consulta</strong> do sistema para sair da consulta.
        </div>

        <button class="btn-fechar" onclick="fecharTeleconsulta()">
            Fechar Consulta
        </button>

        <iframe
            id="iframeTeleconsulta"
            width="100%"
            height="100%"
            allow="camera; microphone; fullscreen">
        </iframe>

    </div>
</div>

    <script src="assets/js/medico.js"></script>
</body>
</html>
