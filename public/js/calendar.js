/**
 * Calendar View for Bookings
 * Manages the monthly calendar display and booking visualization
 */

let currentDate = new Date();
let bookingsData = [];

/**
 * Initialize the calendar when the page loads
 */
function initializeCalendar() {
    // Load bookings data from hidden script tag
    loadBookingsData();

    // Render the current month
    renderCalendar();

    // Setup event listeners
    setupEventListeners();
}

/**
 * Load bookings data from the JSON script tag
 */
function loadBookingsData() {
    const dataElement = document.getElementById('bookings-data');
    if (dataElement) {
        try {
            bookingsData = JSON.parse(dataElement.textContent);
        } catch (e) {
            console.error('Error parsing bookings data:', e);
            bookingsData = [];
        }
    }
}

/**
 * Setup event listeners for calendar navigation
 */
function setupEventListeners() {
    const prevButton = document.getElementById('prev-month');
    const nextButton = document.getElementById('next-month');

    if (prevButton) {
        prevButton.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });
    }
}

/**
 * Render the calendar for the current month
 */
function renderCalendar() {
    updateMonthYearDisplay();
    renderCalendarDays();
}

/**
 * Update the month and year display
 */
function updateMonthYearDisplay() {
    const monthYearElement = document.getElementById('calendar-month-year');
    if (monthYearElement) {
        const options = { month: 'long', year: 'numeric' };
        monthYearElement.textContent = currentDate.toLocaleDateString('en-US', options);
    }
}

/**
 * Render all calendar day cells
 */
function renderCalendarDays() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    // Get first day of month and total days
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const firstDayOfWeek = firstDay.getDay();
    const totalDays = lastDay.getDate();

    // Get all calendar cells
    const cells = document.querySelectorAll('.calendar-day-cell');

    // Clear all cells first
    cells.forEach(cell => {
        cell.classList.add('hidden');
        cell.querySelector('.calendar-day-number').textContent = '';
        cell.querySelector('.calendar-day-indicator').innerHTML = '';
        cell.dataset.date = '';
    });

    // Fill in the days
    for (let day = 1; day <= totalDays; day++) {
        const cellIndex = firstDayOfWeek + day - 1;
        const cell = cells[cellIndex];

        if (cell) {
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            // Show cell and set day number
            cell.classList.remove('hidden');
            cell.querySelector('.calendar-day-number').textContent = day;
            cell.dataset.date = dateStr;

            // Check if this is today
            const today = new Date();
            const isToday =
                day === today.getDate() &&
                month === today.getMonth() &&
                year === today.getFullYear();

            if (isToday) {
                cell.querySelector('.calendar-day-number').classList.add('text-blue-600', 'font-bold');
            } else {
                cell.querySelector('.calendar-day-number').classList.remove('text-blue-600', 'font-bold');
            }

            // Add booking indicators
            addBookingIndicators(cell, dateStr);

            // Add click event
            cell.onclick = () => showDayBookings(dateStr);
        }
    }
}

/**
 * Add booking indicators to a calendar day cell
 */
function addBookingIndicators(cell, dateStr) {
    const dayBookings = bookingsData.filter(b => b.date === dateStr);
    const indicatorContainer = cell.querySelector('.calendar-day-indicator');

    if (dayBookings.length === 0) return;

    // Count by status
    const futureCount = dayBookings.filter(b => b.status === 'future').length;
    const pastCount = dayBookings.filter(b => b.status === 'past').length;
    const cancelledCount = dayBookings.filter(b => b.status === 'cancelled').length;

    // Add indicators
    if (futureCount > 0) {
        const dot = createIndicatorDot('blue', futureCount);
        indicatorContainer.appendChild(dot);
    }

    if (pastCount > 0) {
        const dot = createIndicatorDot('gray', pastCount);
        indicatorContainer.appendChild(dot);
    }

    if (cancelledCount > 0) {
        const dot = createIndicatorDot('red', cancelledCount);
        indicatorContainer.appendChild(dot);
    }
}

/**
 * Create an indicator dot element
 */
function createIndicatorDot(color, count) {
    const dot = document.createElement('div');
    dot.className = `w-2 h-2 rounded-full bg-${color}-500`;
    dot.title = `${count} booking(s)`;
    return dot;
}

/**
 * Show bookings for a specific day
 */
function showDayBookings(dateStr) {
    const dayBookings = bookingsData.filter(b => b.date === dateStr);

    const container = document.getElementById('selected-day-bookings');
    const title = document.getElementById('selected-day-title');
    const list = document.getElementById('selected-day-bookings-list');

    if (dayBookings.length === 0) {
        container.classList.add('hidden');
        return;
    }

    // Format date for title
    const date = new Date(dateStr + 'T00:00:00');
    const dateFormatted = date.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    title.textContent = `Bookings for ${dateFormatted}`;

    // Clear previous bookings
    list.innerHTML = '';

    // Get booking card templates and clone them
    const templates = document.querySelectorAll('.booking-card-template');

    dayBookings.forEach(booking => {
        const template = Array.from(templates).find(
            t => t.dataset.bookingId === String(booking.id)
        );

        if (template) {
            const clone = template.firstElementChild.cloneNode(true);
            list.appendChild(clone);
        }
    });

    // Show the container
    container.classList.remove('hidden');

    // Scroll to bookings
    container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Export for use in inline script
window.initializeCalendar = initializeCalendar;
