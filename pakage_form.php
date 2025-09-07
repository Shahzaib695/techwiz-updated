<?php
session_start();
include 'db.php';
date_default_timezone_set('Asia/Karachi');

$success = false;
$error_msg = '';

// ✅ Get package details from URL
$package_name = isset($_GET['package']) ? htmlspecialchars($_GET['package']) : '';
$packageQuery = $conn->prepare("SELECT * FROM packages WHERE name = ?");
$packageQuery->bind_param("s", $package_name);
$packageQuery->execute();
$packageResult = $packageQuery->get_result();
$package = $packageResult->fetch_assoc();

if (!$package) {
  $error_msg = 'Package not found!';
}

// ✅ Fetch staff list
$staff = [];
$staffQuery = "SELECT name FROM employees";
$staffResult = mysqli_query($conn, $staffQuery);
if ($staffResult && mysqli_num_rows($staffResult) > 0) {
  while ($row = mysqli_fetch_assoc($staffResult)) {
    $staff[] = $row['name'];
  }
}

// ✅ Form submission handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = trim($_POST['client_name']);
    $client_phone = trim($_POST['client_phone']);
    $client_email = trim($_POST['client_email']);
    $client_address = trim($_POST['client_address']);
    $staff_selected = $_POST['staff'];
    $package_name = $_POST['package'];
    $price = $_POST['price'];

    // ✅ Server-side validation
    if (!preg_match("/^[a-zA-Z ]{3,}$/", $client_name)) {
        $error_msg = "Invalid name format.";
    } elseif (!preg_match("/^[0-9]{11}$/", $client_phone)) {
        $error_msg = "Phone number must be exactly 11 digits.";
    } elseif (!filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Invalid email format.";
    } elseif (strlen($client_address) < 5) {
        $error_msg = "Address too short.";
    } elseif (empty($staff_selected)) {
        $error_msg = "Please select a staff member.";
    } else {
        $insert = $conn->prepare("INSERT INTO package_orders (client_name, phone, email, address, staff, package, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param("sssssss", $client_name, $client_phone, $client_email, $client_address, $staff_selected, $package_name, $price);

        if ($insert->execute()) {
            $success = true;
        } else {
            $error_msg = 'Something went wrong while placing your order.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Package</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
body, html {
  margin: 0;
  padding: 0;
  height: 100%;
  font-family: 'Poppins', sans-serif;
  background: #000; /* keep dark background */
}

/* Modal Overlay */
.modal-overlay {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: url('decorevistaimages/3759d98e9032719b8e11ce45eb9eb859.jpg')
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
  color: #b96e42;
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
  color: #444; /* darker label */
}

.form-group input,
.form-group select,
.form-group textarea {
  padding: 12px 15px;
  border: 1px solid rgba(0,0,0,0.15);
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.9); /* ✅ light fields */
  color: #2c3e50;
  font-size: 15px;
  transition: 0.3s ease;
}

/* Focus effect */
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #ff9966;
  box-shadow: 0 0 8px rgba(255, 153, 102, 0.5);
}

/* Dropdown Options */
.form-group select option {
  background: #fff;
  color: #2c3e50;
}

/* Full-width */
.form-group.full-width {
  grid-column: 1 / -1;
  text-align: center;
}

/* Submit Button */
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
    <div class="modal-title"><i class="fas fa-box"></i> Order Package</div>

    <form method="POST" id="orderForm">
      <div class="modal-form-grid">
        <div class="form-group">
          <label for="client_name"><i class="fas fa-user"></i> Your Name</label>
          <input type="text" name="client_name" required>
        </div>
        <div class="form-group">
          <label for="client_phone"><i class="fas fa-phone-alt"></i> Phone Number</label>
          <input type="text" name="client_phone" maxlength="11" required>
        </div>
        <div class="form-group">
          <label for="client_email"><i class="fas fa-envelope"></i> Email</label>
          <input type="email" name="client_email" required>
        </div>
        <div class="form-group">
          <label for="client_address"><i class="fas fa-map-marker-alt"></i> Address</label>
          <textarea name="client_address" rows="3" required></textarea>
        </div>
        <div class="form-group">
          <label for="staff"><i class="fas fa-user-tie"></i> Select Staff</label>
          <select name="staff" required>
            <option value="" disabled selected>Select Staff</option>
            <?php foreach ($staff as $s): ?>
              <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label><i class="fas fa-gift"></i> Package</label>
          <input type="text" name="package" value="<?= $package['name'] ?? '' ?>" readonly>
        </div>
        <div class="form-group">
          <label><i class="fas fa-dollar-sign"></i> Price</label>
          <input type="text" name="price" value="<?= $package['price'] ?? '' ?>" readonly>
        </div>
        <div class="form-group full-width">
          <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Place Order</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- SweetAlert Handlers -->
<?php if ($success): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Order Placed!',
      text: 'Your package order has been received.',
      confirmButtonColor: '#27ae60'
    }).then(() => {
      window.location.href = 'pakages.php';
    });
  </script>
<?php elseif (!empty($error_msg)): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: '<?= $error_msg ?>',
      confirmButtonColor: '#d63031'
    });
  </script>
<?php endif; ?>

<!-- JS Validation -->
<script>
document.getElementById("orderForm").addEventListener("submit", function(e) {
  const name = document.querySelector("[name='client_name']");
  const phone = document.querySelector("[name='client_phone']");
  const email = document.querySelector("[name='client_email']");
  const address = document.querySelector("[name='client_address']");
  const staff = document.querySelector("[name='staff']");

  const nameRegex = /^[a-zA-Z ]{3,}$/;
  const phoneRegex = /^[0-9]{11}$/;
  // const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;

  let isValid = true;
  let errorMsg = "";

  if (!nameRegex.test(name.value.trim())) {
    isValid = false;
    errorMsg = "Please enter a valid name (only letters, min 3 chars)";
  } else if (!phoneRegex.test(phone.value.trim())) {
    isValid = false;
    errorMsg = "Phone number must be exactly 11 digits";
  } else if (!emailRegex.test(email.value.trim())) {
    isValid = false;
    errorMsg = "Invalid email format";
  } else if (address.value.trim().length < 5) {
    isValid = false;
    errorMsg = "Address must be at least 5 characters long";
  } else if (staff.value === "") {
    isValid = false;
    errorMsg = "Please select a staff member";
  }

  if (!isValid) {
    e.preventDefault();
    Swal.fire({
      icon: 'warning',
      title: 'Validation Error!',
      text: errorMsg,
      confirmButtonColor: '#e17055'
    });
  }
});
</script>
</body>
</html>
