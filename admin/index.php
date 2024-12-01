<?php
include_once('index.controller.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Clinic Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="stylesheet" href="../styles/calendar.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Pie chart for Available vs Unavailable appointments
        const availableAppointments = <?php echo json_encode($availableDays); ?>;
        const unavailableAppointments = <?php echo json_encode($unavailableDays); ?>;

        const ctxPie = document.getElementById('appointmentsPieChart').getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Available', 'Unavailable'],
                datasets: [{
                    data: [availableAppointments, unavailableAppointments],  // Initial data
                    backgroundColor: ['green', 'red'],  // Segment colors
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Availability Overview', // Title for the pie chart
                        font: {
                            size: 18
                        },
                        padding: {
                            bottom: 10
                        }
                    },
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const dataset = tooltipItem.dataset;
                                const currentValue = dataset.data[tooltipItem.dataIndex];
                                const total = dataset.data.reduce((sum, value) => sum + value, 0);
                                const percentage = ((currentValue / total) * 100).toFixed(2);
                                return `${tooltipItem.label}: ${currentValue} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Bar chart for Total, Completed, and Cancelled appointments
        const totalAppointments = <?php echo json_encode($totalAppointments); ?>;
        const completedAppointments = <?php echo json_encode($completedAppointments); ?>;
        const cancelledAppointments = <?php echo json_encode($cancelledAppointments); ?>;

        const ctxBar = document.getElementById('appointmentsBarChart').getContext('2d');
        const barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Total', 'Completed', 'Cancelled'],  // Labels for the bar chart
                datasets: [{
                    label: 'Appointments',
                    data: [totalAppointments, completedAppointments, cancelledAppointments],  // Data points
                    backgroundColor: ['blue', 'green', 'red'],  // Bar colors
                    borderColor: ['#003366', '#51ce57', '#ce5151'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Appointments Status Overview', // Title for the bar chart
                        font: {
                            size: 18
                        },
                        padding: {
                            bottom: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const dataset = tooltipItem.dataset;
                                const currentValue = dataset.data[tooltipItem.dataIndex];
                                return tooltipItem.label + ': ' + currentValue;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
    </script>
    <style>
        .calendar .day_num .events-container {
            max-height: 150px; 
            overflow-y: auto;
            margin-top: 5px;
        }

        #appointmentsPieChart {
            width: 300px;
            height: 300px;
            margin: 0 auto;
        }

       #appointmentsBarChart {
            width: 500px;
            height: 400px;
            margin: 0 auto;
        }

        .calendar .day_num .event {
            margin: 5px 0;
            padding: 5px;
            border-radius: 4px;
            font-size: 12px;
            background-color: #f7c30d; 
            color: #fff;
            word-wrap: break-word;
        }

        .box-container {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .box {
            width: auto;
            height: 30px;
            border-radius: 3px;
            padding: 5px 15px;
            color: #fff;
            font-size: 13px;
        }

        .box p {
            margin-top: 3px;
        }

        .completed {
            background: #51ce57;
        }

        .cancelled {
            background-color: #ce5151;
        }

        .can {
            display: flex;
            align-items: center;
            justify-content: space-around;
        }
    </style>
</head>
<body>
    <nav class="sidebar">
        <img src="../image/image.png" alt="Dental Clinic Logo" class="logo">
        <div class="nav-item">
            <a href="index.php" class="active">Dashboard</a>
        </div>
        <div class="nav-item">
            <a href="appointment.php">Appointments</a>
        </div>
        <div class="nav-item">
            <a href="history.php">History</a>
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
                    echo"<script>window.location.href='../view/login.php';</script>";
                }
            ?>
        </header>

        <section class="content-section">
            <h2>Overview</h2>
            <!-- Pie chart for available and unavailable appointments -->
            <div class="can">
                <div>
                <canvas id="appointmentsPieChart"></canvas>
                </div>
                <div>
                <canvas id="appointmentsBarChart"></canvas>

                </div>
            <!-- Bar chart for total, completed, and cancelled appointments -->
            </div>
        </section>

        <section class="content-section">
            <h1>Calendar</h1><br>
            <form method="GET" action="" id="calendar-id">
                <label for="month">Select Month:</label>
                <select name="month" id="month" onchange="this.form.submit()">
                    <?php
                    for ($m = 1; $m <= 12; $m++) {
                        $monthName = date('F', mktime(0, 0, 0, $m, 10)); 
                        $selected = ($m == $selectedMonth) ? 'selected' : ''; 
                        echo "<option value='$m' $selected>$monthName</option>";
                    }
                    ?>
                </select>

                <label for="year">Select Year:</label>
                <select name="year" id="year" onchange="this.form.submit()">
                    <?php
                    $currentYear = date('Y');
                    for ($y = $currentYear - 5; $y <= $currentYear + 5; $y++) {
                        $selected = ($y == $selectedYear) ? 'selected' : ''; 
                        echo "<option value='$y' $selected>$y</option>";
                    }
                    ?>
                </select>
            </form>

            <div class="calendar-container">
                <?php echo $calendar; ?>
            </div>
        </section>
    </main>
</body>
</html>
