document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('response-text');
    const counter = document.getElementById('response-char-count');
    
    if (textarea && counter) {
        textarea.addEventListener('input', function() {
            counter.textContent = `${this.value.length}/500 chars`;
        });
    }
});