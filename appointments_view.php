<?php
session_start();
include 'db.php';

date_default_timezone_set('Asia/Karachi');

// Admin login check
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.com') {
    header("Location: login.php");
    exit;
}

// Handle Approve/Reject POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['action'])) {
    $id = intval($_POST['id']);
    $status = $_POST['action'];

    if (in_array($status, ['Approved', 'Rejected'])) {
        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
    }
}

// Fetch all appointments
$sql = "SELECT * FROM appointments ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointments - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
  :root {
  --brand-accent: #d4a373; /* DecorVista gold */
  --brand-hover: #b89560;
  --text-light: #f1f1f1;
  --bg-dark: #121212;
  --card-bg: rgba(255, 255, 255, 0.06);
  --sidebar-width: 250px;
  --glass-blur: blur(12px);
  --border-color: rgba(212,163,115,0.25);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background: linear-gradient(180deg, rgba(212,163,115,0.05), rgba(0,0,0,0.6)), url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  color: var(--text-light);
  padding: 60px 20px 20px var(--sidebar-width);
  position: relative;
}

body::before {
  content: '';
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.65);
  z-index: -1;
}

/* ---------- BOX (FORM & CONTENT CARDS) ---------- */
.box {
  max-width: 1000px;
  margin: auto;
  background: var(--card-bg);
  padding: 30px;
  border-radius: 18px;
  box-shadow: 0 0 22px rgba(212,163,115,0.3);
  backdrop-filter: var(--glass-blur);
  overflow: hidden;
}

h2, h3 {
  text-align: center;
  margin-bottom: 20px;
  color: var(--brand-accent);
  text-shadow: 0 0 8px var(--brand-accent);
}

/* ---------- INPUTS & BUTTONS ---------- */
input, select, button {
  width: 100%;
  padding: 12px;
  margin-top: 12px;
  border: none;
  border-radius: 10px;
  font-size: 16px;
  backdrop-filter: var(--glass-blur);
}

input {
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
}

button {
  background: var(--brand-accent);
  color: #fff;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s ease, transform 0.2s ease;
}

button:hover {
  background: var(--brand-hover);
  transform: scale(1.03);
}

/* ---------- MESSAGES ---------- */
.msg {
  margin-top: 10px;
  text-align: center;
  color: #00ff88;
}

.error {
  color: #ff6b6b;
}

/* ---------- TABLE ---------- */
table {
  width: 100%;
  margin-top: 30px;
  border-collapse: collapse;
  background: rgba(255, 255, 255, 0.04);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 0 20px rgba(212,163,115,0.2);
}

th, td {
  padding: 12px;
  text-align: center;
  border-bottom: 1px solid var(--border-color);
  color: #eee;
}

th {
  color: var(--brand-accent);
  font-weight: 600;
}

tr:hover {
  background-color: rgba(212,163,115,0.08);
}

/* ---------- TABLE ACTION LINKS ---------- */
.edit {
  color: #00eaff;
  text-decoration: none;
}

.delete {
  color: #ff6b6b;
  text-decoration: none;
}

/* ---------- SEARCH & SORT ---------- */
.search-sort {
  margin-top: 25px;
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

/* ---------- SIDEBAR ---------- */
.sidebar {
  width: var(--sidebar-width);
  background: linear-gradient(180deg, rgba(212,163,115,0.1), rgba(0,0,0,0.7)), url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  padding: 22px;
  position: fixed;
  height: 100vh;
  overflow-y: auto;
  border-right: 1px solid var(--border-color);
  backdrop-filter: var(--glass-blur);
  z-index: 999;
  top: 0;
  left: 0;
  transform: translateX(0);
  transition: transform 0.3s ease-in-out;
  border-radius: 0 20px 20px 0;
}

.sidebar .logo {
  text-align: center;
  margin-bottom: 35px;
}

.sidebar .logo img {
  width: 130px;
  border-radius: 14px;
  border: 2px solid var(--brand-accent);
  box-shadow: 0 0 18px var(--brand-accent);
}

.sidebar nav a {
  display: flex;
  align-items: center;
  color: #fff;
  text-decoration: none;
  padding: 14px 16px;
  margin: 10px 0;
  border-radius: 14px;
  font-weight: 500;
  font-size: 16px;
  gap: 12px;
  transition: 0.35s ease;
}

.sidebar nav a:hover {
  color: var(--brand-accent);
  background: rgba(0,0,0,0.45);
  box-shadow: 0 0 18px var(--brand-accent);
  transform: translateX(8px);
}

/* ---------- SIDEBAR TOGGLE BUTTON ---------- */
.sidebar-toggle {
  display: none;
  position: fixed;
  top: 10px;
  left: 10px;
  background: var(--brand-accent);
  color: #fff;
  border: none;
  padding: 10px;
  width: 44px;
  height: 44px;
  border-radius: 10px;
  font-size: 20px;
  cursor: pointer;
  z-index: 1100;
}

/* ---------- RESPONSIVE ---------- */
@media (max-width: 1024px) {
  body {
    padding: 60px 20px;
  }
  .sidebar {
    transform: translateX(-100%);
  }
  .sidebar.open {
    transform: translateX(0);
    overflow: hidden;
  }
  .sidebar-toggle {
    display: block;
  }
}

  </style>
</head>
<body>

<button class="sidebar-toggle" onclick="toggleSidebar()">
  <i class="fa fa-bars"></i>
</button>

<div class="sidebar" id="sidebar">
  <div class="logo">
    <img src="decorevistaimages/logo.png" alt="Logo" />
  </div>
  <nav>
    <a href="admin.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="Signup_view.php"><i class="fa-solid fa-user-check"></i> View Users</a>
    <a href="contect_view.php"><i class="fa-solid fa-envelope-circle-check"></i> Contacts</a>
    <a href="admin_add_product3.php"><i class="fa-solid fa-cart-shopping"></i> Add Products</a>
    <a href="view_products_cards.php"><i class="fa-solid fa-box-open"></i> Product Cards</a>
    <a href="admin_order.php"><i class="fa-solid fa-file-invoice-dollar"></i> Orders</a>
    <a href="admin_add_employee.php"><i class="fa-solid fa-user-plus"></i> Add Employee</a>
    <a href="admin_view_employee.php"><i class="fa-solid fa-users-viewfinder"></i> View Employees</a>
    <a href="appointment_service_add.php"><i class="fa-solid fa-handshake-angle"></i> Add Service</a>
    <a href="appointments_view.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
    <a href="admin_add_pakages.php"><i class="fa-solid fa-box-open"></i> Add Packages</a>
    <a href="pakages_view.php"><i class="fa-solid fa-boxes-stacked"></i> View Packages</a>
    <a href="pakages_order_view.php"><i class="fa-solid fa-truck-arrow-right"></i> Packages Order</a>
    <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">
      <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
    </a>
  </nav>
</div>

<div class="main-content">
  <h2>All Appointments</h2>
  <div class="container">
    <table>
      <thead>
        <tr>
          <th>Employee</th>
          <th>Client</th>
          <th>Phone</th>
          <th>Service</th>
          <th>Amount</th>
          <th>Date</th>
          <th>Time</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['employee_name']) ?></td>
            <td><?= htmlspecialchars($row['client_name']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['service']) ?></td>
            <td>$ <?= number_format($row['amount']) ?></td>
            <td><?= date('d M Y', strtotime($row['date'])) ?></td>
            <td><?= date('g:i A', strtotime($row['time'])) ?></td>
            <td class="status <?= $row['status'] ?>"><?= $row['status'] ?></td>
            <td>
              <?php if ($row['status'] == 'Pending'): ?>
                <form method="post" style="display:inline;">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <button type="submit" name="action" value="Approved" class="btn approve">Approve</button>
                </form>
                <form method="post" style="display:inline;">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <button type="submit" name="action" value="Rejected" class="btn reject">Reject</button>
                </form>
              <?php else: ?>
                <button class="btn" disabled><?= $row['status'] ?></button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("open");
  }
</script>

</body>
</html>
