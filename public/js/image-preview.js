document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[type="file"][data-preview]')
        .forEach(input => {

            input.addEventListener('change', () => {
                const previewId = input.dataset.preview;
                const preview = document.getElementById(previewId);

                if (!preview || !input.files || !input.files[0]) return;

                const file = input.files[0];

                const reader = new FileReader();
                reader.onload = e => preview.src = e.target.result;
                reader.readAsDataURL(file);
            });
        });
});
