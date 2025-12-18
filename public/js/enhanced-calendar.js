/**
 * Enhanced Calendar View - Navigation and Interactions
 * Handles view switching, date navigation, week filters, and slot details panel
 */

let currentDate = new Date(document.getElementById('day-select')?.value || new Date());
let selectedSlotId = null;

/**
 * Initialize on page load
 */
document.addEventListener('DOMContentLoaded', function() {
    // Set default view to calendar
    switchView('calendar');
});

/**
 * Switch between List and Calendar views
 */
function switchView(view) {
    // Hide all views
    document.getElementById('list-view').classList.add('hidden');
    document.getElementById('calendar-view').classList.add('hidden');
    
    // Reset button styles
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.classList.remove('bg-emerald-200', 'text-black');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    // Show selected view and highlight button
    if (view === 'list') {
        document.getElementById('list-view').classList.remove('hidden');
        const btn = document.getElementById('btn-list-view');
        btn.classList.remove('bg-gray-200', 'text-gray-700');
        btn.classList.add('bg-emerald-200', 'text-black');
    } else if (view === 'calendar') {
        document.getElementById('calendar-view').classList.remove('hidden');
        const btn = document.getElementById('btn-calendar-view');
        btn.classList.remove('bg-gray-200', 'text-gray-700');
        btn.classList.add('bg-emerald-200', 'text-black');
    }
}

/**
 * Show specific tab in list view
 */
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Reset all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-emerald-800', 'text-emerald-800');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab
    document.getElementById(`content-${tabName}`).classList.remove('hidden');
    
    // Highlight selected button
    const selectedButton = document.getElementById(`tab-${tabName}`);
    selectedButton.classList.remove('border-transparent', 'text-gray-500');
    selectedButton.classList.add('border-emerald-800', 'text-emerald-800');
}

/**
 * Navigate to specific date
 */
function goToDate(dateString) {
    const url = new URL(window.location);
    url.searchParams.set('date', dateString);
    window.location.href = url.toString();
}

/**
 * Update date from day/month/year selectors
 */
function updateDateFromSelectors() {
    const day = document.getElementById('day-select').value;
    const month = document.getElementById('month-select').value;
    const year = document.getElementById('year-select').value;
    
    const date = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    goToDate(date);
}

/**
 * Change week (forward/backward)
 */
function changeWeek(offset) {
    const currentDateStr = getDateFromSelectors();
    const date = new Date(currentDateStr);
    date.setDate(date.getDate() + (offset * 7));
    
    const newDate = formatDateForInput(date);
    goToDate(newDate);
}

/**
 * Go to specific day of week (0 = Monday, 6 = Sunday)
 */
function goToDayOfWeek(dayIndex) {
    const currentDateStr = getDateFromSelectors();
    const date = new Date(currentDateStr);
    
    // Get current day of week (0 = Monday)
    const currentDayIndex = (date.getDay() + 6) % 7;
    
    // Calculate difference
    const diff = dayIndex - currentDayIndex;
    
    // Apply difference
    date.setDate(date.getDate() + diff);
    
    const newDate = formatDateForInput(date);
    goToDate(newDate);
}

/**
 * Get date from selectors
 */
function getDateFromSelectors() {
    const day = document.getElementById('day-select').value;
    const month = document.getElementById('month-select').value;
    const year = document.getElementById('year-select').value;
    return `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
}

/**
 * Format date for input (YYYY-MM-DD)
 */
function formatDateForInput(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

/**
 * Show details for selected time slot
 */
function showSlotDetails(slotId) {
    selectedSlotId = slotId;
    
    // Remove active state from all slots
    document.querySelectorAll('.slot-button').forEach(btn => {
        btn.classList.remove('ring-2', 'ring-emerald-500');
    });
    
    // Add active state to selected slot
    const selectedButton = document.querySelector(`[data-slot-id="${slotId}"]`);
    if (selectedButton) {
        selectedButton.classList.add('ring-2', 'ring-emerald-500');
    }
    
    // Get slot data
    const slotData = document.getElementById(`slot-data-${slotId}`);
    const detailsPanel = document.getElementById('details-panel');
    
    if (slotData && detailsPanel) {
        // Clone and display the slot data
        detailsPanel.innerHTML = slotData.innerHTML;
    }
}
