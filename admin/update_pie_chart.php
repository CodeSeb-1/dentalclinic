<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/DENTALCLINICAPPOINTMENT/includes/db.php');

$selectedMonth = $_GET['month'];
$selectedYear = $_GET['year'];

// Fetch unavailable days for the selected month and year
$queryUnavailable = "SELECT COUNT(DISTINCT DATE(date)) as unavailable 
                     FROM bookappointment 
                     WHERE MONTH(date) = ? AND YEAR(date) = ?";
$stmtUnavailable = $con->prepare($queryUnavailable);
$stmtUnavailable->bind_param("ii", $selectedMonth, $selectedYear);
$stmtUnavailable->execute();
$resultUnavailable = $stmtUnavailable->get_result();
$unavailableDays = $resultUnavailable->fetch_assoc()['unavailable'];

// Calculate available days
$totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
$availableDays = $totalDaysInMonth - $unavailableDays;

// Return available and unavailable days as a JSON response
echo json_encode([
    'available' => $availableDays,
    'unavailable' => $unavailableDays
]);
?>
