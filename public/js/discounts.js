if (typeof window.activeDiscountCode === 'undefined') {
    window.activeDiscountCode = null;
}

window.applyPromoCode = function() {
    const codeInput = document.getElementById('promoCodeInput');
    const messageEl = document.getElementById('promoMessage');
    
    const config = window.PaymentConfig || {};

    if (!codeInput || !codeInput.value.trim()) {
        messageEl.textContent = 'Insira um código.';
        messageEl.classList.remove('hidden', 'text-green-600');
        messageEl.classList.add('text-red-600');
        return;
    }

    let scheduleId = 0;
    const scheduleInput = document.querySelector('input[name="schedule_id"]');
    
    if (scheduleInput && scheduleInput.value) {
        scheduleId = scheduleInput.value;
    } else if (window.selectedScheduleId) {
        scheduleId = window.selectedScheduleId;
    } else if (window.bookingData && window.bookingData.scheduleId) {
        scheduleId = window.bookingData.scheduleId;
    }

    const widget = document.querySelector('.booking-widget');
    const spaceId = widget ? widget.dataset.spaceId : null;
    const durationInput = document.getElementById('durationInput'); 
    const personsInput = document.getElementById('personsInput');
    
    const duration = durationInput ? durationInput.value : 60;
    const persons = personsInput ? personsInput.value : 1;
    
    messageEl.classList.add('hidden');

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch(config.routes.checkDiscount, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            space_id: spaceId,
            schedule_id: scheduleId,
            duration: duration,
            persons: persons,
            code: codeInput.value
        })
    })
    .then(response => {
        if (!response.ok) return response.text().then(text => { throw new Error(text) });
        return response.json();
    })
    .then(data => {
        messageEl.classList.remove('hidden');
        
        if (data.valid) {
            window.activeDiscountCode = codeInput.value;
            
            messageEl.textContent = data.message;
            messageEl.className = 'text-sm mt-2 text-green-600';
            
            if(document.getElementById('originalPriceDisplay')) {
                document.getElementById('originalPriceDisplay').textContent = '€' + parseFloat(data.original_price).toFixed(2);
                document.getElementById('originalPriceDisplay').classList.remove('hidden');
            }
            
            if(document.getElementById('paymentAmount')) {
                document.getElementById('paymentAmount').textContent = '€' + parseFloat(data.final_price).toFixed(2);
                document.getElementById('paymentAmount').classList.add('text-green-700');
            }

        } else {
            window.activeDiscountCode = null;
            throw new Error(data.message || 'Invalid code');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        window.activeDiscountCode = null;

        let msg = 'Error applying code.';
        try {
            if (error.message && error.message.startsWith('{')) {
                 const errorObj = JSON.parse(error.message);
                 if(errorObj.message) msg = errorObj.message;
            } else if (error.message) {
                 msg = error.message;
            }
        } catch(e) {}

        messageEl.textContent = msg;
        messageEl.className = 'text-sm mt-2 text-red-600';
        messageEl.classList.remove('hidden');
    });
};

document.addEventListener('DOMContentLoaded', function() {
    const timeGrid = document.getElementById('timeGrid');
    if (timeGrid) {
        timeGrid.addEventListener('click', function(e) {
            const btn = e.target.closest('button');
            if (btn) {
                let id = btn.dataset.id;
                if (!id && btn.getAttribute('onclick')) {
                    const match = btn.getAttribute('onclick').match(/\d+/);
                    if (match) id = match[0];
                }
                if (id) {
                    window.selectedScheduleId = id;
                }
            }
        });
    }
});

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