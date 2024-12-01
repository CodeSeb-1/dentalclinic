<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/DENTALCLINICAPPOINTMENT/includes/db.php');

date_default_timezone_set('Asia/Manila');
$selectedMonth = $_GET['month'] ?? date('m');
$selectedYear = $_GET['year'] ?? date('Y');

// Query to get the available and unavailable counts per day in the selected month
$query = "
    SELECT 
        DAY(date) AS day,
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed_count,
        SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) AS cancelled_count
    FROM bookappointment
    WHERE MONTH(date) = ? AND YEAR(date) = ?
    GROUP BY DAY(date)
";
$stmt = $con->prepare($query);
$stmt->bind_param("ii", $selectedMonth, $selectedYear);
$stmt->execute();
$result = $stmt->get_result();

// Prepare data for the chart
$availableCounts = [];
$unavailableCounts = [];
$days = [];

while ($row = $result->fetch_assoc()) {
    $days[] = "Day " . $row['day'];
    $availableCounts[] = $row['completed_count'];
    $unavailableCounts[] = $row['cancelled_count'];
}
$stmt->close();
?>
