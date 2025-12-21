{{-- Schedule Modal --}}
<div id="scheduleModal" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 border">
        {{-- Header --}}
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h3 id="scheduleModalTitle" class="text-xl font-bold text-gray-900">Add Schedule</h3>
            <button type="button" onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Error Message --}}
        <div id="scheduleErrorMessage" class="hidden mx-6 mt-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg"></div>

        {{-- Form --}}
        <form id="scheduleForm" class="px-6 py-4">
            <input type="hidden" name="space_id" id="space_id" value="{{ $space->id }}">
            <input type="hidden" name="schedule_id" id="schedule_id" value="">

            {{-- Start Date --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Start Date <span class="text-red-500">*</span>
                </label>
                <input type="date"
                       name="date"
                       id="schedule_date"
                       min="{{ date('Y-m-d') }}"
                       class="text-gray-700 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       required>
            </div>

            {{-- Recurrence Checkbox --}}
            <div class="mb-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox"
                           id="is_recurring"
                           name="is_recurring"
                           class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500"
                           onchange="toggleRecurrence()">
                    <span class="text-sm font-medium text-gray-700">Recurring schedule</span>
                </label>
            </div>

            {{-- End Date (hidden by default) --}}
            <div id="end_date_container" class="mb-4 hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    End Date <span class="text-red-500">*</span>
                </label>
                <input type="date"
                       name="end_date"
                       id="schedule_end_date"
                       min="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <p class="mt-1 text-xs text-gray-500">
                    Schedules will be created for all dates from start to end
                </p>
            </div>

            {{-- All Day Checkbox --}}
            <div class="mb-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox"
                           id="all_day"
                           name="all_day"
                           class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500"
                           onchange="toggleAllDay()">
                    <span class="text-sm font-medium text-gray-700">All day (all time slots)</span>
                </label>
            </div>

            {{-- Time (hidden when "all day" is checked) --}}
            <div id="time_container" class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Start Time <span class="text-red-500">*</span>
                </label>
                <select name="time"
                        id="schedule_time"
                        class="text-gray-700 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="">Select first a start date...</option>
                </select>
            </div>

            {{-- Info Box when All Day is selected --}}
            <div id="all_day_info" class="mb-4 hidden">
                <div class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        All time slots will be created from <strong>{{ $space->opening_time }}</strong> to <strong>{{ $space->closing_time }}</strong>
                    </p>
                </div>
            </div>

            {{-- Max Capacity --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Maximum Capacity <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="max_capacity"
                       id="max_capacity"
                       min="1"
                       placeholder="e.g., 10"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       required>
                <p class="mt-1 text-xs text-gray-500">
                    Maximum number of simultaneous bookings
                </p>
            </div>

            {{-- Summary Box --}}
            <div id="schedule_summary" class="mb-4 hidden">
                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Summary:</p>
                    <p id="summary_text" class="text-sm text-gray-600"></p>
                </div>
            </div>

            {{-- Footer Buttons --}}
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button"
                        onclick="closeScheduleModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition">
                    Cancel
                </button>

                <button type="button"
                        id="scheduleDeleteBtn"
                        onclick="deleteSchedule()"
                        class="hidden flex-1 px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 font-semibold transition">
                    Delete
                </button>

                <button type="submit"
                        id="scheduleSubmitBtn"
                        class="flex-1 px-4 py-2 bg-emerald-800 text-white rounded-xl hover:bg-emerald-700 font-semibold transition flex items-center justify-center gap-2">
                    <svg id="scheduleSubmitSpinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="scheduleSubmitText">Create Schedule</span>
                </button>
            </div>
        </form>
    </div>
</div>
