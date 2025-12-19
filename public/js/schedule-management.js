// Schedule Management JavaScript
// Wrap in IIFE to avoid global scope pollution but expose needed functions
(function() {
    'use strict';

    let currentScheduleId = null;
    let isEditMode = false;

    /**
     * Open modal to create a new schedule
     */
    window.openScheduleModal = function() {
        isEditMode = false;
        currentScheduleId = null;

        // Reset form
        document.getElementById('scheduleForm').reset();
        document.getElementById('schedule_id').value = '';

        // Set default date to selected date
        if (window.spaceData && window.spaceData.selectedDate) {
            document.getElementById('schedule_date').value = window.spaceData.selectedDate;
        }

        // Update modal title and button
        document.getElementById('scheduleModalTitle').textContent = 'Add Schedule';
        document.getElementById('scheduleSubmitText').textContent = 'Create Schedule';
        document.getElementById('scheduleDeleteBtn').classList.add('hidden');

        // Hide error message
        document.getElementById('scheduleErrorMessage').classList.add('hidden');

        // Populate time options
        populateTimeOptions();

        // Show modal
        document.getElementById('scheduleModal').classList.remove('hidden');
    };

    /**
     * Open modal to edit an existing schedule
     */
    window.editSchedule = function(scheduleId, event) {
        if (event) {
            event.stopPropagation(); // Prevent slot click event
        }

        isEditMode = true;
        currentScheduleId = scheduleId;

        // Fetch schedule data
        fetch('/api/schedules/' + scheduleId, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(function(response) {
                if (!response.ok) {
                    return response.json().then(function(data) {
                        throw new Error(data.message || 'Failed to load schedule');
                    });
                }
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    var schedule = data.schedule;

                    // Populate form
                    document.getElementById('schedule_id').value = schedule.id;
                    document.getElementById('schedule_date').value = schedule.date;
                    document.getElementById('max_capacity').value = schedule.max_capacity;

                    // Populate time options and select the current time
                    populateTimeOptions(schedule.time);

                    // Update modal title and button
                    document.getElementById('scheduleModalTitle').textContent = 'Edit Schedule';
                    document.getElementById('scheduleSubmitText').textContent = 'Update Schedule';
                    document.getElementById('scheduleDeleteBtn').classList.remove('hidden');

                    // Hide error message
                    document.getElementById('scheduleErrorMessage').classList.add('hidden');

                    // Show modal
                    document.getElementById('scheduleModal').classList.remove('hidden');
                } else {
                    showError(data.message || 'Failed to load schedule data');
                }
            })
            .catch(function(error) {
                console.error('Error fetching schedule:', error);
                showError(error.message || 'Failed to load schedule data');
            });
    };

    /**
     * Close the schedule modal
     */
    window.closeScheduleModal = function() {
        document.getElementById('scheduleModal').classList.add('hidden');
        currentScheduleId = null;
        isEditMode = false;
    };

    /**
     * Populate time options based on space opening hours and duration
     */
    function populateTimeOptions(selectedTime) {
        var timeSelect = document.getElementById('schedule_time');
        if (!timeSelect) return;

        timeSelect.innerHTML = '';

        if (!window.spaceData) {
            console.error('Space data not available');
            return;
        }

        var openingTime = window.spaceData.opening_time;
        var closingTime = window.spaceData.closing_time;
        var duration = window.spaceData.duration;

        // Parse opening and closing times
        var openParts = openingTime.split(':');
        var closeParts = closingTime.split(':');
        var openHour = parseInt(openParts[0]);
        var openMinute = parseInt(openParts[1]);
        var closeHour = parseInt(closeParts[0]);
        var closeMinute = parseInt(closeParts[1]);

        // Generate time slots
        var currentHour = openHour;
        var currentMinute = openMinute;

        while (currentHour < closeHour || (currentHour === closeHour && currentMinute < closeMinute)) {
            var hourStr = String(currentHour).padStart(2, '0');
            var minuteStr = String(currentMinute).padStart(2, '0');
            var timeString = hourStr + ':' + minuteStr;

            var option = document.createElement('option');
            option.value = timeString;
            option.textContent = timeString;

            if (selectedTime && timeString === selectedTime) {
                option.selected = true;
            }

            timeSelect.appendChild(option);

            // Add duration to current time
            currentMinute += duration;
            if (currentMinute >= 60) {
                currentHour += Math.floor(currentMinute / 60);
                currentMinute = currentMinute % 60;
            }
        }
    }

    /**
     * Delete a schedule
     */
    window.deleteSchedule = function() {
        if (!currentScheduleId) return;

        if (!confirm('Are you sure you want to delete this schedule? This action cannot be undone.')) {
            return;
        }

        fetch('/api/schedules/' + currentScheduleId, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(function(response) {
                if (!response.ok) {
                    return response.json().then(function(data) {
                        throw new Error(data.message || 'Failed to delete schedule');
                    });
                }
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    window.closeScheduleModal();
                    window.location.reload();
                } else {
                    showError(data.message || 'Failed to delete schedule');
                }
            })
            .catch(function(error) {
                console.error('Error deleting schedule:', error);
                showError(error.message || 'Failed to delete schedule. Please try again.');
            });
    };

    /**
     * Show error message in modal
     */
    function showError(message) {
        var errorDiv = document.getElementById('scheduleErrorMessage');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
        } else {
            alert(message);
        }
    }

    /**
     * Handle schedule form submission
     */
    function initializeForm() {
        var form = document.getElementById('scheduleForm');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            var submitBtn = document.getElementById('scheduleSubmitBtn');
            var submitText = document.getElementById('scheduleSubmitText');
            var submitSpinner = document.getElementById('scheduleSubmitSpinner');

            // Disable button and show spinner
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitSpinner.classList.remove('hidden');

            // Hide error message
            document.getElementById('scheduleErrorMessage').classList.add('hidden');

            // Get form data
            var formData = new FormData(this);
            var data = {
                space_id: formData.get('space_id'),
                date: formData.get('date'),
                time: formData.get('time'),
                max_capacity: formData.get('max_capacity')
            };

            // Determine endpoint and method
            var url = isEditMode ? '/api/schedules/' + currentScheduleId : '/api/space/' + data.space_id + '/schedule';
            var method = isEditMode ? 'PUT' : 'POST';

            // Send request
            fetch(url, {
                method: method,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
                .then(function(response) {
                    if (!response.ok) {
                        return response.json().then(function(data) {
                            throw new Error(data.message || 'Failed to save schedule');
                        });
                    }
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        // Close modal and reload page
                        window.closeScheduleModal();
                        window.location.reload();
                    } else {
                        // Show error message
                        showError(data.message || 'Failed to save schedule');
                        // Re-enable button
                        submitBtn.disabled = false;
                        submitText.classList.remove('hidden');
                        submitSpinner.classList.add('hidden');
                    }
                })
                .catch(function(error) {
                    console.error('Error saving schedule:', error);
                    showError(error.message || 'Failed to save schedule. Please try again.');
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitText.classList.remove('hidden');
                    submitSpinner.classList.add('hidden');
                });
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeForm);
    } else {
        initializeForm();
    }
})();