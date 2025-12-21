function toggleModal() {
    const modal = document.getElementById('helpModal');
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
    } else {
        modal.classList.add('hidden');
    }
}

const help_modal = document.getElementById("helpModal");
if (help_modal) {
    help_modal.addEventListener("click", function (e) {
        if (e.target === this) {
            toggleModal();
        }
    });
}