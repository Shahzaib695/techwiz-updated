<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $staff = $_POST['selectedWorkerName'];
    $name = $_POST['clientName'];
    $phone = $_POST['clientPhone'];
    $service = $_POST['service'];
    $date = $_POST['appointmentDate'];
    $time = $_POST['appointmentTime'];

    $query = "INSERT INTO appointments (staff, name, phone, service, date, time, status) 
              VALUES ('$staff', '$name', '$phone', '$service', '$date', '$time', 'pending')";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo '<script>alert("Appointment booked successfully!"); window.location.href = "view_appointment.php";</script>';
    } else {
        echo '<script>alert("Error booking appointment. Try again."); window.location.href = "contact.php";</script>';
    }
}
?>
