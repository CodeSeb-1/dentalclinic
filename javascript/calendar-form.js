let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();
// Render the calendar
function renderCalendar() {
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    let calendarDays = '';
    // Add blank cells for days before the 1st of the month
    for (let i = 0; i < firstDay; i++) {
        calendarDays += '<td class="muted"></td>';
    }
    // Add days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        if ((firstDay + day - 1) % 7 === 0) calendarDays += '<tr>'; // Start a new row
        calendarDays += `<td onclick="selectDate(this)" data-day="${day}" id="day-${day}">${day}</td>`;
        if ((firstDay + day) % 7 === 0) calendarDays += '</tr>'; // Close the row
    }
    // Update the calendar display
    document.getElementById('calendarDays').innerHTML = calendarDays;
    
    document.getElementById('monthLabel').textContent = `${getMonthName(currentMonth)} ${currentYear}`;
    // Check availability for each date in the month
    checkMonthAvailability();
}
// Get the name of the month
function getMonthName(monthIndex) {
    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    return monthNames[monthIndex];
}
// Select a date and fetch available time slots
function selectDate(cell) {
    const day = cell.textContent.trim();
    const month = currentMonth + 1; // Month is 0-indexed
    const year = currentYear;
    const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.padStart(2, '0')}`;
    document.getElementById('selectedDateInput').value = formattedDate;
    // Highlight the selected date
    const calendarCells = document.querySelectorAll('#calendarDays td');
    calendarCells.forEach(cell => cell.classList.remove('selected'));
    cell.classList.add('selected');
    const buttons = document.querySelectorAll('.circle-btn');
    buttons.forEach(button => button.classList.remove('selected'));
    // Fetch availability for the selected date
    fetchAvailability(formattedDate);
}
// Fetch available time slots from the server
function fetchAvailability(date) {
    fetch(`check_availability.php?date=${date}`)
        .then(response => response.json())
        .then(data => updateTimeSlots(data, date))
        .catch(error => console.error('Error fetching availability:', error));
}
// Update time slot buttons based on availability
function updateTimeSlots(availability, date) {
    const checkboxes = document.querySelectorAll('.time-checkbox');
    let allSlotsUnavailable = true;
    checkboxes.forEach(checkbox => {
        const time = checkbox.value;
        
        // Check if the time slot is available in the availability data
        if (availability[time]) {
            checkbox.disabled = false;  // Enable the time slot checkbox
            checkbox.classList.remove('disabled');  // Remove 'disabled' class for styling
            allSlotsUnavailable = false; // At least one slot is available
        } else {
            checkbox.disabled = true;  // Disable the time slot checkbox
            checkbox.classList.add('disabled');  // Add 'disabled' class for styling
        }
    });
    // Reset selected time when availability changes
    document.getElementById('selectedTimeInput').value = '';
    document.getElementById('bookAppointmentBtn').disabled = allSlotsUnavailable; // Disable booking if all slots are unavailable
    // Change the background color of the date cell based on availability
    changeDateBackgroundColor(date, allSlotsUnavailable);
}
// Change the background color of the date cell to red if all slots are unavailable
function changeDateBackgroundColor(date, allSlotsUnavailable) {
    const day = new Date(date).getDate();
    const dateCell = document.getElementById(`day-${day}`);
    if (allSlotsUnavailable) {
        dateCell.style.backgroundColor = 'red'; // Change background to red if fully booked
        dateCell.style.color = 'white'; // Optional: Change text color to white
    } else {
        dateCell.style.backgroundColor = ''; // Reset background if slots are available
        dateCell.style.color = ''; // Reset text color
    }
}
// Check availability for each date in the month
function checkMonthAvailability() {
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    let availabilityPromises = [];
    // Create an array of promises for each day in the month
    for (let day = 1; day <= daysInMonth; day++) {
        const formattedDate = `${currentYear}-${(currentMonth + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
        const availabilityPromise = fetch(`check_availability.php?date=${formattedDate}`)
            .then(response => response.json())
            .then(data => {
                const isAvailable = Object.values(data).some(timeSlot => timeSlot); // Check if any time slot is available
                return { day, isAvailable };
            })
            .catch(error => {
                console.error('Error fetching availability:', error);
                return { day, isAvailable: false }; // If there's an error, assume the day is fully booked
            });
        availabilityPromises.push(availabilityPromise);
    }
    // Wait for all the fetch requests to complete
    Promise.all(availabilityPromises)
        .then(results => {
            results.forEach(({ day, isAvailable }) => {
                const dateCell = document.getElementById(`day-${day}`);
                if (isAvailable) {
                    dateCell.style.backgroundColor = ''; // No appointment
                    dateCell.style.color = ''; // Reset text color
                } else {
                    dateCell.style.backgroundColor = 'red'; // Fully booked
                    dateCell.style.color = 'white'; // Optional: Make the text white to improve contrast
                }
            });
        });
}
// Initialize the calendar on page load
renderCalendar();
// Handle previous month button
document.querySelector('.prev-month').addEventListener('click', function() {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    renderCalendar();
});
// Handle next month button
document.querySelector('.next-month').addEventListener('click', function() {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar();
});
// Handle checkbox selection (ensure only one checkbox is selected at a time)
function handleCheckboxSelection(checkbox) {
    const checkboxes = document.querySelectorAll('.time-checkbox');
    checkboxes.forEach(box => {
        if (box !== checkbox) box.checked = false; // Uncheck other checkboxes
    });
    document.getElementById('selectedTimeInput').value = checkbox.value;
}