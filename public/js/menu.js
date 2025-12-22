// Inicializar menu hambúrguer
function initializeMenu() {
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            // Toggle menu
            navMenu.classList.toggle('translate-x-full');
            navMenu.classList.toggle('translate-x-0');

            // Animar hamburger
            const spans = hamburger.querySelectorAll('span');
            if (navMenu.classList.contains('translate-x-0')) {
                // Menu aberto - transformar em X
                spans[0].style.transform = 'rotate(45deg) translateY(8px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translateY(-8px)';
            } else {
                // Menu fechado - voltar às 3 linhas
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        });

        // Fechar menu ao clicar em link
        const menuLinks = navMenu.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.add('translate-x-full');
                navMenu.classList.remove('translate-x-0');

                // Reset hamburger
                const spans = hamburger.querySelectorAll('span');
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            });
        });

        console.log('Menu inicializado com sucesso');
    } else {
        console.error('Elementos do menu não encontrados');
    }
}

// Inicializar quando o DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeMenu);
} else {
    initializeMenu();
}
