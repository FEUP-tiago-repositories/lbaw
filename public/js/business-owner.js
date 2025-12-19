/**
 * Business Owner Management
 * Handles space details (About/Reviews tabs) and space bookings (List/Calendar views)
 */

// ============================================
// SPACE DETAILS - About/Reviews Tabs
// ============================================
function initSpaceDetailsTabs() {
    const aboutTab = document.getElementById('about-tab');
    const reviewsTab = document.getElementById('reviews-tab');
    const aboutContent = document.getElementById('about-content');
    const reviewsContent = document.getElementById('reviews-content');

    if (!aboutTab || !reviewsTab) return;

    function showAbout() {
        aboutTab.classList.add('text-green-700', 'border-b-2', 'border-green-700');
        aboutTab.classList.remove('hover:text-green-700');
        reviewsTab.classList.remove('text-green-700', 'border-b-2', 'border-green-700');
        reviewsTab.classList.add('hover:text-green-700');

        if (aboutContent) aboutContent.classList.remove('hidden');
        if (reviewsContent) reviewsContent.classList.add('hidden');
    }

    function showReviews() {
        reviewsTab.classList.add('text-green-700', 'border-b-2', 'border-green-700');
        reviewsTab.classList.remove('hover:text-green-700');
        aboutTab.classList.remove('text-green-700', 'border-b-2', 'border-green-700');
        aboutTab.classList.add('hover:text-green-700');

        if (reviewsContent) reviewsContent.classList.remove('hidden');
        if (aboutContent) aboutContent.classList.add('hidden');
    }

    aboutTab.addEventListener('click', showAbout);
    reviewsTab.addEventListener('click', showReviews);
}

// ============================================
// SPACE BOOKINGS - List/Calendar Views
// ============================================
function initSpaceBookingsViews() {
    const listView = document.getElementById('list-view');
    const calendarView = document.getElementById('calendar-view');

    if (!listView || !calendarView) return;

    // Tab management within list view
    window.showTab = function(tabName) {
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('border-emerald-800', 'text-emerald-800');
            button.classList.add('border-transparent', 'text-gray-500');
        });

        const contentElement = document.getElementById('content-' + tabName);
        if (contentElement) {
            contentElement.classList.remove('hidden');
        }

        const activeTab = document.getElementById('tab-' + tabName);
        if (activeTab) {
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-emerald-800', 'text-emerald-800');
        }
    };

    // View switching (List vs Calendar)
    window.switchView = function(view) {
        const btnList = document.getElementById('btn-list-view');
        const btnCalendar = document.getElementById('btn-calendar-view');

        if (!btnList || !btnCalendar) {
            console.error('View toggle buttons not found');
            return;
        }

        if (view === 'list') {
            listView.classList.remove('hidden');
            calendarView.classList.add('hidden');
            btnList.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-400');
            btnList.classList.add('bg-emerald-200', 'text-black', 'hover:bg-emerald-400');
            btnCalendar.classList.remove('bg-emerald-200', 'text-black', 'hover:bg-emerald-400');
            btnCalendar.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-400');
        } else if (view === 'calendar') {
            listView.classList.add('hidden');
            calendarView.classList.remove('hidden');
            btnList.classList.remove('bg-emerald-200', 'text-black', 'hover:bg-emerald-400');
            btnList.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-400');
            btnCalendar.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-400');
            btnCalendar.classList.add('bg-emerald-200', 'text-black', 'hover:bg-emerald-400');

            // Initialize calendar when switching to it
            if (typeof window.initializeCalendar === 'function') {
                window.initializeCalendar();
            } else {
                console.error('Calendar initialization function not available');
            }
        }
    };
}

// ============================================
// AUTO-INITIALIZE
// ============================================
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        initSpaceDetailsTabs();
        initSpaceBookingsViews();
    });
} else {
    initSpaceDetailsTabs();
    initSpaceBookingsViews();
}