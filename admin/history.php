<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/DENTALCLINICAPPOINTMENT/includes/db.php');

$query = "SELECT * FROM bookappointment";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Clinic Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/admin.css">
</head>
<body>
    <nav class="sidebar">
        <img src="../image/image.png" alt="Dental Clinic Logo" class="logo">
        <div class="nav-item">
            <a href="index.php">Dashboard</a>
        </div>
        <div class="nav-item">
            <a href="appointment.php">Appointments</a>
        </div>
        <div class="nav-item">
            <a href="history.php" class="active">History</a>
        </div>
    </nav>

    <main class="main-content">
        <header class="header">
            <h1>Admin Dashboard</h1>
            <form method="POST">
                <button type="submit" name="logout" class="action-button">Logout</button>
            </form>
            <?php
                if(isset($_POST['logout'])) {
                    session_destroy();

                    header("Location: ../view/login.php");
                    exit();
                }
            ?>
        </header>

        <section class="content-section">
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
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
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
                            }

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
        </section>
    </main>
</body>
</html>
