function showResponseForm() {

    // hide button
    const btnContainer = document.getElementById("write-response-btn-container");
    if (btnContainer) btnContainer.classList.add("hidden");


    document.getElementById("response-form-container").classList.remove("hidden");

    // scroll to forms
    document.getElementById("response-form-container").scrollIntoView({
        behavior: "smooth",
        block: "center",
    });
}

function hideResponseForm() {

    // show button
    const btnContainer = document.getElementById("write-response-btn-container");
    if (btnContainer) btnContainer.classList.remove("hidden");


    // hide form
    document.getElementById('response-form-container').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('response-text');
    const counter = document.getElementById('response-char-count');

    if (textarea && counter) {
        textarea.addEventListener('input', function() {
            counter.textContent = `${this.value.length}/500 chars`;
        });
    }
});