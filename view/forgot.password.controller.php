<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/DENTALCLINICAPPOINTMENT/includes/db.php');

if (isset($_POST['forgotPassword'])) {
    // Retrieve form inputs
    $email = trim($_POST['email']);
    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validate form inputs
    if (empty($email) || empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        echo "
        <script>
            alert('All fields are required.');
            window.history.back();
        </script>
        ";
        exit();

    }

    if ($newPassword !== $confirmPassword) {
        echo "
          <script>
            alert(' New password and confirm password do not match.');
            window.history.back();
        </script>";
        exit();
    }

    // Check if the email exists
    $query = "SELECT * FROM patient WHERE email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "
            <script>
                alert('No account found with that email.');
                window.history.back();
            </script>";
        exit();

    }

    $user = $result->fetch_assoc();
    $currentPasswordFromDB = $user['password']; // Plain text password from database

    // Verify current password (plain text comparison)
    if ($currentPassword !== $currentPasswordFromDB) {
        echo "
        <script>
            alert('Current password is incorrect.');
            window.history.back();
        </script>";
        exit();

    }

    // Update the password in the database
    $updateQuery = "UPDATE patient SET password = ? WHERE email = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("ss", $newPassword, $email);

    if ($updateStmt->execute()) {

        echo "
        <script>
            alert('Password updated successfully.');
            window.location.href='login.php';
        </script>";
    } else {

        echo "
        <script>
            alert('Failed to update password. Please try again later.');
            window.location.href='login.php';
        </script>";
    }
} else {
    echo "Invalid request.";
    exit();
}
?>
