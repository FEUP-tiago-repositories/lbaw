/**
 * Daily Calendar View - Navigation and Interactions
 * Handles date navigation and booking details expansion
 */

/**
 * Navigate to previous/next day
 */
function changeDay(offset) {
    const currentDateValue = document.getElementById('date-picker').value;
    const date = new Date(currentDateValue);
    date.setDate(date.getDate() + offset);

    const newDate = formatDateForInput(date);
    goToDate(newDate);
}

/**
 * Navigate to today
 */
function goToToday() {
    const today = formatDateForInput(new Date());
    goToDate(today);
}

/**
 * Navigate to specific date
 */
function goToDate(dateString) {
    // Update URL with date parameter
    const url = new URL(window.location);
    url.searchParams.set('date', dateString);
    window.location.href = url.toString();
}

/**
 * Format date for input field (YYYY-MM-DD)
 */
function formatDateForInput(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

/**
 * Toggle visibility of bookings for a specific schedule
 */
function toggleScheduleBookings(scheduleId) {
    const bookingsDiv = document.getElementById(`schedule-bookings-${scheduleId}`);
    const chevron = document.getElementById(`chevron-${scheduleId}`);

    if (bookingsDiv.classList.contains('hidden')) {
        bookingsDiv.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
    } else {
        bookingsDiv.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
    }
}
