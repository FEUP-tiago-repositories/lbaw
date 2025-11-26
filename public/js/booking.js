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
    payment: null
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

    if (mode === 'edit' && window.editMode && window.bookingData) {
        isEditMode = true;
        originalBookingData = window.bookingData;
        state.bookingId = window.bookingData.id;
        initEditMode();
    } else {
        initCalendar();
    }
});

// ============================================
// MODO DE EDIÇÃO
// ============================================
function initEditMode() {
    const bookingDate = new Date(originalBookingData.date);
    state.selectedDate = bookingDate;
    state.scheduleId = originalBookingData.scheduleId;
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

        dayDiv.className = `py-2 rounded-lg border border-gray-200 transition ${
            isPast
                ? 'text-gray-300 line-through cursor-not-allowed'
                : isSelected
                    ? 'bg-blue-600 text-white font-bold'
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
        const response = await fetch(`/api/space/${state.spaceId}/schedule?date=${dateStr}`);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const times = await response.json();
        timeGrid.innerHTML = '';

        if (times.length === 0) {
            timeGrid.innerHTML = '<div class="col-span-full text-center py-4">No available times</div>';
            return;
        }

        times.forEach(schedule => {
            const timeBtn = document.createElement('button');
            timeBtn.type = 'button';
            timeBtn.className = `px-3 py-2 border border-gray-300 rounded-lg hover:border-2 hover:border-blue-600 hover:bg-blue-100 transition ${
                state.scheduleId === schedule.id ? 'border-2 border-blue-600 bg-blue-100 font-semibold' : ''
            }`;
            timeBtn.textContent = schedule.start_time;
            timeBtn.onclick = () => selectTime(schedule.id, schedule.start_time);
            timeGrid.appendChild(timeBtn);
        });
    } catch (error) {
        console.error('Error loading times:', error);
        timeGrid.innerHTML = '<div class="col-span-full text-center py-4 text-red-500">Error loading times</div>';
    }
}

function selectTime(scheduleId, time) {
    state.scheduleId = scheduleId;
    state.time = time;

    document.querySelectorAll('#timeGrid button').forEach(btn => {
        btn.classList.remove('border-2','border-blue-600', 'bg-blue-100', 'font-semibold');
    });
    event.target.classList.add('border-2','border-blue-600', 'bg-blue-100', 'font-semibold');

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
// CRIAR/ATUALIZAR RESERVA
// ============================================
async function createBooking() {
    if (!state.selectedDate || !state.scheduleId) {
        alert('Please complete all steps');
        return;
    }

    const customerId = document.querySelector('meta[name="customer-id"]')?.content;

    if (!customerId) {
        alert('Please login to continue');
        window.location.href = '/sign-in';
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    try {
        let response, data;

        if (isEditMode) {
            response = await fetch(
                `/api/space/${state.spaceId}/schedule/${state.scheduleId}/bookings/${state.bookingId}`,
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
        } else {
            const requestData = {
                customer_id: parseInt(customerId),
                duration: state.duration,
                number_of_persons: state.persons,
                payment_provider_ref: 'Credit/Debit Card'
            };

            console.log('📤 Sending POST request:', {
                url: `/api/space/${state.spaceId}/schedule/${state.scheduleId}/bookings`,
                data: requestData
            });
            response = await fetch(
                `/api/space/${state.spaceId}/schedule/${state.scheduleId}/bookings`,
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        customer_id: parseInt(customerId),
                        duration: parseInt(state.duration),
                        number_of_persons: parseInt(state.persons),
                        payment_provider_ref: 'Credit/Debit Card'
                    })
                }
            );
        }

        data = await response.json();

        if (data.success) {
            state.bookingId = data.booking_id;
            state.payment = data.payment || { value: data.additional_payment || 0 };
            openPaymentModal(data.payment?.value || data.additional_payment);
        } else {
            alert(data.error || 'Failed to process booking');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
}

// ============================================
// MODAL DE PAGAMENTO
// ============================================
function selectPaymentMethod(method) {
    selectedPaymentMethod = method;

    document.querySelectorAll('.payment-method').forEach(btn => {
        btn.classList.remove('border-blue-600', 'bg-blue-50');
    });

    event.target.classList.add('border-blue-600', 'bg-blue-50');

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

function openPaymentModal(amount) {
    document.getElementById('paymentAmount').textContent = '€' + amount.toFixed(2);
    document.getElementById('paymentModal').classList.remove('hidden');

    selectedPaymentMethod = null;
    document.querySelectorAll('.payment-method').forEach(btn => {
        btn.classList.remove('border-blue-600', 'bg-blue-50');
    });
    document.getElementById('cardForm').classList.add('hidden');
    document.getElementById('mbwayForm').classList.add('hidden');
    document.getElementById('paypalForm').classList.add('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}

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

    await new Promise(resolve => setTimeout(resolve, 1000));
    window.location.href = '/bookings/payment-success';
}

// ============================================
// MODAL DE CANCELAMENTO
// ============================================
function openCancelModalFromData(button) {
    const bookingId = button.dataset.bookingId;
    const spaceName = button.dataset.spaceName;
    const date = button.dataset.date;
    const time = button.dataset.time;
    const duration = button.dataset.duration;
    const amount = parseFloat(button.dataset.amount);
    const spaceId = button.dataset.spaceId;
    const scheduleId = button.dataset.scheduleId;

    openCancelModal(bookingId, spaceName, date, time, duration, amount, spaceId, scheduleId);
}

function openCancelModal(bookingId, spaceName, date, time, duration, amount, spaceId, scheduleId) {
    const modal = document.getElementById('cancelModal');

    if (!modal) {
        console.error('Cancel modal not found');
        return;
    }

    document.getElementById('cancelSpaceName').textContent = spaceName;
    document.getElementById('cancelDate').textContent = date;
    document.getElementById('cancelTime').textContent = time + ', (' + duration + ' min)';
    document.getElementById('cancelAmount').textContent = amount.toFixed(2) + '€';
    document.getElementById('cancelBookingId').value = bookingId;
    document.getElementById('cancelSpaceId').value = spaceId;
    document.getElementById('cancelScheduleId').value = scheduleId;

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
        const url = `/api/space/${spaceId}/schedule/${scheduleId}/bookings/${bookingId}/cancel`;

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
    }}
