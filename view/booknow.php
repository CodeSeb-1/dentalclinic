<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vannesa Nicolas Dental Clinic</title>
    <link rel="stylesheet" href="../styles/index.css?">
    <link rel="stylesheet" href="../styles/calender-form.css?">
</head>
<body>
<header>
        <nav class="container">
            <a href="#" class="logo">
                <img src="../image/image.png" alt="Logo">
            </a>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="booknow.php" class="active">Book Now</a>
                <a href="history.php">History</a>
                <a href="profile.php">
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

    <main>
        <section class="book-container">
            <div class="container">
            <form method="POST" action="booknow.controller.php">
            <p class="schedule-title-outside">Appointment Schedule</p>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Name of Client:</label>
                        <input type="text" id="name" value="<?php echo $_SESSION['fullname']; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" name="age" id="age" required>
                    </div>

                    <div class="form-group">
                        <label for="treatment">Type of Treatment:</label>
                        <select id="treatment" name="treatment" required>
                            <option value="" disabled selected>Select Option</option>
                            <option value="Tooth Removal">Tooth Removal</option>
                            <option value="Teeth Cleaning">Teeth Cleaning</option>
                            <option value="Brace Adjustment">Brace Adjustment</option>
                            <option value="Dental Implant">Dental Implant</option>
                            <option value="Crown">Crown</option>
                            <option value="Veneers">Veneers</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dentist">Dentist Name:</label>
                        <select id="dentist" name="dentistname" required>
                            <option value="" disabled selected>Select Option</option>
                            <option value="Dr. Vanessa Nicolas">Dr. Vanessa Nicolas</option>
                            <option value="Dr. Nestine Basilio">Dr. Nestine Basilio</option>
                            <option value="Dr. Anne Sioson">Dr. Anne Sioson</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" value="<?php echo $_SESSION['email'] ?>" readonly>
                    </div>
                </div>
                <br>
                <div class="appointment-container">
                    <div class="appointment-schedule">

                        <!-- Calendar Container -->
                        <div id="calendar" class="calendar-container">
                            <table class="table-condensed table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="7">
                                            <div class="calendar-header">
                                                <button class="prev-month" onclick="changeMonth(-1)">&#8249;</button>
                                                <span id="monthLabel"></span>
                                                <button class="next-month" onclick="changeMonth(1)">&#8250;</button>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Su</th>
                                        <th>Mo</th>
                                        <th>Tu</th>
                                        <th>We</th>
                                        <th>Th</th>
                                        <th>Fr</th>
                                        <th>Sa</th>
                                    </tr>
                                </thead>
                                <tbody id="calendarDays"></tbody>
                            </table>
                        </div>

                        <!-- Time Selection -->
                        <div class="time-selection-container">
                                <h3>TIME AVAILABILITY</h3>
                                <div class="time-slot-container">
                                    <div class="time-slot">
                                        <input type="checkbox" id="time1" class="time-checkbox" value="09:00-10:30AM" onclick="handleCheckboxSelection(this)" />
                                        <label for="time1">9:00 - 10:30AM</label>
                                    </div>
                                    <div class="time-slot">
                                        <input type="checkbox" id="time2" class="time-checkbox" value="10:30-12:00PM" onclick="handleCheckboxSelection(this)" />
                                        <label for="time2">10:30 - 12:00PM</label>
                                    </div>
                                    <div class="time-slot">
                                        <input type="checkbox" id="time3" class="time-checkbox" value="01:00-02:30PM" onclick="handleCheckboxSelection(this)" />
                                        <label for="time3">1:00 - 2:30PM</label>
                                    </div>
                                    <div class="time-slot">
                                        <input type="checkbox" id="time4" class="time-checkbox" value="02:30-04:00PM" onclick="handleCheckboxSelection(this)" />
                                        <label for="time4">2:30 - 4:00PM</label>
                                    </div>
                                </div>
                                <!-- Hidden inputs to pass data to forms.php -->
                                <input type="hidden" name="date" id="selectedDateInput">
                                <input type="hidden" name="time" id="selectedTimeInput">
                                <input type="hidden" name="monthName" id="monthNameInput">

                                <!-- Book Appointment Button -->
                                <button type="submit" name="bookAppointment" class="btn" id="bookAppointmentBtn">Appoint Now</button>
                                </div>

                    </div>
                </div>
            </form>
            </div>
       </section>
    </main>
    <script src="../javascript/calendar-form.js?"></script>
    <script src="../javascript/notification.js"></script>

</body>
</html>
