/**
 * Saúde Fácil - Script Principal
 * Gerencia toda a interatividade da plataforma
 */

// ============================================
// INICIALIZAÇÃO E CONFIGURAÇÃO
// ============================================

const API = "assets/js/backpublic/";

// Verificar dark mode ao carregar
document.addEventListener('DOMContentLoaded', () => {
    initializePage();
    loadDarkModePreference();
    carregarMedicosPublic();
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

//««««««««««««««««««««««««««««««««««««««««Formatar turno
function formatarTextoEsp(esp) {

    switch (esp) {

        case "Neurocirurgiã":
            return "Especializada em tratamentos neurológicos com atendimento humanizado.";

        case "Anestesiologista":
            return "Profissional experiente focada na segurança e conforto dos pacientes.";

        case "Cardiologista":
            return "Especialista em diagnóstico e acompanhamento da saúde cardiovascular.";

        case "Diretor Médico":
            return "Especialista comprometido em oferecer atendimento médico de qualidade e confiança.";

        default:
            return "Especialista comprometido em oferecer atendimento médico de qualidade e confiança.";
    }
}

//«««««««««««««««««Listar especialidade
async function carregarMedicosPublic() {

    try {

        const res = await fetch(API + "dados.php");

        const data = await res.json();

        const medicos = document.getElementById("medicos");

        data.data.forEach(med => {

            medicos.innerHTML = `
            
      <!-- Médico 4 -->
      <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">

        <div class="team-member d-flex align-items-start">

          <div class="pic">
            <img src="${med.foto}assets/img/doctors/medica-2.png" class="img-fluid" alt="">
          </div>

          <div class="member-info">

            <h4>${med.medico}</h4>

            <span>${med.especialidade}</span>

            <p>
              ${formatarTextoEsp(med.especialidade)}
            </p>

            <div class="social">
              <a href=""><i class="bi bi-twitter-x"></i></a>
              <a href=""><i class="bi bi-facebook"></i></a>
              <a href=""><i class="bi bi-instagram"></i></a>
              <a href=""><i class="bi bi-linkedin"></i></a>
            </div>

          </div>

        </div>

      </div>
      <!-- End Team Member -->
        `;
        });

    } catch (erro) {

        console.error(erro);
    }
}
