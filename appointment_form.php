<?php
session_start();
include 'db.php';
date_default_timezone_set('Asia/Karachi');

$success = false;
$error_msg = '';

// ✅ Fetch service prices from DB
$services = [];
$serviceQuery = "SELECT service_name, amount FROM services";
$serviceRes = $conn->query($serviceQuery);
if ($serviceRes && $serviceRes->num_rows > 0) {
    while ($row = $serviceRes->fetch_assoc()) {
        $services[$row['service_name']] = $row['amount'];
    }
}

// Get designer id and name from GET
$designer_id = isset($_GET['designer_id']) ? intval($_GET['designer_id']) : 0;
$designer_name = isset($_GET['designer_name']) ? $_GET['designer_name'] : 'Unknown';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $designer_id = intval($_POST['designer_id']);
    $client_name = $_POST['client_name'];
    $client_phone = $_POST['client_phone'];
    $service = $_POST['service'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $amount = $_POST['amount'];
    $status = 'Pending';

    // ✅ Server-side regex validation
    if (!preg_match("/^[A-Za-z\s]{3,50}$/", $client_name)) {
        $error_msg = 'Name must contain only letters and spaces (3-50 characters).';
    } elseif (!preg_match("/^03[0-9]{9}$/", $client_phone)) {
        $error_msg = 'Enter a valid 11-digit Pakistani phone number starting with 03.';
    } else {
        // ✅ Check if slot already booked for this designer
        $checkQuery = "SELECT * FROM appointments WHERE designer_id = ? AND date = ? AND time = ? AND status = 'Approved'";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("iss", $designer_id, $appointment_date, $appointment_time);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_msg = 'Sorry, this slot is already booked and approved.';
        } else {
            $insertQuery = "INSERT INTO appointments (designer_id, client_name, phone, service, date, time, amount, status)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("isssssss", $designer_id, $client_name, $client_phone, $service, $appointment_date, $appointment_time, $amount, $status);
            if ($insertStmt->execute()) {
                $success = true;
            } else {
                $error_msg = 'Something went wrong while booking.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Appointment</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    body, html {
  margin: 0;
  padding: 0;
  height: 100%;
  font-family: 'Poppins', sans-serif;
  background: #000; /* Dark background for contrast */
  color: #2c3e50; /* Default text color */
}

/* Modal Overlay */
.modal-overlay {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg')
              no-repeat center center/cover;
  backdrop-filter: blur(6px);
}

/* Modal Box */
.modal-box {
  background: rgba(0, 0, 0, 0.7); /* ✅ transparent black glass */
  border-radius: 20px;
  box-shadow: 0 0 25px rgba(255, 255, 255, 0.15); /* halka glow */
  padding: 30px 40px;
  width: 100%;
  max-width: 800px;
  color: #fff; /* text white */
  backdrop-filter: blur(12px);
  animation: fadeIn 0.5s ease-in-out;
  border: 1px solid rgba(255, 255, 255, 0.1); /* subtle border */
}
/* Title */
.modal-title {
  text-align: center;
  font-size: 28px;
  font-weight: bold;
  margin-bottom: 30px;
  color: #b96e42; /* warm accent */
}

.modal-title i {
  margin-right: 10px;
  color: #b96e42;
}

/* Form Grid */
.modal-form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  font-size: 14px;
  margin-bottom: 8px;
  color: #444; /* Darker label for contrast */
}

.form-group input,
.form-group select,
.form-group textarea {
  padding: 12px 15px;
  border: 1px solid rgba(0,0,0,0.15);
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.9); /* ✅ light input */
  color: #2c3e50; /* Dark text */
  font-size: 15px;
  transition: 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #ff9966;
  box-shadow: 0 0 8px rgba(255, 153, 102, 0.5);
}

/* Full-width group */
.form-group.full-width {
  grid-column: 1 / -1;
  text-align: center;
}

/* Button */
.btn-submit {
  padding: 12px 30px;
  border: none;
  border-radius: 10px;
  background-color: #d4a373;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.2s;
  box-shadow: 0 4px 12px rgba(212, 163, 115, 0.4);
}

.btn-submit:hover {
  background-color: #b88a5e;
  transform: scale(1.03);
  box-shadow: 0 6px 16px rgba(212, 163, 115, 0.6);
}

.btn-submit i {
  margin-right: 8px;
}

/* Dropdown Options */
option {
  background-color: #fff;
  color: #2c3e50;
}

/* Fade in Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

</style>
</head>
<body>
<div class="modal-overlay">
  <div class="modal-box">
    <div class="modal-title"><i class="fas fa-calendar-check"></i> Book an Appointment</div>
    <form method="POST" id="appointmentForm">
      <input type="hidden" name="designer_id" value="<?= $designer_id ?>">
      <div class="modal-form-grid">
        <div class="form-group">
          <label for="designer_name"><i class="fas fa-user-tie"></i> Designer Name</label>
          <input type="text" name="designer_name" id="designer_name" value="<?= htmlspecialchars($designer_name) ?>" readonly>
        </div>
        <div class="form-group">
          <label for="client_name"><i class="fas fa-user"></i> Your Name</label>
          <input type="text" name="client_name" id="client_name" required>
        </div>
        <div class="form-group">
          <label for="client_phone"><i class="fas fa-phone-alt"></i> Phone Number</label>
          <input type="text" name="client_phone" id="client_phone" maxlength="11" required>
        </div>
        <div class="form-group">
          <label for="service"><i class="fas fa-concierge-bell"></i> Service</label>
          <select name="service" id="service" required onchange="updateAmount()">
            <option value="" disabled selected>Select a service</option>
            <?php foreach ($services as $svc => $amt): ?>
              <option value="<?= htmlspecialchars($svc) ?>" data-price="<?= $amt ?>"><?= htmlspecialchars($svc) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="amount"><i class="fas fa-dollar-sign"></i> Amount</label>
          <input type="text" name="amount" id="amount" readonly required>
        </div>
        <div class="form-group">
          <label for="appointment_date"><i class="fas fa-calendar-alt"></i> Date</label>
          <input type="date" name="appointment_date" id="appointment_date" required>
        </div>
        <div class="form-group">
          <label for="appointment_time"><i class="fas fa-clock"></i> Time</label>
          <input type="time" name="appointment_time" id="appointment_time" required>
        </div>
        <div class="form-group full-width">
          <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Confirm Appointment</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php if ($success): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Appointment Booked!',
  text: 'Your appointment is pending approval.',
  confirmButtonColor: '#27ae60'
}).then(() => {
  window.location.href = 'Detail.php';
});
</script>
<?php elseif (!empty($error_msg)): ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Booking Failed',
  text: '<?= $error_msg ?>',
  confirmButtonColor: '#d63031'
});
</script>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const today = new Date().toISOString().split("T")[0];
  document.getElementById("appointment_date").setAttribute("min", today);

  const form = document.getElementById("appointmentForm");
  form.addEventListener("submit", function (e) {
    const name = document.getElementById("client_name");
    const phone = document.getElementById("client_phone");

    const namePattern = /^[A-Za-z\s]{3,50}$/;
    const phonePattern = /^03[0-9]{9}$/;

    let error = '';
    if (!namePattern.test(name.value.trim())) {
      error = "Name must contain only letters and spaces (3-50 characters).";
    } else if (!phonePattern.test(phone.value.trim())) {
      error = "Please enter a valid 11-digit phone number starting with 03.";
    }

    if (error) {
      e.preventDefault();
      Swal.fire({ icon:'error', title:'Validation Error', text:error, confirmButtonColor:'#d63031' });
    }
  });
});

function updateAmount() {
  const selected = document.getElementById("service").selectedOptions[0];
  const price = selected.getAttribute("data-price");
  document.getElementById("amount").value = price || '';
}
</script>
</body>
</html>
