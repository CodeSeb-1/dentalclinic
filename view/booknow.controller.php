<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/DENTALCLINICAPPOINTMENT/includes/db.php');

if(isset($_POST['bookAppointment'])) {
    // Retrieve form data
    $fullname = $_SESSION['fullname'];
    $email = $_SESSION['email'];
    
    $age = $_POST['age'];
    $treatment = $_POST['treatment'];
    $dentistname = $_POST['dentistname'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $duration = "1 hour and 30 minutes";
    $status = "Complete";
    $checker = "check";

    if (empty($date) || empty($time) || empty($age) || empty($treatment) || empty($dentistname)) {
        echo "<script>
                alert('All Fields Are Required.');
                window.history.back();
                window.location.href='booknow.php';
            </script>";
    } else {
        // Insert into database
        $stmt = $con->prepare("INSERT INTO bookappointment 
            (name, age, treatment, duration, dentistname, email, status, date, time, checker) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param(
                "sissssssss",
                $fullname,
                $age,
                $treatment,
                $duration,
                $dentistname,
                $email,
                $status,
                $date,
                $time,
                $checker
            );
            if ($stmt->execute()) {
                // Success message
                $success_message = "Appointment successfully scheduled!";
                echo "<script>alert('$success_message'); window.location.href='booknow.php';</script>";
            } else {
                echo "<script>
                    alert('1');
                    window.history.back();
                    window.location.href='booknow.php';
                </script>";            }
            $stmt->close();
        } else {
            echo "<script>
                alert('2');
                window.history.back();
                window.location.href='booknow.php';
            </script>";        
        }
    }
}