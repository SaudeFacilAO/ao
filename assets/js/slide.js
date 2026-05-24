// ======================= CARROSSEL JS =======================
const slidesContainer = document.getElementById('slidesContainer');
const slides = document.querySelectorAll('.hospital-slide');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const dotsContainer = document.getElementById('dotsContainer');

let currentIndex = 0;
const totalSlides = slides.length;

// Função para atualizar a posição do carrossel
function updateCarousel() {
    const slideWidth = slides[0].clientWidth;
    slidesContainer.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
    
    // Atualizar dots
    const dots = document.querySelectorAll('.dot');
    dots.forEach((dot, idx) => {
        if(idx === currentIndex) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
}

// Criar os dots (indicadores)
function createDots() {
    dotsContainer.innerHTML = '';
    for(let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if(i === currentIndex) dot.classList.add('active');
        dot.addEventListener('click', () => {
            currentIndex = i;
            updateCarousel();
        });
        dotsContainer.appendChild(dot);
    }
}

// Evento de resize para recalcular posição (evita desalinhamento)
let resizeTimeout;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        updateCarousel();
    }, 100);
});

// Navegação
function nextSlide() {
    currentIndex = (currentIndex + 1) % totalSlides;
    updateCarousel();
}

function prevSlide() {
    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
    updateCarousel();
}

nextBtn.addEventListener('click', nextSlide);
prevBtn.addEventListener('click', prevSlide);

// Inicializar dots e garantir que o carrossel reaja à largura inicial
createDots();

// Ajustar quando a página carregar e quando imagens carregarem para posição correta
window.addEventListener('load', () => {
    updateCarousel();
    // Observer para caso alguma imagem altere o layout
    const images = document.querySelectorAll('.slide-img-top img');
    let loadedCount = 0;
    if(images.length === 0) updateCarousel();
    images.forEach(img => {
        if(img.complete) {
            loadedCount++;
            if(loadedCount === images.length) updateCarousel();
        } else {
            img.addEventListener('load', () => {
                updateCarousel();
            });
        }
    });
});

// Opcional: suporte a touch para mobile (simples)
let touchStartX = 0;
let touchEndX = 0;
const carouselWrapper = document.querySelector('.carousel-wrapper');

carouselWrapper.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
});

carouselWrapper.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    const diff = touchEndX - touchStartX;
    if(Math.abs(diff) > 50) {
        if(diff > 0) {
            prevSlide();
        } else {
            nextSlide();
        }
    }
});