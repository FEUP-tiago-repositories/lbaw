const favoriteBtn = document.getElementById('favorite-btn');

if (favoriteBtn) {
    favoriteBtn.addEventListener('click', async function() {
        const spaceId = this.dataset.spaceId;
        const heartSvg = this.querySelector('svg');

        try {
            const response = await fetch(`/spaces/${spaceId}/favorite`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (response.ok) {
                // Toggle heart appearance
                if (data.is_favorite) {
                    heartSvg.classList.remove('fill-none', 'stroke-gray-400');
                    heartSvg.classList.add('fill-red-500', 'stroke-red-500');
                } else {
                    heartSvg.classList.remove('fill-red-500', 'stroke-red-500');
                    heartSvg.classList.add('fill-none', 'stroke-gray-400');
                }
            } else {
                alert(data.error || 'Failed to update favorite status');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });
}