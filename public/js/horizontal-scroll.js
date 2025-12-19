/**
 * Horizontal Scroll with Dynamic Gradients and Arrows
 * Usage: Call initHorizontalScroll(containerId) for each scroll container
 */

function initHorizontalScroll(containerId) {
    const container = document.getElementById(containerId);
    if (!container) {
        console.log('Container não encontrado:', containerId);
        return;
    }

    // Encontra os elementos relacionados baseado no ID
    const baseId = containerId.replace('-scroll-container', ''); // ✅ CORRIGIDO: era '-container'

    const gradientLeft = document.getElementById(`${baseId}-gradient-left`);
    const gradientRight = document.getElementById(`${baseId}-gradient-right`);
    const btnScrollLeft = document.getElementById(`${baseId}-scroll-left`); // ✅ Renomeado para evitar conflito
    const btnScrollRight = document.getElementById(`${baseId}-scroll-right`);

    // Função para atualizar visibilidade dos gradientes e setas
    function updateScrollIndicators() {
        const scrollLeft = container.scrollLeft;
        const scrollWidth = container.scrollWidth;
        const clientWidth = container.clientWidth;

        // Verifica se pode fazer scroll
        const canScrollLeft = scrollLeft > 0;
        const canScrollRight = scrollLeft < (scrollWidth - clientWidth - 1);

        // Atualiza gradiente e seta esquerda
        if (gradientLeft) {
            gradientLeft.style.opacity = canScrollLeft ? '1' : '0';
        }
        if (btnScrollLeft) {
            btnScrollLeft.style.opacity = canScrollLeft ? '1' : '0';
            btnScrollLeft.style.pointerEvents = canScrollLeft ? 'auto' : 'none';
        }

        // Atualiza gradiente e seta direita
        if (gradientRight) {
            gradientRight.style.opacity = canScrollRight ? '1' : '0';
        }
        if (btnScrollRight) {
            btnScrollRight.style.opacity = canScrollRight ? '1' : '0';
            btnScrollRight.style.pointerEvents = canScrollRight ? 'auto' : 'none';
        }
    }

    // Função para scroll suave
    function smoothScroll(direction) {
        const scrollAmount = 400; // Ajustável: quantos pixels scrollar
        const newPosition = container.scrollLeft + (direction === 'right' ? scrollAmount : -scrollAmount);

        container.scrollTo({
            left: newPosition,
            behavior: 'smooth'
        });
    }

    // Event listeners
    container.addEventListener('scroll', updateScrollIndicators);

    if (btnScrollLeft) {
        btnScrollLeft.addEventListener('click', () => {
            smoothScroll('left');
        });
    }

    if (btnScrollRight) {
        btnScrollRight.addEventListener('click', () => {
            smoothScroll('right');
        });
    }

    // Atualiza no redimensionamento da janela
    window.addEventListener('resize', updateScrollIndicators);

    // Inicializa
    updateScrollIndicators();
}

// Auto-inicializa todos os containers de scroll na página
document.addEventListener('DOMContentLoaded', function() {
    // Procura por todos os elementos com IDs que terminam em '-scroll-container'
    const scrollContainers = document.querySelectorAll('[id$="-scroll-container"]');

    scrollContainers.forEach(container => {
        initHorizontalScroll(container.id);
    });
});
