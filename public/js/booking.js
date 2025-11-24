// Estado global
let state = {
    spaceId: null,
    selectedDate: null,
    scheduleId: null,
    time: null,
    duration: 30,
    persons: 1,
    bookingId: null,
    payment: null
};

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    const space = document.querySelector('[data-space-id]');
    if (space) {
        state.spaceId = space.dataset.spaceId;
        initCalendar();
    }
});

// ============================================
// CALENDÁRIO
// ============================================

let currentDate = new Date();

function initCalendar() {
    renderCalendar();
}

function renderCalendar() {
    const month = currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    document.getElementById('currentMonth').textContent = month;

    const grid = document.getElementById('calendarGrid');
    grid.innerHTML = '';

    // Headers
    ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'].forEach(day => {
        const header = document.createElement('div');
        header.className = 'text-center text-sm font-semibold text-gray-600 py-2';
        header.textContent = day;
        grid.appendChild(header);
    });

    // Days
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    const startDay = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;

    // Empty cells
    for (let i = 0; i < startDay; i++) {
        grid.appendChild(document.createElement('div'));
    }

    // Days
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    for (let day = 1; day <= lastDay.getDate(); day++) {
        const date = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
        date.setHours(0, 0, 0, 0);

        const cell = document.createElement('div');
        cell.textContent = day;
        cell.className = 'text-center py-2 cursor-pointer hover:bg-blue-50 rounded';

        if (date < today) {
            cell.className = 'text-center py-2 text-gray-300';
        } else {
            cell.onclick = () => selectDate(date);
        }

        grid.appendChild(cell);
    }
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
}

async function selectDate(date) {
    state.selectedDate = date;

    const dateStr = date.toISOString().split('T')[0];
    const response = await fetch(`/api/space/${state.spaceId}/schedules/available?date=${dateStr}`);
    const data = await response.json();

    renderTimes(data.schedules);
    showStep('step-times');
    updateSummary();
}

function renderTimes(schedules) {
    const grid = document.getElementById('timesGrid');
    grid.innerHTML = '';

    schedules.forEach(s => {
        const btn = document.createElement('button');
        btn.textContent = s.start_time;
        btn.className = 'p-2 border rounded hover:bg-blue-600 hover:text-white';
        btn.onclick = () => {
            state.scheduleId = s.id;
            state.time = s.start_time;
            state.duration = s.duration;
            showStep('step-duration');
            updateSummary();
        };
        grid.appendChild(btn);
    });
}

// ============================================
// DURAÇÃO E PESSOAS
// ============================================

function changeDuration(amount) {
    state.duration = Math.max(30, state.duration + amount);
    document.getElementById('durationValue').textContent = state.duration + ' min';
    updateSummary();
}

function changePersons(amount) {
    state.persons = Math.max(1, state.persons + amount);
    document.getElementById('personsValue').textContent = state.persons;
    updateSummary();
}

// ============================================
// NAVEGAÇÃO
// ============================================

function showStep(stepId) {
    ['step-calendar', 'step-times', 'step-duration', 'step-persons'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
    });
    document.getElementById(stepId).classList.remove('hidden');

    if (stepId !== 'step-calendar') {
        document.getElementById('bookingSummary').classList.remove('hidden');
    }
}

function updateSummary() {
    if (!state.selectedDate) return;

    document.getElementById('summaryDate').textContent =
        state.selectedDate.toLocaleDateString('pt-PT');
    document.getElementById('summaryTime').textContent = state.time || '-';
    document.getElementById('summaryDuration').textContent = state.duration + ' min';
    document.getElementById('summaryPersons').textContent = state.persons;

    const total = Math.ceil(state.duration / 30) * state.persons * 10;
    document.getElementById('summaryTotal').textContent = total.toFixed(2) + '€';
}

// ============================================
// CRIAR RESERVA
// ============================================

async function createBooking() {
    const customerId = document.querySelector('meta[name="customer-id"]')?.content;

    const response = await fetch(
        `/api/space/${state.spaceId}/schedule/${state.scheduleId}/bookings`,
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                customer_id: parseInt(customerId),
                duration: state.duration,
                number_of_persons: state.persons,
                payment_provider_ref: 'Credit/Debit Card'
            })
        }
    );

    const data = await response.json();

    if (data.success) {
        state.bookingId = data.booking_id;
        state.payment = data.payment;
        openPaymentModal(data.payment.value);
    } else {
        alert('Failed to create booking');
    }
}

// ============================================
// PAGAMENTO
// ============================================

function openPaymentModal(amount) {
    document.getElementById('paymentAmount').textContent = amount.toFixed(2) + '€';
    document.getElementById('paymentModal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}

function selectPayment(method) {
    document.querySelectorAll('.payment-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white');
    });
    event.target.classList.add('bg-blue-600', 'text-white');
    state.payment = method;
}

async function confirmPayment() {
    const response = await fetch('/api/bookings/confirm-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });

    if (response.ok) {
        closePaymentModal();
        alert('Payment confirmed!');
        window.location.href = `/user/${document.querySelector('meta[name="user-id"]').content}/my_reservations`;
    }
}

// ============================================
// CANCELAR
// ============================================

let cancelBookingId = null;

function openCancelModal(id, space, date, time, refund) {
    cancelBookingId = id;
    document.getElementById('cancelSpace').textContent = space;
    document.getElementById('cancelDate').textContent = date;
    document.getElementById('cancelTime').textContent = time;
    document.getElementById('cancelRefund').textContent = refund.toFixed(2);
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

async function confirmCancel() {
    const customerId = document.querySelector('meta[name="customer-id"]').content;

    const response = await fetch(
        `/api/space/0/schedule/0/bookings/${cancelBookingId}/cancel`,
        {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ customer_id: parseInt(customerId) })
        }
    );

    if (response.ok) {
        closeCancelModal();
        alert('Booking cancelled!');
        location.reload();
    }
}

// ============================================
// EDITAR
// ============================================

function editReservation(id) {
    alert('Edit functionality - redirect to edit page');
}

// ============================================
// PAYMENT MODAL
// ============================================

let selectedPaymentMethod = null;

function openPaymentModal(amount) {
    document.getElementById('paymentAmount').textContent = amount.toFixed(2) + '€';
    document.getElementById('paymentModal').classList.remove('hidden');
    document.getElementById('confirmPayBtn').disabled = true; // Desabilitar até selecionar método
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');

    // Reset
    document.querySelectorAll('.payment-btn').forEach(btn => {
        btn.classList.remove('border-blue-500', 'bg-blue-50');
    });

    // Hide all forms
    document.getElementById('cardPaymentForm').classList.add('hidden');
    document.getElementById('mbwayPaymentForm').classList.add('hidden');
    document.getElementById('paypalPaymentForm').classList.add('hidden');

    selectedPaymentMethod = null;
}

function selectPayment(method) {
    selectedPaymentMethod = method;

    // Update button styles
    document.querySelectorAll('.payment-btn').forEach(btn => {
        btn.classList.remove('border-blue-500', 'bg-blue-50');
    });
    event.currentTarget.classList.add('border-blue-500', 'bg-blue-50');

    // Hide all forms first
    document.getElementById('cardPaymentForm').classList.add('hidden');
    document.getElementById('mbwayPaymentForm').classList.add('hidden');
    document.getElementById('paypalPaymentForm').classList.add('hidden');

    // Show relevant form
    if (method === 'Credit/Debit Card') {
        document.getElementById('cardPaymentForm').classList.remove('hidden');
    } else if (method === 'MB Way') {
        document.getElementById('mbwayPaymentForm').classList.remove('hidden');
    } else if (method === 'Paypal') {
        document.getElementById('paypalPaymentForm').classList.remove('hidden');
    }

    // Enable confirm button
    document.getElementById('confirmPayBtn').disabled = false;
}

async function confirmPayment() {
    if (!selectedPaymentMethod) {
        alert('Please select a payment method');
        return;
    }

    try {
        const response = await fetch('/api/bookings/confirm-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) throw new Error('Payment failed');

        // Close payment modal
        closePaymentModal();

        // Show success modal
        document.getElementById('paymentSuccessModal').classList.remove('hidden');

    } catch (error) {
        console.error('Payment error:', error);
        alert('Payment failed. Please try again.');
    }
}

function goToReservations() {
    const userId = document.querySelector('meta[name="user-id"]')?.content;
    window.location.href = `/user/${userId}/my_reservations`;
}

function openEditModal(bookingId) {
    // Redirecionar para página de edição
    window.location.href = `/test/bookings/${bookingId}/edit`;
}