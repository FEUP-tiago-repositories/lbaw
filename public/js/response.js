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