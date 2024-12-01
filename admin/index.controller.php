<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/DENTALCLINICAPPOINTMENT/includes/db.php');

date_default_timezone_set('Asia/Manila');
include_once('calendar.php');

$selectedMonth = $_GET['month'] ?? date('m');
$selectedYear = $_GET['year'] ?? date('Y');

// Initialize the Calendar
$calendarObj = new Calendar("$selectedYear-$selectedMonth-01");

// Fetch appointments from the database for the selected month and year
$query = "SELECT *FROM bookappointment 
          WHERE MONTH(date) = ? AND YEAR(date) = ? ORDER BY bapp_id DESC";
$stmt = $con->prepare($query);
$stmt->bind_param("ii", $selectedMonth, $selectedYear);
$stmt->execute();
$result = $stmt->get_result();

// Add events to the calendar
while ($row = $result->fetch_assoc()) {
    $eventDate = $row['date'];
    $eventText = "<i>" . $row['name'] . "</i> (<i>" . $row['time'] . "</i>)<br><b>Doctor:</b> <i>" . $row['dentistname'] . "</i><br><b>Treatment:</b> <i>" . $row['treatment'] . "</i>";
    $eventStatus = $row['status'];

    // Conditionally set color based on status
    if ($eventStatus == 'Complete') {
        $eventColor = 'yellow'; // Completed appointments are green
    } else if ($eventStatus === "Completed") { 
        $eventColor = 'green'; // Completed appointments are green
    }else {
        $eventColor = 'red'; // Non-completed (e.g., cancelled) appointments are red
    }

    // Add the event to the calendar with the appropriate color
    $calendarObj->add_event($eventText, $eventDate, 1, $eventColor);
}

// Convert calendar to string for rendering
$calendar = $calendarObj->__toString();

// Fetch counts for completed, cancelled, and total appointments
$queryStatusCount = "SELECT 
                        COUNT(*) AS total,
                        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed,
                        SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) AS cancelled
                     FROM bookappointment 
                     WHERE MONTH(date) = ? AND YEAR(date) = ?";
$stmtStatusCount = $con->prepare($queryStatusCount);
$stmtStatusCount->bind_param("ii", $selectedMonth, $selectedYear);
$stmtStatusCount->execute();
$resultStatusCount = $stmtStatusCount->get_result();
$rowStatusCount = $resultStatusCount->fetch_assoc();

$totalAppointments = $rowStatusCount['total'];
$completedAppointments = $rowStatusCount['completed'];
$cancelledAppointments = $rowStatusCount['cancelled'];

// Query to count the number of days with appointments (unavailable days)
// Query to count the total number of appointments (unavailable days) in the selected month
$queryUnavailable = "SELECT COUNT(*) AS unavailable 
                     FROM bookappointment 
                     WHERE MONTH(date) = ? AND YEAR(date) = ?";
$stmtUnavailable = $con->prepare($queryUnavailable);
$stmtUnavailable->bind_param("ii", $selectedMonth, $selectedYear);
$stmtUnavailable->execute();
$resultUnavailable = $stmtUnavailable->get_result();
$unavailableAppointments = $resultUnavailable->fetch_assoc()['unavailable'];

// Calculate total days in the selected month
$totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);

// Calculate unavailable days as the number of days with appointments
$unavailableDays = ceil($unavailableAppointments / 1); // Each appointment counts as an unavailable day

// Calculate available days as the total days in the month minus unavailable days
$availableDays = $totalDaysInMonth - $unavailableDays;

?>