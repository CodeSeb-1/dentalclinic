<?php
session_start();

if(isset($_POST['logout'])) {
    session_destroy();

    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vannesa Nicolas Dental Clinic</title>
    <link rel="stylesheet" href="../styles/index.css?">
    <link rel="stylesheet" href="../styles/calender-form.css?">
    <link rel="stylesheet" href="../styles/profile.css">
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
                <a href="history.php">History</a>
                <a href="profile.php" class="active">
                    <div class="profile">
                        <img src="../image/dp.png" alt="Profile Picture">
                        <p><?php echo $_SESSION['fullname']; ?></p>
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
                            // Backend PHP logic to fetch notifications
                            include_once($_SERVER['DOCUMENT_ROOT'] . '/DENTALCLINICAPPOINTMENT/includes/db.php');
                            
                            date_default_timezone_set('Asia/Manila');
                            $name = $_SESSION['fullname']; // Get the logged-in user's name
                            
                            // Fetch upcoming appointments for today and beyond for the specific user
                            $query = "SELECT*FROM bookappointment WHERE name = ? ORDER BY date ASC";
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
                                    echo "<li><strong>{$appointmentName}</strong> <br> {$appointmentDate} - {$appointmentTime}<br>Status: {$appointmentStatus}</li>";
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
    <main class="main-content">
        <div class="container">
            <section class="profile-section">
                <div class="profile-picture">
                    <img src="https://via.placeholder.com/200x200" alt="Matrix Salonga">
                </div>
                <div class="profile-info">
                    <h1 class="section-title">Your Profile</h1>
                    <div class="info-group">
                        <div class="info-label">Full Name</div>
                        <div class="info-value"><?php echo $_SESSION['fullname'] ?></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Contact Number</div>
                        <div class="info-value"><?php echo $_SESSION['contactno'] ?></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Email Address</div>
                        <div class="info-value"><?php echo $_SESSION['email'] ?></div>
                    </div>
                    <form action="profile.php" method="POST">
                        <button type="submit" name="logout"  class="logout-btn">Log Out</button>
                    </form>
                </div>
            </section>
        </div>
    </main>

<script src="../javascript/notification.js"></script>
</body>
</html>
