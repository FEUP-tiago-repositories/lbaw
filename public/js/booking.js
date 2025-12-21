// ============================================
// ESTADO GLOBAL
// ============================================
let state = {
    spaceId: null,
    selectedDate: null,
    scheduleId: null,
    time: null,
    duration: 30,
    persons: 1,
    bookingId: null,
    payment: null,
    customerId: null,
    userId: null,  // User ID for redirects
    scheduleDuration: 30,
    discountCode: null
};

let isEditMode = false;
let originalBookingData = null;
let currentDate = new Date();
let selectedPaymentMethod = null;

// ============================================
// INICIALIZAÇÃO
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const widget = document.querySelector('[data-space-id]');
    if (!widget) return;

    state.spaceId = widget.dataset.spaceId;
    const mode = widget.dataset.mode;

    // Get user ID from meta tag
    const userIdMeta = document.querySelector('meta[name="user-id"]');
    if (userIdMeta) {
        state.userId = parseInt(userIdMeta.content);
    }

    // Get customer ID from meta tag
    const customerIdMeta = document.querySelector('meta[name="customer-id"]');
    if (customerIdMeta) {
        state.customerId = parseInt(customerIdMeta.content);
    }

    if (mode === 'edit' && window.editMode && window.bookingData) {
        isEditMode = true;
        originalBookingData = window.bookingData;
        state.bookingId = window.bookingData.id;
        initEditMode();
    } else {
        initCalendar();
    }

    // Fetch space duration for price calculations
    fetchSpaceDuration();
});

async function fetchSpaceDuration() {
    try {
        const response = await fetch('/api/space/' + state.spaceId + '/details');
        if (response.ok) {
            const data = await response.json();
            state.scheduleDuration = data.duration || 30;
        }
    } catch (error) {
        console.warn('Could not fetch space duration, using default 30min');
        state.scheduleDuration = 30;
    }
}

// ============================================
// MODO DE EDIÇÃO
// ============================================
function initEditMode() {
    const bookingDate = new Date(originalBookingData.date);
    state.selectedDate = bookingDate;
    state.scheduleId = originalBookingData.scheduleId;
    window.selectedScheduleId = originalBookingData.scheduleId;
    state.time = originalBookingData.time;
    state.duration = originalBookingData.duration;
    state.persons = originalBookingData.persons;
    currentDate = bookingDate;

    initCalendar();

    setTimeout(async () => {
        await selectDate(bookingDate);
        document.getElementById('durationInput').value = state.duration;
        document.getElementById('personsInput').value = state.persons;
        showSection('time-section');
        showSection('duration-section');
        showSection('persons-section');
        showSection('confirm-section');
    }, 100);
}

// ============================================
// GESTÃO DE SEÇÕES PROGRESSIVAS
// ============================================
function showSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) section.classList.remove('hidden');
}

function hideSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) section.classList.add('hidden');
}

function resetFromSection(sectionId) {
    const sections = ['time-section', 'duration-section', 'persons-section', 'confirm-section'];
    const startIndex = sections.indexOf(sectionId);

    for (let i = startIndex; i < sections.length; i++) {
        hideSection(sections[i]);
    }
}

// ============================================
// CALENDÁRIO
// ============================================
function initCalendar() {
    renderCalendar();

    const prevBtn = document.getElementById('prevMonth');
    const nextBtn = document.getElementById('nextMonth');

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });
    }
}

function renderCalendar() {
    const monthElement = document.getElementById('currentMonth');
    const grid = document.getElementById('calendarGrid');

    if (!monthElement || !grid) return;

    const month = currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    monthElement.textContent = month;

    grid.innerHTML = '';

    // Headers
    const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    days.forEach(day => {
        const header = document.createElement('div');
        header.textContent = day;
        grid.appendChild(header);
    });

    // Dias do mês
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    const startingDayOfWeek = firstDay.getDay() === 0 ? 7 : firstDay.getDay();

    // Dias vazios
    for (let i = 1; i < startingDayOfWeek; i++) {
        grid.appendChild(document.createElement('div'));
    }

    // Dias
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    for (let day = 1; day <= lastDay.getDate(); day++) {
        const date = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
        const dayDiv = document.createElement('div');
        const isPast = date < today;
        const isSelected = state.selectedDate && date.toDateString() === state.selectedDate.toDateString();

        dayDiv.className = `py-2 rounded-xl border border-gray-200 transition ${
            isPast
                ? 'text-gray-300 line-through cursor-not-allowed'
                : isSelected
                    ? 'bg-emerald-800 text-white font-bold'
                    : 'hover:bg-gray-100 cursor-pointer text-gray-900'
        }`;

        dayDiv.textContent = day;

        if (!isPast) {
            dayDiv.onclick = () => selectDate(date);
        }

        grid.appendChild(dayDiv);
    }
}

async function selectDate(date) {
    state.selectedDate = date;
    renderCalendar();
    resetFromSection('time-section');
    showSection('time-section');
    await loadAvailableTimes(date);
}

async function loadAvailableTimes(date) {
    const dateStr = date.toISOString().split('T')[0];
    const timeGrid = document.getElementById('timeGrid');

    if (!timeGrid) return;

    timeGrid.innerHTML = '<div class="col-span-full text-center py-4 text-gray-500">Loading...</div>';

    try {
        const response = await fetch('/api/space/' + state.spaceId + '/schedule?date=' + dateStr, {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('HTTP error! status: ' + response.status);
        }

        const times = await response.json();

        if (!Array.isArray(times)) {
            console.error('Expected array but got:', times);
            throw new Error('Invalid response format');
        }

        timeGrid.innerHTML = '';

        if (times.length === 0) {
            timeGrid.innerHTML = '<div class="col-span-full text-center py-4">No available times</div>';
            return;
        }

        times.forEach(function(schedule) {
            const timeBtn = document.createElement('button');
            timeBtn.type = 'button';
            timeBtn.className = 'px-2 py-1 border border-gray-200 rounded-lg hover:border-2 hover:border-emerald-600 hover:bg-emerald-100 transition ' +
                (state.scheduleId === schedule.id ? 'border-2 border-emerald-600 bg-emerald-100 font-semibold' : '');
            timeBtn.textContent = schedule.start_time;
            timeBtn.onclick = function() {
                selectTime(schedule.id, schedule.start_time);
            };
            timeGrid.appendChild(timeBtn);
        });
    } catch (error) {
        console.error('Error loading times:', error);
        timeGrid.innerHTML = '<div class="col-span-full text-center py-4 text-red-500">Error loading times</div>';
    }
}

function selectTime(scheduleId, time) {
    state.scheduleId = scheduleId;
    window.selectedScheduleId = scheduleId; 
    state.time = time;

    document.querySelectorAll('#timeGrid button').forEach(function(btn) {
        btn.classList.remove('border-2','border-emerald-600', 'bg-emerald-100', 'font-semibold');
    });
    event.target.classList.add('border-2','border-emerald-600', 'bg-emerald-100', 'font-semibold');

    showSection('duration-section');
}

// ============================================
// DURAÇÃO E PESSOAS
// ============================================
function decrementDuration() {
    const input = document.getElementById('durationInput');
    const value = parseInt(input.value);
    if (value > parseInt(input.min)) {
        input.value = value - parseInt(input.step);
        updateDuration();
    }
}

function incrementDuration() {
    const input = document.getElementById('durationInput');
    input.value = parseInt(input.value) + parseInt(input.step);
    updateDuration();
}

function updateDuration() {
    state.duration = parseInt(document.getElementById('durationInput').value);
    showSection('persons-section');
}

function decrementPersons() {
    const input = document.getElementById('personsInput');
    const value = parseInt(input.value);
    if (value > 1) {
        input.value = value - 1;
        updatePersons();
    }
}

function incrementPersons() {
    const input = document.getElementById('personsInput');
    input.value = parseInt(input.value) + 1;
    updatePersons();
}

function updatePersons() {
    state.persons = parseInt(document.getElementById('personsInput').value);
    showSection('confirm-section');
}

// ============================================
// CALCULAR PREÇO CORRETO
// ============================================
function calculatePrice(duration, persons, scheduleDuration = 30) {
    const slotsNeeded = Math.ceil(duration / scheduleDuration);
    const pricePerSlot = 10;
    const totalPrice = slotsNeeded * persons * pricePerSlot;
    return totalPrice;
}

// ============================================
// CRIAR/ATUALIZAR RESERVA
// ============================================
async function createBooking() {
    if (!state.selectedDate || !state.scheduleId) {
        alert('Please complete all steps');
        return;
    }

    // Ensure we have customer ID and user ID
    if (!state.customerId) {
        const customerIdMeta = document.querySelector('meta[name="customer-id"]');
        if (customerIdMeta) {
            state.customerId = parseInt(customerIdMeta.content);
        }
    }

    if (!state.userId) {
        const userIdMeta = document.querySelector('meta[name="user-id"]');
        if (userIdMeta) {
            state.userId = parseInt(userIdMeta.content);
        }
    }

    if (!state.customerId || !state.userId) {
        alert('Please login to continue');
        window.location.href = '/sign-in';
        return;
    }

    if (isEditMode) {
        await handleEditBooking();
    } else {
        await handleNewBooking();
    }
}

async function handleNewBooking() {
    const newPrice = calculatePrice(state.duration, state.persons, state.scheduleDuration);
    openPaymentModal(newPrice, null, null);
}

async function handleEditBooking() {
    const oldPrice = calculatePrice(
        originalBookingData.duration,
        originalBookingData.persons,
        state.scheduleDuration
    );

    const newPrice = calculatePrice(
        state.duration,
        state.persons,
        state.scheduleDuration
    );

    const priceDifference = newPrice - oldPrice;

    console.log('💰 Price comparison:', {
        oldPrice,
        newPrice,
        difference: priceDifference
    });

    if (priceDifference > 0) {
        openPaymentModal(priceDifference, oldPrice, newPrice);
    } else if (priceDifference < 0) {
        openRefundModal(oldPrice, newPrice, Math.abs(priceDifference));
    } else {
        await updateBookingDirectly();
    }
}

async function updateBookingDirectly() {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        const response = await fetch(
            '/api/space/' + state.spaceId + '/schedule/' + state.scheduleId + '/bookings/' + state.bookingId,
            {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    new_schedule_id: state.scheduleId,
                    duration: state.duration,
                    number_of_persons: state.persons,
                    payment_provider_ref: 'Credit/Debit Card'
                })
            }
        );

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Failed to update booking');
        }

        const data = await response.json();

        if (data.success) {
            alert('Booking updated successfully!');
            window.location.href = '/users/' + state.userId + '/my_reservations';
        }
    } catch (error) {
        console.error('Update error:', error);
        alert('Failed to update booking: ' + error.message);
    }
}

// ============================================
// MODAL DE REEMBOLSO
// ============================================
function openRefundModal(oldPrice, newPrice, refundAmount) {
    const modal = document.getElementById('refundModal');
    if (!modal) {
        console.error('Refund modal not found');
        return;
    }

    document.getElementById('refundOldPrice').textContent = oldPrice.toFixed(2);
    document.getElementById('refundNewPrice').textContent = newPrice.toFixed(2);
    document.getElementById('refundAmount').textContent = refundAmount.toFixed(2);

    modal.classList.remove('hidden');
}

function closeRefundModal() {
    const modal = document.getElementById('refundModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

async function confirmRefund() {
    closeRefundModal();
    await updateBookingDirectly();
}

// ============================================
// MODAL DE PAGAMENTO
// ============================================
function selectPaymentMethod(method) {
    selectedPaymentMethod = method;

    document.querySelectorAll('.payment-method').forEach(function(btn) {
        btn.classList.remove('border-emerald-600', 'bg-emerald-50');
    });

    event.target.classList.add('border-emerald-600', 'bg-emerald-50');

    document.getElementById('cardForm').classList.add('hidden');
    document.getElementById('mbwayForm').classList.add('hidden');
    document.getElementById('paypalForm').classList.add('hidden');

    if (method === 'card') {
        document.getElementById('cardForm').classList.remove('hidden');
    } else if (method === 'mbway') {
        document.getElementById('mbwayForm').classList.remove('hidden');
    } else if (method === 'paypal') {
        document.getElementById('paypalForm').classList.remove('hidden');
    }
}

function openPaymentModal(amount, oldPrice = null, newPrice = null) {
    const modal = document.getElementById('paymentModal');
    const amountElement = document.getElementById('paymentAmount');
    const detailsElement = document.getElementById('paymentDetails');

    if (oldPrice !== null && newPrice !== null) {
        amountElement.textContent = '€' + amount.toFixed(2);
        detailsElement.innerHTML = `
            <div class="text-sm text-gray-600 space-y-1">
                <div class="flex justify-between">
                    <span>Original price:</span>
                    <span>€${oldPrice.toFixed(2)}</span>
                </div>
                <div class="flex justify-between">
                    <span>New price:</span>
                    <span>€${newPrice.toFixed(2)}</span>
                </div>
                <div class="flex justify-between font-semibold text-emerald-600 border-t pt-1">
                    <span>Additional payment:</span>
                    <span>€${amount.toFixed(2)}</span>
                </div>
            </div>
        `;
        detailsElement.classList.remove('hidden');
    } else {
        amountElement.textContent = '€' + amount.toFixed(2);
        detailsElement.classList.add('hidden');
    }

    modal.classList.remove('hidden');

    selectedPaymentMethod = null;
    document.querySelectorAll('.payment-method').forEach(function(btn) {
        btn.classList.remove('border-emerald-600', 'bg-emerald-50');
    });
    document.getElementById('cardForm').classList.add('hidden');
    document.getElementById('mbwayForm').classList.add('hidden');
    document.getElementById('paypalForm').classList.add('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}

window.applyPromoCode = function() {
    const codeInput = document.getElementById('promoCodeInput');
    const messageEl = document.getElementById('promoMessage');
    
    if (!codeInput || !codeInput.value.trim()) {
        messageEl.textContent = 'Insert a promotional code.';
        messageEl.classList.remove('hidden', 'text-green-600');
        messageEl.classList.add('text-red-600');
        return;
    }

    messageEl.classList.add('hidden');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch("/bookings/check-discount", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            space_id: state.spaceId,
            schedule_id: state.scheduleId,
            duration: state.duration,
            persons: state.persons,
            code: codeInput.value
        })
    })
    .then(r => r.ok ? r.json() : r.text().then(t => { throw new Error(t) }))
    .then(data => {
        messageEl.classList.remove('hidden');
        if (data.valid) {
            state.discountCode = codeInput.value; 
            
            messageEl.textContent = data.message;
            messageEl.className = 'text-sm mt-2 text-green-600';
            
            const oldPriceEl = document.getElementById('originalPriceDisplay');
            const newPriceEl = document.getElementById('paymentAmount');
            
            if(oldPriceEl) {
                oldPriceEl.textContent = '€' + parseFloat(data.original_price).toFixed(2);
                oldPriceEl.classList.remove('hidden');
            }
            if(newPriceEl) {
                newPriceEl.textContent = '€' + parseFloat(data.final_price).toFixed(2);
                newPriceEl.classList.add('text-green-700');
            }
        } else {
            state.discountCode = null;
            throw new Error(data.message || 'Invalid code');
        }
    })
    .catch(error => {
        state.discountCode = null;
        console.error('Erro:', error);
        let msg = 'Error applying code.';
        try { 
            if(error.message.startsWith('{')) msg = JSON.parse(error.message).message;
            else msg = error.message;
        } catch(e) {}
        
        messageEl.textContent = msg;
        messageEl.className = 'text-sm mt-2 text-red-600';
        messageEl.classList.remove('hidden');
    });
};

async function processPayment() {
    if (!selectedPaymentMethod) {
        alert('Please select a payment method');
        return;
    }

    let isValid = false;

    if (selectedPaymentMethod === 'card') {
        const cardNumber = document.getElementById('cardNumber').value;
        const cardExpiry = document.getElementById('cardExpiry').value;
        const cardCVV = document.getElementById('cardCVV').value;
        isValid = cardNumber && cardExpiry && cardCVV;
    } else if (selectedPaymentMethod === 'mbway') {
        const mbwayPhone = document.getElementById('mbwayPhone').value;
        isValid = mbwayPhone && mbwayPhone.length === 9;
    } else if (selectedPaymentMethod === 'paypal') {
        const paypalEmail = document.getElementById('paypalEmail').value;
        isValid = paypalEmail && paypalEmail.includes('@');
    }

    if (!isValid) {
        alert('Please fill in all payment details');
        return;
    }

    const confirmBtn = event.target;
    const originalText = confirmBtn.textContent;
    confirmBtn.textContent = 'Processing...';
    confirmBtn.disabled = true;

    try {
        await new Promise(function(resolve) {
            setTimeout(resolve, 1000);
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        let paymentProviderRef;
        if (selectedPaymentMethod === 'card') {
            paymentProviderRef = 'Credit/Debit Card';
        } else if (selectedPaymentMethod === 'mbway') {
            paymentProviderRef = 'MB Way';
        } else if (selectedPaymentMethod === 'paypal') {
            paymentProviderRef = 'Paypal';
        }

        let response;

        if (isEditMode) {
            response = await fetch(
                '/api/space/' + state.spaceId + '/schedule/' + state.scheduleId + '/bookings/' + state.bookingId,
                {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        new_schedule_id: state.scheduleId,
                        duration: state.duration,
                        number_of_persons: state.persons,
                        payment_provider_ref: paymentProviderRef
                    })
                }
            );
        } else {
            response = await fetch(
                '/api/space/' + state.spaceId + '/schedule/' + state.scheduleId + '/bookings',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        customer_id: state.customerId,
                        duration: state.duration,
                        number_of_persons: state.persons,
                        payment_provider_ref: paymentProviderRef,
                        discount_code: state.discountCode
                    })
                }
            );
        }

        if (!response.ok) {
            const errorData = await response.json();

            if (errorData.details) {
                const errorMessages = Object.values(errorData.details).flat().join('\n');
                throw new Error('Validation errors:\n' + errorMessages);
            }

            throw new Error(errorData.error || errorData.message || 'Operation failed');
        }

        const data = await response.json();

        if (data.success) {
            window.location.href = '/bookings/payment-success';
        } else {
            throw new Error(data.error || 'Operation failed');
        }
    } catch (error) {
        console.error('Payment error:', error);
        alert('Payment failed: ' + error.message);
        confirmBtn.textContent = originalText;
        confirmBtn.disabled = false;
    }
}

// ============================================
// MODAL DE CANCELAMENTO
// ============================================
function openCancelModalFromData(button) {
    const bookingId = button.dataset.bookingId;
    const spaceName = button.dataset.spaceName;
    const customerName = button.dataset.customerName || null;
    const date = button.dataset.date;
    const time = button.dataset.time;
    const duration = button.dataset.duration;
    const amount = parseFloat(button.dataset.amount);
    const spaceId = button.dataset.spaceId;
    const scheduleId = button.dataset.scheduleId;

    openCancelModal(bookingId, spaceName, customerName, date, time, duration, amount, spaceId, scheduleId);
}

function openCancelModal(bookingId, spaceName, customerName, date, time, duration, amount, spaceId, scheduleId) {
    const modal = document.getElementById('cancelModal');

    if (!modal) {
        console.error('Cancel modal not found');
        return;
    }

    const requiredElements = {
        cancelSpaceName: document.getElementById('cancelSpaceName'),
        cancelDate: document.getElementById('cancelDate'),
        cancelTime: document.getElementById('cancelTime'),
        cancelAmount: document.getElementById('cancelAmount'),
        cancelBookingId: document.getElementById('cancelBookingId'),
        cancelSpaceId: document.getElementById('cancelSpaceId'),
        cancelScheduleId: document.getElementById('cancelScheduleId')
    };

    for (const key in requiredElements) {
        if (!requiredElements[key]) {
            console.error('Missing required modal element:', key);
            return;
        }
    }

    requiredElements.cancelSpaceName.textContent = spaceName || 'N/A';
    requiredElements.cancelDate.textContent = date || 'N/A';
    requiredElements.cancelTime.textContent = time + ', (' + duration + ' min)';
    requiredElements.cancelAmount.textContent = (amount || 0).toFixed(2) + '€';
    requiredElements.cancelBookingId.value = bookingId;
    requiredElements.cancelSpaceId.value = spaceId;
    requiredElements.cancelScheduleId.value = scheduleId;

    const cancelCustomerName = document.getElementById('cancelCustomerName');
    if (cancelCustomerName) {
        if (customerName) {
            cancelCustomerName.textContent = customerName;
        } else {
            const customerRow = cancelCustomerName.closest('.flex');
            if (customerRow) {
                customerRow.style.display = 'none';
            }
        }
    }

    modal.classList.remove('hidden');
}

function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

async function confirmCancel() {
    const bookingId = document.getElementById('cancelBookingId')?.value;
    const spaceId = document.getElementById('cancelSpaceId')?.value;
    const scheduleId = document.getElementById('cancelScheduleId')?.value;

    if (!bookingId || !spaceId || !scheduleId) {
        alert('Invalid booking data. Please try again.');
        console.error('Missing data:', { bookingId, spaceId, scheduleId });
        return;
    }

    const confirmBtn = event.target;
    const originalText = confirmBtn.textContent;
    confirmBtn.textContent = 'Cancelling...';
    confirmBtn.disabled = true;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    try {
        const url = '/api/space/' + spaceId + '/schedule/' + scheduleId + '/bookings/' + bookingId + '/cancel';

        const response = await fetch(url, {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'same-origin'
        });

        const data = await response.json();

        if (response.ok && data.success) {
            closeCancelModal();
            alert('Booking cancelled successfully!');
            window.location.reload();
        } else {
            alert(data.error || data.message || 'Failed to cancel booking');
            confirmBtn.textContent = originalText;
            confirmBtn.disabled = false;
        }
    } catch (error) {
        console.error('Error cancelling booking:', error);
        alert('An error occurred while cancelling the booking. Please try again.');
        confirmBtn.textContent = originalText;
        confirmBtn.disabled = false;
    }
}