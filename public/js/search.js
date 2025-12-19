/**
 * search.js
 * Lógica para manipulação do formulário de pesquisa de espaços
 */

// Função para limpar todos os inputs do formulário
function clearFilters() {
    const form = document.getElementById('filterForm');
    
    if (!form) return;

    // Limpa inputs de texto, data e números
    const inputs = form.querySelectorAll('input[type="text"], input[type="date"], input[type="number"]');
    inputs.forEach(input => input.value = '');

    // Limpa checkboxes
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = false);

    // Opcional: Submeter o formulário imediatamente após limpar
    // form.submit(); 
    
    // OU apenas recarregar a página sem query params
    window.location.href = form.action;
}

// Se quiseres inicializar Flatpickr (calendário mais bonito)
// Requer: <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
// Requer: <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.querySelector('input[name="date"]');
    if (dateInput && typeof flatpickr !== 'undefined') {
        flatpickr(dateInput, {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            minDate: "today",
            locale: "pt" // Requer ficheiro de locale pt
        });
    }
});