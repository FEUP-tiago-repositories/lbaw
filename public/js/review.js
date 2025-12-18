function showReviewForm() {

    // hide button
    const btnContainer = document.getElementById("write-review-btn-container");
    const firstReviewBtn = document.getElementById("write-first-review-btn");
    if (btnContainer) btnContainer.classList.add("hidden");
    if (firstReviewBtn) firstReviewBtn.classList.add("hidden");


    document.getElementById("review-form-container").classList.remove("hidden");

    // scroll to forms
    document.getElementById("review-form-container").scrollIntoView({
        behavior: "smooth",
        block: "center",
    });
}

function hideReviewForm() {

    // show button
    const btnContainer = document.getElementById("write-review-btn-container");
    const firstReviewBtn = document.getElementById("write-first-review-btn");
    if (btnContainer) btnContainer.classList.remove("hidden");
    if (firstReviewBtn) firstReviewBtn.classList.remove("hidden");

    // hide form
    document.getElementById('review-form-container').classList.add('hidden');
}
