function openDiscountModal() {
    document.getElementById('discountsListModal').classList.remove('hidden');
}

function closeDiscountModal() {
    document.getElementById('discountsListModal').classList.add('hidden');
}

function copyToClipboard(text, element) {
    navigator.clipboard.writeText(text).then(() => {
        const originalColor = element.style.color;
        element.style.color = '#059669';
        element.innerText = 'COPIED!';
        setTimeout(() => {
            element.innerText = text;
            element.style.color = originalColor;
        }, 1000);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('discountModal');
    const form = document.getElementById('discountForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    window.onclick = function(e) {
        if (e.target == modal) closeModal();
    }

    window.openModal = function() {
        form.reset();
        methodField.innerHTML = '';
        modalTitle.innerText = 'Create New Discount';
        form.action = window.DiscountConfig.routes.store;
        modal.classList.remove('hidden');
    };

    window.openEditModal = function(discount) {
        modalTitle.innerText = 'Edit Discount';

        let updateUrl = window.DiscountConfig.routes.update.replace(':id', discount.id);
        form.action = updateUrl;

        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('inputSpaceId').value = discount.space_id;
        document.getElementById('inputCode').value = discount.code || '';
        document.getElementById('inputPercentage').value = discount.percentage;
        
        if(discount.start_date) {
            document.getElementById('inputStart').value = discount.start_date.slice(0, 16);
        }
        if(discount.end_date) {
            document.getElementById('inputEnd').value = discount.end_date.slice(0, 16);
        }

        modal.classList.remove('hidden');
    };

    window.closeModal = function() {
        modal.classList.add('hidden');
    };

    window.generateCode = function() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        let result = '';
        for (let i = 0; i < 8; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('inputCode').value = result;
    };
});