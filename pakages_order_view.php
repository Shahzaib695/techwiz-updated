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
        $stmt = $conn->prepare("UPDATE package_orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
    }
}

$sql = "SELECT * FROM package_orders ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Package Orders - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap">
<style>
:root {
  --brand-accent: #d4a373; /* DecorVista Gold */
  --brand-hover: #b89560;
  --bg-dark: #0f0f0f;
  --card-bg: rgba(255, 255, 255, 0.06);
  --text-light: #f1f1f1;
  --sidebar-width: 260px;
  --shadow-glow: 0 0 18px var(--brand-accent);
  --glass-blur: blur(16px);
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'Poppins', sans-serif;
  background: var(--bg-dark);
  color: var(--text-light);
  display: flex;
  min-height: 100vh;
  overflow-x: hidden;
}

/* ---------- SIDEBAR ---------- */
.sidebar {
  width: var(--sidebar-width);
  background: linear-gradient(180deg, rgba(212,163,115,0.1), rgba(0,0,0,0.7)), 
              url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  padding: 22px;
  position: fixed;
  height: 100vh;
  overflow-y: auto;
  border-right: 1px solid rgba(255, 255, 255, 0.08);
  backdrop-filter: var(--glass-blur);
  z-index: 1001;
  transition: transform 0.3s ease-in-out;
  border-radius: 0 20px 20px 0;
}

.sidebar .logo { text-align: center; margin-bottom: 25px; }

.sidebar .logo img {
  width: 130px;
  border-radius: 14px;
  border: 2px solid var(--brand-accent);
  box-shadow: var(--shadow-glow);
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
  transition: 0.35s ease, transform 0.2s ease;
}

.sidebar nav a:hover {
  background: rgba(0,0,0,0.5);
  color: var(--brand-accent);
  box-shadow: var(--shadow-glow);
  transform: translateX(8px);
}

/* ---------- TOGGLE BUTTON ---------- */
.sidebar-toggle {
  display: none;
  position: fixed;
  top: 18px;
  left: 18px;
  background: var(--brand-accent);
  color: #fff;
  border: none;
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 20px;
  cursor: pointer;
  z-index: 1100;
}

/* ---------- MAIN CONTENT ---------- */
.main-content {
  margin-left: var(--sidebar-width);
  padding: 40px;
  flex: 1;
  width: calc(100% - var(--sidebar-width));
  background: url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  position: relative;
  z-index: 1;
  overflow-x: hidden;
  min-height: 100vh;
}

.main-content::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.55);
  z-index: 0;
}

.main-content > * { position: relative; z-index: 1; }

/* ---------- TABLES ---------- */
h2 {
  font-size: 32px;
  color: var(--brand-accent);
  margin-bottom: 30px;
  text-align: center;
  text-shadow: 0 0 12px var(--brand-accent);
}

.container {
  background: var(--card-bg);
  padding: 30px;
  border-radius: 20px;
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  box-shadow: 0 0 28px rgba(212,163,115,0.2);
  border: 1px solid rgba(255,255,255,0.08);
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  color: var(--text-light);
  min-width: 600px;
}

th, td {
  padding: 14px 18px;
  text-align: center;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}

th {
  color: var(--brand-accent);
  font-weight: 600;
  text-shadow: 0 0 6px var(--brand-accent);
}

tr:hover {
  background-color: rgba(212,163,115,0.05);
  cursor: pointer;
  transition: 0.3s;
}

/* ---------- FOOTER ---------- */
footer {
  text-align: center;
  padding: 30px 10px;
  font-size: 14px;
  margin-top: 40px;
  color: #aaa;
}

footer span { color: var(--brand-accent); font-weight: 500; }

/* ---------- RESPONSIVE ---------- */
@media (max-width: 768px) {
  .main-content { margin-left: 0; width: 100%; padding: 20px; }
  .sidebar { transform: translateX(-100%); position: fixed; top: 0; left: 0; height: 100%; z-index: 1001; }
  .sidebar.active { transform: translateX(0); }
  .sidebar-toggle { display: block; }
  .container { padding: 20px; max-width: 100%; }
  table { font-size: 14px; display: block; overflow-x: auto; white-space: nowrap; }
  th, td { padding: 10px 12px; }
}

@media (min-width: 769px) and (max-width: 1024px) {
  .main-content { padding: 30px; }
  .container { padding: 24px; max-width: 90%; }
  h2 { font-size: 28px; }
  th, td { padding: 12px; font-size: 15px; }
}

@media (min-width: 1600px) {
  .container { max-width: 1400px; }
  h2 { font-size: 36px; }
  th, td { font-size: 17px; padding: 18px 20px; }
}

/* Approve Button */
.btn.approve {
  background: rgba(40, 167, 69, 0.15); /* Transparent green */
  border: 1px solid rgba(40, 167, 69, 0.4);
  color: #28a745;
  font-weight: 600;
  padding: 8px 18px;
  border-radius: 12px;
  backdrop-filter: blur(8px);
  cursor: pointer;
  transition: all 0.3s ease-in-out;
}
.btn.approve:hover {
  background: rgba(40, 167, 69, 0.25);
  box-shadow: 0 0 12px rgba(40, 167, 69, 0.6);
  transform: translateY(-2px);
}

/* Reject Button */
.btn.reject {
  background: rgba(220, 53, 69, 0.15); /* Transparent red */
  border: 1px solid rgba(220, 53, 69, 0.4);
  color: #dc3545;
  font-weight: 600;
  padding: 8px 18px;
  border-radius: 12px;
  backdrop-filter: blur(8px);
  cursor: pointer;
  transition: all 0.3s ease-in-out;
}
.btn.reject:hover {
  background: rgba(220, 53, 69, 0.25);
  box-shadow: 0 0 12px rgba(220, 53, 69, 0.6);
  transform: translateY(-2px);
}







</style>

</head>
<body>
 <button class="sidebar-toggle" onclick="toggleSidebar()">
    <i class="fa fa-bars"></i>
  </button>

  <div class="sidebar">
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
<script>
  const toggleBtn = document.querySelector('.sidebar-toggle');
  const sidebar = document.querySelector('.sidebar');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('active');
  });
</script>
<div class="main-content">
  <h2><i class="fas fa-box-open"></i> Package Orders</h2>
  <div class="container">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Client</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Address</th>
          <th>Package</th>
          <th>Staff</th>
          <th>Price</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $count = 0; while($row = $result->fetch_assoc()): $count++; ?>
          <tr>
            <td data-label="#"><?= $count ?></td>
            <td data-label="Client"><?= htmlspecialchars($row['client_name']) ?></td>
            <td data-label="Phone"><?= htmlspecialchars($row['phone']) ?></td>
            <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
            <td data-label="Address"><?= htmlspecialchars($row['address']) ?></td>
            <td data-label="Package"><?= htmlspecialchars($row['package']) ?></td>
            <td data-label="Staff"><?= htmlspecialchars($row['staff']) ?></td>
            <td data-label="Price">$ <?= number_format($row['price']) ?></td>
            <td data-label="Status" class="status <?= $row['status'] ?>"><?= $row['status'] ?></td>
            <td data-label="Action">
              <?php if ($row['status'] === 'Pending'): ?>
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
</body>
</html>
