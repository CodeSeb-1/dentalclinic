<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/DENTALCLINICAPPOINTMENT/includes/db.php');

if (isset($_GET['bapp_id'])) {
    $bapp_id = $_GET['bapp_id'];
    $bapp_id = $con->real_escape_string($bapp_id);

    // Update the status to 'rejected' for the selected appointment
    $query = "UPDATE bookappointment SET status = 'Cancelled' WHERE bapp_id = '$bapp_id' AND status = 'Complete'";

    if ($con->query($query) === TRUE) {
        // On success, redirect back to the appointments page
        echo "<script>
                alert('Appointment rejected successfully!');
                window.location.href = 'appointment.php'; // Redirect to the appointments page
              </script>";
    } else {
        // Error: show an alert
        echo "<script>
                alert('Error: " . $con->error . "');
                window.location.href = 'appointment.php'; // Redirect to the appointments page
              </script>";
    }
} else {
    // If bapp_id is not provided, show an error
    echo "<script>
            alert('Invalid request');
            window.location.href = 'appointment.php'; // Redirect to the appointments page
          </script>";
}
?>
