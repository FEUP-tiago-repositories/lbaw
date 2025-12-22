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
    const baseId = containerId.replace('-scroll-container', '');
    const gradientLeft = document.getElementById(`${baseId}-gradient-left`);
    const gradientRight = document.getElementById(`${baseId}-gradient-right`);
    const btnScrollLeft = document.getElementById(`${baseId}-scroll-left`);
    const btnScrollRight = document.getElementById(`${baseId}-scroll-right`);

    // Função para atualizar visibilidade dos gradientes e setas
    function updateScrollIndicators() {
        const scrollLeft = container.scrollLeft;
        const scrollWidth = container.scrollWidth;
        const clientWidth = container.clientWidth;

        // Verifica se há overflow (conteúdo maior que o container)
        const hasOverflow = scrollWidth > clientWidth;

        // Verifica se pode fazer scroll para cada lado
        const canScrollLeft = scrollLeft > 0;
        const canScrollRight = scrollLeft < (scrollWidth - clientWidth - 1);

        // Atualiza gradiente e seta esquerda
        if (gradientLeft) {
            gradientLeft.style.opacity = (hasOverflow && canScrollLeft) ? '1' : '0';
        }
        if (btnScrollLeft) {
            btnScrollLeft.style.opacity = (hasOverflow && canScrollLeft) ? '1' : '0';
            btnScrollLeft.style.pointerEvents = (hasOverflow && canScrollLeft) ? 'auto' : 'none';
        }

        // Atualiza gradiente e seta direita
        if (gradientRight) {
            gradientRight.style.opacity = (hasOverflow && canScrollRight) ? '1' : '0';
        }
        if (btnScrollRight) {
            btnScrollRight.style.opacity = (hasOverflow && canScrollRight) ? '1' : '0';
            btnScrollRight.style.pointerEvents = (hasOverflow && canScrollRight) ? 'auto' : 'none';
        }
    }

    // Função para scroll suave
    function smoothScroll(direction) {
        const scrollAmount = 400;
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

    // Aguarda imagens carregarem
    const images = container.querySelectorAll('img');
    let loadedImages = 0;

    if (images.length > 0) {
        images.forEach(img => {
            if (img.complete) {
                loadedImages++;
            } else {
                img.addEventListener('load', () => {
                    loadedImages++;
                    if (loadedImages === images.length) {
                        updateScrollIndicators();
                    }
                });
            }
        });

        // Se todas as imagens já estão carregadas
        if (loadedImages === images.length) {
            updateScrollIndicators();
        }
    } else {
        // Sem imagens, atualiza imediatamente
        updateScrollIndicators();
    }

    // Backup: força atualização após 500ms
    setTimeout(() => {
        updateScrollIndicators();
    }, 500);
}

// Auto-inicializa todos os containers de scroll na página
document.addEventListener('DOMContentLoaded', function() {
    const scrollContainers = document.querySelectorAll('[id$="-scroll-container"]');

    scrollContainers.forEach(container => {
        initHorizontalScroll(container.id);
    });
});
