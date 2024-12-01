<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/DENTALCLINICAPPOINTMENT/includes/db.php');

$name = $_SESSION['fullname']; // Fetch the full name from the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vanessa Nicolas Dental Clinic</title>
    <link rel="stylesheet" href="../styles/index.css?">
    <link rel="stylesheet" href="../styles/calender-form.css?">
    <link rel="stylesheet" href="../styles/history-user.css">
</head>
<body>
<header>
    <nav class="container">
        <a href="#" class="logo">
            <img src="../image/image.png" alt="Logo">
        </a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="booknow.php">Book Now</a>
            <a href="history.php" class="active">History</a>
            <a href="profile.php">
                <div class="profile">
                    <img src="../image/dp.png" alt="Profile Picture">
                    <p><?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
                </div>
            </a>
            <!-- Notification Icon -->
            <div class="notification-container">
                <div class="notification-icon" id="notification-icon">
                    <span>&#128276;</span> <!-- Bell icon -->
                </div>
                <div class="notification-popup" id="notification-popup">
                    <ul id="notification-list">
                        <?php
                            date_default_timezone_set('Asia/Manila');
                            
                            // Fetch upcoming appointments for today and beyond for the specific user
                            $query = "SELECT date, time, status, treatment FROM bookappointment WHERE name = ? ORDER BY date ASC";
                            $stmt = $con->prepare($query);
                            $stmt->bind_param("s", $name); // Bind the name parameter to avoid SQL injection
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            // Check if there are any appointments
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Format the date as Month Day, Year (e.g., December 1, 2024)
                                    $appointmentDate = date('F j, Y', strtotime($row['date'])); // Format as Month Day, Year
                                    $appointmentTime = date('h:i A', strtotime($row['time'])); // Format time as 12-hour with AM/PM
                                    $appointmentStatus = ucfirst($row['status']); // Capitalize first letter of status
                                    $appointmentName = htmlspecialchars($row['treatment']); // Escape special characters in name for security
                                
                                    // Display each notification with name, date, time, and status
                                    echo "<li><strong>{$appointmentName}</strong><br>{$appointmentDate} - {$appointmentTime}<br>Status: {$appointmentStatus}</li>";
                                }
                            } else {
                                echo "<li>No upcoming appointments</li>";
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

<main>
    <section class="content-section">
        <div class="container">
            <h2>History Appointments</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch historical appointments
                    $queryHistory = "SELECT name, date, time, status FROM bookappointment WHERE name = ? ORDER BY date DESC";
                    $stmtHistory = $con->prepare($queryHistory);
                    $stmtHistory->bind_param("s", $name);
                    $stmtHistory->execute();
                    $resultHistory = $stmtHistory->get_result();

                    if ($resultHistory->num_rows > 0) {
                        while ($row = $resultHistory->fetch_assoc()) {
                            // Set class based on the status
                            $statusClass = '';
                            $statusText = '';

                            switch ($row['status']) {
                                case 'Completed':
                                    $statusClass = 'accepted';
                                    $statusText = 'Completed';
                                    break;
                                case 'Cancelled':
                                    $statusClass = 'rejected';
                                    $statusText = 'Cancelled';
                                    break;
                                default:
                                    $statusClass = 'pending';
                                    $statusText = 'Pending';
                                    break;
                            }

                            // Format date as Month Day, Year
                            $date = new DateTime($row['date']);
                            $formattedDate = $date->format('F j, Y'); // Example format: November 30, 2024

                            echo "<tr>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($formattedDate) . "</td> <!-- Formatted Date -->
                                <td>" . htmlspecialchars($row['time']) . "</td>
                                <td><span class='status {$statusClass}'>{$statusText}</span></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No appointments found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</main>


<script src="../javascript/calendar-form.js"></script>
<script src="../javascript/notification.js"></script>

</body>
</html>
