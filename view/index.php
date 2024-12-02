<?php
session_start();

if(empty($_SESSION['patientid']) || $_SESSION['role'] === 'admin') {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vanessa Nicolas Dental Clinic</title>
    <link rel="stylesheet" href="../styles/index.css?">
</head>
<body>
    <header>
        <nav class="container">
            <a href="#" class="logo">
                <img src="../image/image.png" alt="Logo">
            </a>
            <div class="nav-links">
                <a href="index.php" class="active">Home</a>
                <a href="booknow.php">Book Now</a>
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
                            $name = $_SESSION['email']; // Get the logged-in user's name
                            
                            // Fetch upcoming appointments for today and beyond for the specific user
                            $query = "SELECT*FROM bookappointment WHERE email = ? ORDER BY bapp_id DESC";
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
        <section class="hero">
            <div class="hero-overlay"></div>
            <div class="container hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">Your Smile, Our Passion</h1>
                    <p class="hero-description">Experience exceptional dental care with Dr. Vanessa Nicolas. Modern techniques, comfortable environment, and outstanding results.</p>
                    <div class="hero-buttons">
                        <a href="#" class="btn btn-primary">Book Now</a>
                        <a href="#" class="btn btn-outline">Learn More</a>
                    </div>
                </div>
                <div class="image-container">
                  <img src="../image/headerpic.webp" alt="Hero image">
                </div>
            </div>
        </section>

        <section id="about">
            <div class="container">
                <h2 class="section-title">About Us</h2>
                <div class="about-content">
                    <p>Achieve the ideal balance of a dazzling smile and glowing skin.</p>
                    <h3>When Great Smile Meets Glow Skin</h3>
                    <p>Founded by Dr. Vanessa Nicolas on July 8, 2016, Vanessa Nicolas Dental Clinic is committed to offering quality dental care with a focus on patient comfort and the latest dental technologies.</p>
                </div>
            </div>
        </section>

        <section id="services" class="bg-purple-50">
            <div class="container">
                <h2 class="section-title">Our Services</h2>
                <div class="services-grid">
                    <div class="service-card">
                        <img src="../image/image1.jpg" alt="Personal Care" class="service-image">
                        <div class="service-content">
                            <h3 class="service-title">Personal Care</h3>
                            <p>Ensuring your comfort with gentle, personalized care.</p>
                        </div>
                    </div>
                    <div class="service-card">
                        <img src="../image/image2.jpg" alt="Trusted Expertise" class="service-image">
                        <div class="service-content">
                            <h3 class="service-title">Trusted Expertise</h3>
                            <p>Our experienced team provides top-notch treatment.</p>
                        </div>
                    </div>
                    <div class="service-card">
                        <img src="../image/image3.jpg"  alt="Bright Smiles" class="service-image">
                        <div class="service-content">
                            <h3 class="service-title">Bright Smiles</h3>
                            <p>Helping you achieve a confident and beautiful smile.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="team">
            <div class="container">
                <h2 class="section-title">Meet the Team</h2>
                <div class="team-container">
                  <img src="../image/team.jpg" alt="Vanessa Nicolas Dental Clinic Team" class="team-image">
                </div>
            </div>
        </section>

        <section id="location" class="bg-purple-50">
            <div class="container">
                <h2 class="section-title">Our Location</h2>
                <div class="location-content">
                    <p class="location-description">We are located at 453 Gov Padilla Road, Poblacion, Plaridel, Bulacan. Visit us for all your dental needs.</p>
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3857.1907254313347!2d120.85659731534675!3d14.822859989655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397ae7c1a8d5c6d%3A0x9c0c5a4f8f5b8b0a!2s453%20Gov%20Padilla%20Rd%2C%20Plaridel%2C%20Bulacan!5e0!3m2!1sen!2sph!4v1625123456789!5m2!1sen!2sph" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact">
            <div class="container">
                <h2 class="section-title">Get In Touch</h2>
                <div class="contact-content">
                    <img src="../image/vanessa.jpg" alt="Dr. Vanessa Nicolas" class="contact-image">
                    <div class="contact-info">
                        <div class="contact-item">
                            <svg class="contact-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            453 Gov. Padilla Rd. Poblacion Plaridel Bulacan
                        </div>
                        <div class="contact-item">
                            <svg class="contact-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            0915-393-5088
                        </div>
                        <div class="contact-item">
                            <svg class="contact-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            nicolasdentalclinic@gmail.com
                        </div>
                        <div class="contact-item">
                            <svg class="contact-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            Mon - Sun: 9:00 am - 6:00 pm
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 Vanessa Nicolas Dental Clinic. All rights reserved.</p>
        </div>
    </footer>
    <script src="../javascript/notification.js"></script>
</body>
</html>