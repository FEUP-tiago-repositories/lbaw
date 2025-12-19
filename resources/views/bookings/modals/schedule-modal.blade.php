<!-- Schedule Modal -->
<div id="scheduleModal" class="hidden fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 id="scheduleModalTitle" class="text-lg font-semibold text-gray-900">Add Schedule</h3>
            <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="scheduleForm" class="space-y-4">
            <input type="hidden" id="schedule_id" name="schedule_id" value="">
            <input type="hidden" id="space_id" name="space_id" value="{{ $space->id }}">
            
            <!-- Date -->
            <div>
                <label for="schedule_date" class="block text-sm font-medium text-gray-700 mb-1">
                    Date
                </label>
                <input type="date" id="schedule_date" name="date" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            
            <!-- Time -->
            <div>
                <label for="schedule_time" class="block text-sm font-medium text-gray-700 mb-1">
                    Start Time
                </label>
                <select id="schedule_time" name="time" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <!-- Options will be populated by JavaScript based on space opening hours and duration -->
                </select>
                <p class="mt-1 text-xs text-gray-500">
                    Available times based on space hours ({{ $space->opening_time }} - {{ $space->closing_time }})
                </p>
            </div>
            
            <!-- Max Capacity -->
            <div>
                <label for="max_capacity" class="block text-sm font-medium text-gray-700 mb-1">
                    Max Capacity
                </label>
                <input type="number" id="max_capacity" name="max_capacity" min="1" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="e.g., 20">
            </div>
            
            <!-- Error Message -->
            <div id="scheduleErrorMessage" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <!-- Error message will be inserted here -->
            </div>
            
            <!-- Buttons -->
            <div class="flex gap-3 mt-2">
                <button type="button" onclick="closeScheduleModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition">
                    Cancel
                </button>
                <button type="submit" id="scheduleSubmitBtn"
                        class="flex-1 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium transition">
                    <span id="scheduleSubmitText">Create Schedule</span>
                    <span id="scheduleSubmitSpinner" class="hidden">
                        <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
            
            <!-- Delete Button (only for edit mode) -->
            <button type="button" id="scheduleDeleteBtn" onclick="deleteSchedule()" class="hidden w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition mt-2">
                Delete Schedule
            </button>
        </form>
    </div>
</div>