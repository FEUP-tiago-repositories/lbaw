function toggleModal() {
    const modal = document.getElementById('helpModal');
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
    } else {
        modal.classList.add('hidden');
    }
}

document.getElementById('helpModal').addEventListener('click', function(e) {
    if (e.target === this) {
        toggleModal();
    }
});