function setRating(category,rating){
    document.getElementById(`${category}-label`).value = rating;

    // update label
    document.getElementById(`${category}-label`).textContent = `${rating}/5`;

    // update star colors
    const stars = document.querySelectorAll(`#${category}-stars svg`);
    stars.forEach((star,index) => {
        if (index < rating){
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}

document.getElementById('review-text').addEventListener('input',function() {
    const count = this.value.length;
    document.getElementById('char-count').textContent = `${count}/500 characters`;
})