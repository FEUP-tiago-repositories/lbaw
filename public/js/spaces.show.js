document.addEventListener("DOMContentLoaded", function () {
    const aboutTab = document.getElementById("about-tab");
    const reviewsTab = document.getElementById("reviews-tab");
    const aboutContent = document.getElementById("about-content");
    const reviewsContent = document.getElementById("reviews-content");

    // Function to show About section
    function showAbout() {
        // CSS behaviour for when About is selected
        aboutTab.classList.add(
            "text-green-700",
            "border-b-2",
            "border-green-700"
        );
        aboutTab.classList.remove("hover:text-green-700");
        reviewsTab.classList.remove(
            "text-green-700",
            "border-b-2",
            "border-green-700"
        );
        reviewsTab.classList.add("hover:text-green-700");

        aboutContent.classList.remove("hidden");
        reviewsContent.classList.add("hidden");
    }

    //Function to show Reviews section
    function showReviews() {
        reviewsTab.classList.add(
            "text-green-700",
            "border-b-2",
            "border-green-700"
        );
        reviewsTab.classList.remove("hover:text-green-700");
        aboutTab.classList.remove(
            "text-green-700",
            "border-b-2",
            "border-green-700"
        );
        aboutTab.classList.add("hover:text-green-700");

        reviewsContent.classList.remove("hidden");
        aboutContent.classList.add("hidden");
    }

    //Event listeners
    aboutTab.addEventListener("click", showAbout);
    reviewsTab.addEventListener("click", showReviews);
});
