<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(404);
    exit("404 - Página não encontrada");
}

// 🔥 BLOQUEIA OUTROS TIPOS
if ($_SESSION['tipo_usuario'] !== 'paciente') {
    http_response_code(403);
    exit("Acesso negado");
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

    <title>Saúde Fácil - Plataforma de Telemedicina</title>
    <link rel="stylesheet" href="assets/css/paciente.css">
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </button>
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-heartbeat"></i>
                <span>Saúde Fácil</span>
            </div>
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <ul>
                <li><a href="#dashboard" class="nav-link active" data-section="dashboard">
                        <i class="fas fa-chart-line"></i><span>Dashboard</span>
                    </a></li>
                <li><a href="#agendamento" class="nav-link" data-section="agendamento">
                        <i class="fas fa-calendar-alt"></i><span>Agendamento</span>
                    </a></li>
                <li><a href="#consultas" class="nav-link" data-section="consultas">
                        <i class="fas fa-list-ul"></i><span>Consultas Agendadas</span>
                    </a></li>

                <li><a href="#historico" class="nav-link" data-section="historico">
                        <i class="fas fa-history"></i><span>Histórico</span>
                    </a></li>
                <li><a href="#perfil" class="nav-link" data-section="perfil">
                        <i class="fas fa-user-circle"></i><span>Perfil</span>
                    </a></li>
                <li><a href="#configuracoes" class="nav-link" data-section="configuracoes">
                        <i class="fas fa-cog"></i><span>Configurações</span>
                    </a></li>
                <li><a href="#suporte" class="nav-link" data-section="suporte">
                        <i class="fas fa-question-circle"></i><span>Suporte</span>
                    </a></li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <button class="logout-btn">
                <i class="fas fa-sign-out-alt"></i><span>Sair</span>
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-container">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <h1 id="page-title">Dashboard</h1>
            </div>
            <div class="header-right">
                <!-- Notifications -->
                <div class="notification-container">
                    <button class="notification-btn" id="notificationBtn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>

                </div>


                <!-- User Profile -->
                <div class="user-profile">
                    <img src="https://i.pravatar.cc/150?img=1" alt="Foto do paciente" class="profile-image">
                    <div class="profile-info">
                        <p class="profile-name"><?= $_SESSION['nome'] ?></p>
                        <p class="profile-status">Paciente</p>
                    </div>
                </div>

                <!-- Dark Mode Toggle -->
                <button class="dark-mode-toggle" id="darkModeToggle">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </header>

        <!-- Content Sections -->
        <div class="content">
            <!-- Dashboard Section -->
            <section id="dashboard-section" class="section active">
                <!-- Welcome Card -->
                <div class="welcome-card">
                    <div class="welcome-content">
                        <h2>Bem-vindo, <?= $_SESSION['nome'] ?>!</h2>
                        <p>Acompanhe suas consultas e saúde de forma fácil e segura.</p>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-content">
                            <?php $stmtPaciente = $pdo->prepare("
                            SELECT COUNT(*) as quant from consultas
                            INNER JOIN pacientes p ON p.id = c.paciente_id
                            INNER JOIN usuarios u ON u.id = p.usuario_id
                            WHERE DATE(data_hora_inicio) AND u.nome LIKE ? LIMIT 1"); ?>
                            <?php $num_paciente = $stmtPaciente->execute() ?>
                            <p class="stat-label">Próxima Consulta</p>
                            <p class="stat-value">Hoje 15:00</p>
                            <small>Dr. Carlos Silva</small>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <div class="stat-content">
                            <?php $stmtConsultaMes = $pdo->prepare("
                            SELECT COUNT(*) as quant from consultas
                            INNER JOIN pacientes p ON p.id = c.paciente_id
                            INNER JOIN usuarios u ON u.id = p.usuario_id
                            WHERE DATE(data_hora_inicio) AND u.nome LIKE ? LIMIT 1");
                            ?>
                            <?php $num_mes = $stmtConsultaMes->execute([$_SESSION['nome']]) ?>
                            <p class="stat-label">Consultas este mês</p>
                            <p class="stat-value"><?= $num_mes ?></p>
                            <small>2 realizadas</small>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Exames Pendentes</p>
                            <p class="stat-value">1</p>
                            <small>Resultado em 2 dias</small>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Receitas Ativas</p>
                            <p class="stat-value">3</p>
                            <small>Todas válidas</small>
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->


                <!-- Recent Notifications -->
                <div class="recent-activity">
                    <h3>Atividade Recente</h3>
                    <div class="activity-list">
                        <div class="activity-item">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <p>Consulta realizada com Dra. Maria</p>
                                <small>Há 2 dias</small>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-file-upload"></i>
                            <div>
                                <p>Exame enviado para análise</p>
                                <small>Há 5 dias</small>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-prescription-bottle"></i>
                            <div>
                                <p>Receita atualizada</p>
                                <small>Há 1 semana</small>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Agendamento Section -->
            <section id="agendamento-section" class="section">
                <div class="agendamento-container">
                    <div class="agendamento-calendar">
                        <h2>Selecione a Data</h2>

                        <!-- Navegação entre meses -->
                        <div class="calendar-nav">
                            <button type="button" onclick="prevMonth()">&lt;</button>
                            <span id="calendarMonthLabel"></span>
                            <button type="button" onclick="nextMonth()">&gt;</button>
                        </div>

                        <div class="calendar" id="calendar"></div>
                    </div>

                    <div class="agendamento-form">
                        <h2>Agendar Consulta</h2>

                        <div class="form-group">
                            <label>Data Selecionada</label>

                            <!-- 👁️ DATA BONITA (para o utilizador) -->
                            <input type="text" id="selectedDateDisplay" readonly placeholder="Selecione uma data">

                            <!-- 🧠 DATA REAL (para a base de dados) -->
                            <input type="hidden" id="selectedDate">
                        </div>

                        <div class="form-group">
                            <label>Especialidade</label>
                            <select id="especialidadeConsulta">
                                <option value="">Escolha uma especialidade</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Motivo da Consulta</label>

                            <textarea class="motivoConsulta" id="motivoConsulta" placeholder="Descreva o motivo da consulta"></textarea>
                        </div>

                        <!-- Campo "Médico" removido -->
                        <!-- Campo "Horário Disponível" removido -->

                        <button class="btn btn-primary" onclick="solicitarConsulta()">
                            <i class="fas fa-calendar-check"></i> Solicitar Consulta
                        </button>
                    </div>
                </div>
            </section>



            <!-- Consultas Agendadas Section -->
            <section id="consultas-section" class="section">
                <h2>Minhas Consultas Agendadas</h2>

                <div class="table-wrapper">
                    <table class="consultas-table">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nome</th>
                                <th>Especialidade</th>
                                <th>Data Solicitação</th>
                                <th>Data Aceite</th>
                                <th>Link Teleconsulta</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="consultasTableBody">
                            <!-- preenchido via JS -->
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Histórico Section -->
            <section id="historico-section" class="section">
                <h2>Histórico de Consultas</h2>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <p>Março 2024</p>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-card">
                                <h4>Consulta com Dr. Carlos Silva - Cardiologia</h4>
                                <p>Data: 08 de Março de 2024</p>
                                <p class="diagnosis"><strong>Diagnóstico:</strong> Pressão arterial elevada</p>
                                <p class="treatment"><strong>Tratamento:</strong> Repouso e medicação</p>
                                <div class="timeline-actions">
                                    <button class="btn btn-sm btn-secondary">Ver Receita</button>
                                    <button class="btn btn-sm btn-secondary">Baixar Relatório</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-date">
                            <p>Fevereiro 2024</p>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-card">
                                <h4>Consulta com Dra. Maria Santos - Dermatologia</h4>
                                <p>Data: 20 de Fevereiro de 2024</p>
                                <p class="diagnosis"><strong>Diagnóstico:</strong> Dermatite atópica</p>
                                <p class="treatment"><strong>Tratamento:</strong> Creme dermatológico</p>
                                <div class="timeline-actions">
                                    <button class="btn btn-sm btn-secondary">Ver Receita</button>
                                    <button class="btn btn-sm btn-secondary">Baixar Relatório</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-date">
                            <p>Janeiro 2024</p>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-card">
                                <h4>Consulta com Dr. João Costa - Clínica Geral</h4>
                                <p>Data: 15 de Janeiro de 2024</p>
                                <p class="diagnosis"><strong>Diagnóstico:</strong> Resfriado comum</p>
                                <p class="treatment"><strong>Tratamento:</strong> Repouso e líquidos</p>
                                <div class="timeline-actions">
                                    <button class="btn btn-sm btn-secondary">Ver Receita</button>
                                    <button class="btn btn-sm btn-secondary">Baixar Relatório</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Perfil Section -->
            <section id="perfil-section" class="section">
                <div class="profile-container">
                    <div class="profile-header">
                        <img src="https://i.pravatar.cc/150?img=1" alt="Foto do paciente" class="large-avatar">
                        <div class="profile-header-info">
                            <h2>João Silva</h2>
                            <p>Paciente desde 2020</p>
                        </div>
                        <button class="btn btn-secondary" id="editProfileBtn">
                            <i class="fas fa-edit"></i> Editar Perfil
                        </button>
                    </div>

                    <div class="profile-content">
                        <div class="profile-section">
                            <h3>Informações Pessoais</h3>
                            <div class="profile-grid">
                                <div class="profile-item">
                                    <label>Nome Completo</label>
                                    <p>João da Silva Santos</p>
                                </div>
                                <div class="profile-item">
                                    <label>Data de Nascimento</label>
                                    <p>15 de Maio de 1990</p>
                                </div>
                                <div class="profile-item">
                                    <label>Idade</label>
                                    <p>34 anos</p>
                                </div>
                                <div class="profile-item">
                                    <label>Telefone</label>
                                    <p>(11) 9 8765-4321</p>
                                </div>
                                <div class="profile-item">
                                    <label>Email</label>
                                    <p>joao.silva@email.com</p>
                                </div>
                                <div class="profile-item">
                                    <label>Gênero</label>
                                    <p>Masculino</p>
                                </div>
                            </div>
                        </div>

                        <div class="profile-section">
                            <h3>Informações Médicas</h3>
                            <div class="profile-grid">
                                <div class="profile-item">
                                    <label>Tipo Sanguíneo</label>
                                    <p>O+</p>
                                </div>
                                <div class="profile-item">
                                    <label>Altura</label>
                                    <p>1,80m</p>
                                </div>
                                <div class="profile-item">
                                    <label>Peso</label>
                                    <p>80 kg</p>
                                </div>
                                <div class="profile-item">
                                    <label>Alergias</label>
                                    <p>Nenhuma conhecida</p>
                                </div>
                            </div>

                            <div class="medical-history">
                                <h4>Histórico Médico</h4>
                                <ul>
                                    <li>Hipertensão (controlada)</li>
                                    <li>Histórico familiar de diabetes</li>
                                    <li>Apendicectomia (2015)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Configurações Section -->
            <section id="configuracoes-section" class="section">
                <h2>Configurações</h2>
                <div class="settings-container">
                    <div class="settings-section">
                        <h3>Segurança</h3>
                        <div class="setting-item">
                            <div>
                                <p class="setting-label">Alterar Senha</p>
                                <p class="setting-description">Atualize sua senha de forma segura</p>
                            </div>
                            <button class="btn btn-secondary btn-sm" id="changePasswordBtn">
                                Alterar
                            </button>
                        </div>
                    </div>

                    <div class="settings-section">
                        <h3>Aparência</h3>
                        <div class="setting-item">
                            <div>
                                <p class="setting-label">Modo Escuro</p>
                                <p class="setting-description">Ative o modo escuro para reduzir o cansaço visual</p>
                            </div>
                            <div class="toggle-switch">
                                <input type="checkbox" id="darkModeToggleSetting" class="toggle-input">
                                <label for="darkModeToggleSetting" class="toggle-label"></label>
                            </div>
                        </div>
                    </div>

                    <div class="settings-section">
                        <h3>Notificações</h3>
                        <div class="setting-item">
                            <div>
                                <p class="setting-label">Notificações de Consultas</p>
                                <p class="setting-description">Receber alertas sobre consultas marcadas</p>
                            </div>
                            <div class="toggle-switch">
                                <input type="checkbox" id="appointmentNotifications" class="toggle-input" checked>
                                <label for="appointmentNotifications" class="toggle-label"></label>
                            </div>
                        </div>
                        <div class="setting-item">
                            <div>
                                <p class="setting-label">Notificações de Mensagens</p>
                                <p class="setting-description">Receber alertas de novos mensagens</p>
                            </div>
                            <div class="toggle-switch">
                                <input type="checkbox" id="messageNotifications" class="toggle-input" checked>
                                <label for="messageNotifications" class="toggle-label"></label>
                            </div>
                        </div>
                        <div class="setting-item">
                            <div>
                                <p class="setting-label">Notificações de Exames</p>
                                <p class="setting-description">Receber alertas quando resultados estiverem prontos</p>
                            </div>
                            <div class="toggle-switch">
                                <input type="checkbox" id="examNotifications" class="toggle-input" checked>
                                <label for="examNotifications" class="toggle-label"></label>
                            </div>
                        </div>
                    </div>


                </div>
            </section>

            <!-- Suporte Section -->
            <section id="suporte-section" class="section">
                <h2>Suporte e Ajuda</h2>
                <div class="support-container">
                    <div class="faq-section">
                        <h3>Perguntas Frequentes</h3>
                        <div class="faq-list">
                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>Como agendar uma consulta?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>Para agendar uma consulta, acesse a seção "Agendamento" no menu lateral, selecione a data desejada, escolha a especialidade e o médico de sua preferência.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>Posso cancelar uma consulta?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>Sim, você pode cancelar uma consulta até 24 horas antes do horário marcado. Acesse "Consultas Agendadas" e clique em "Cancelar".</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>Como enviar documentos médicos?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>Você pode enviar documentos através da seção "Chat" ao conversar com seu médico, ou anexar à sua consulta agendada.</p>
                                </div>
                            </div>

                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>Minha consulta é confidencial?</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>Sim, todas as suas consultas e dados médicos são totalmente confidenciais e protegidos por legislação de privacidade médica.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="support-contact">
                        <h3>Entre em Contato</h3>
                        <p>Não encontrou o que procurava? Nossa equipe de suporte está pronta para ajudar!</p>
                        <form class="support-form" id="supportForm">
                            <div class="form-group">
                                <label>Assunto</label>
                                <input type="text" placeholder="Descreva brevemente o assunto" required>
                            </div>
                            <div class="form-group">
                                <label>Mensagem</label>
                                <textarea placeholder="Explique seu problema em detalhes..." rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Enviar Mensagem
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Emergency Modal -->
    <div class="modal" id="notificationDropdown">
        <div class="modal-content">
            <button class="modal-close">
                <i class="fas fa-times"></i>
            </button>
            <div class="emergency-options">
                <h2>Notificações</h2>
                <button class="emergency-option">
                    <i class="fas fa-comments"></i>
                    <span>Solicitação de consulta confirmada</span>
                    <p>Converse com um médico de prontidão</p>
                </button>
                <button class="emergency-option">
                    <i class="fas fa-phone"></i>
                    <span>Solicitação de consulta confirmada</span>
                    <p>Converse com um médico de prontidão</p>
                </button>
                <button class="emergency-option">
                    <i class="fas fa-ambulance"></i>
                    <span>Solicitação de consulta confirmada</span>
                    <p>Converse com um médico de prontidão</p>
                </button>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal" id="passwordModal">
        <div class="modal-content">
            <button class="modal-close">
                <i class="fas fa-times"></i>
            </button>
            <h2>Alterar Senha</h2>
            <form id="passwordForm">
                <div class="form-group">
                    <label>Senha Atual</label>
                    <input type="password" placeholder="Digite sua senha atual" required>
                </div>
                <div class="form-group">
                    <label>Nova Senha</label>
                    <input type="password" placeholder="Digite sua nova senha" required>
                </div>
                <div class="form-group">
                    <label>Confirmar Senha</label>
                    <input type="password" placeholder="Confirme sua nova senha" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-lock"></i> Atualizar Senha
                </button>
            </form>
        </div>
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
                frameborder="0"
                allow="camera; microphone; fullscreen">
            </iframe>

        </div>

    </div>
    <!-- Script -->
    <script src="assets/js/paciente.js"></script>
</body>

</html>