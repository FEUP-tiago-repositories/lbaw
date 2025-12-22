document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[type="file"][data-gallery-preview]')
        .forEach(input => {
            input.addEventListener('change', () => {
                const container = document.getElementById(input.dataset.galleryPreview);
                if (!container) return;
            
                container.innerHTML = '';
                
                Array.from(input.files).forEach(file => {
                    if (!file.type.startsWith('image/')) return;
                    
                    const reader = new FileReader();
                    reader.onload = e => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-32 object-cover rounded-lg shadow';
                        container.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });
        });

    document.querySelectorAll('input[type="checkbox"][data-delete-target]')
        .forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const targetId = checkbox.dataset.deleteTarget;
                const target = document.getElementById(targetId);
                if (!target) return;
                
                if (checkbox.checked) {
                    target.classList.add('opacity-30', 'grayscale');
                } else {
                    target.classList.remove('opacity-30', 'grayscale');
                }
            });
        });
});