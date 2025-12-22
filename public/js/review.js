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

function setRating(category, rating) {
    // update hidden input
    document.getElementById(`${category}-rating`).value = rating;

    // update label
    document.getElementById(`${category}-label`).textContent = `${rating}/5`;

    // update star colors
    const stars = document.querySelectorAll(`#${category}-stars svg`);
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove("text-gray-300");
            star.classList.add("text-yellow-400");
        } else {
            star.classList.remove("text-yellow-400");
            star.classList.add("text-gray-300");
        }
    });
}

const reviewText = document.getElementById("review-text");
if (reviewText) {
    document;
    reviewText.addEventListener("input", function () {
        const count = this.value.length;
        document.getElementById(
            "char-count"
        ).textContent = `${count}/500 characters`;
    });
}