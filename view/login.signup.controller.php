<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/DENTALCLINICAPPOINTMENT/includes/db.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare query to check both tables
    $query = "
        SELECT 'patient' AS role, patientid, fullname, contactno, email, password FROM patient WHERE email = ?
        UNION 
        SELECT 'admin' AS role, adminid, fullname, contactno, email, password FROM admin WHERE email = ?
    ";
    $stmt = $con->prepare($query);

    if (!$stmt) {
        die("Query preparation failed: " . $con->error);
    }

    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Direct password comparison (not secure, consider using password hashing)
        if ($password === $user['password']) {

            $_SESSION['email'] = $email;
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['contactno'] = $user['contactno'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'patient') {
                $_SESSION['patientid'] = $user['patientid'];
                header("Location: index.php");
            } else if ($user['role'] === 'admin') {
                $_SESSION['adminid'] = $user['adminid'];
                header("Location: ../admin/index.php");
            }
            exit();
        } else {
            echo "<script>
                alert('Invalid email or password.');
                window.history.back();
                window.location.href='login.php';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            alert('Invalid email or password.');
            window.history.back();
            window.location.href='login.php';
        </script>";
        exit();
    }
}

if (isset($_POST['register'])) {
    $fullname = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>
            alert('Passwords do not match.');
            window.history.back();
            window.location.href='signup.php';
        </script>";
        exit();
    } else {
        // Check if email already exists in the patient table
        $query = "SELECT email FROM patient WHERE email = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>
                alert('Email is already registered.');
                window.history.back();
                window.location.href='signup.php';
            </script>";
            exit();
        } else {
            // Insert into patient table
            $query = "INSERT INTO patient (fullname, contactno, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            if (!$stmt) {
                die("Query preparation failed: " . $con->error);
            }

            $stmt->bind_param("ssss", $fullname, $contact, $email, $password);
            if ($stmt->execute()) {
                $_SESSION['email'] = $email;
                $_SESSION['fullname'] = $fullname;
                $_SESSION['contactno'] = $contact;
                header("Location: index.php");
                exit();
            } else {
                echo "<script>
                    alert('Registration failed. Please try again.');
                    window.history.back();
                    window.location.href='signup.php';
                </script>";
                exit();
            }
        }
    }
}
?>
