<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/DENTALCLINICAPPOINTMENT/includes/db.php');

if (isset($_POST['action']) && isset($_POST['bapp_id'])) {
    $bapp_id = $_POST['bapp_id'];
    $action = $_POST['action'];

    $bapp_id = $con->real_escape_string($bapp_id);
    $action = $con->real_escape_string($action);

    if ($action === 'accept') {
        $query = "UPDATE bookappointment SET status = 'Completed' WHERE bapp_id = '$bapp_id'";
    } elseif ($action === 'reject') {
        // For reject action, confirm before updating
        echo "<script>
                if (confirm('Are you sure you want to reject this appointment?')) {
                    window.location.href = 'reject_appointment.php?bapp_id=$bapp_id'; // Redirect to the reject handler
                } else {
                    window.location.href = 'appointment.php'; // Redirect back if not confirmed
                }
              </script>";
        exit();
    } else {
        die("Invalid action");
    }

    if ($con->query($query) === TRUE) {
        // Success: show an alert and redirect
        echo "<script>
                alert('Appointment {$action}ed successfully!');
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
    // Invalid request: show an alert
    echo "<script>
            alert('Invalid request');
            window.location.href = 'appointment.php'; // Redirect to the appointments page
          </script>";
}
?>
