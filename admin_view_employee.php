<?php
session_start();
include 'db.php';

date_default_timezone_set('Asia/Karachi');

// Admin session check
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.com') {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM employees ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>All Employees | Fashion Vibes</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
:root {
  --brand-accent: #d4a373; /* DecorVista gold */
  --brand-hover: #b89560;
  --bg-dark: #121212;
  --card-bg: rgba(255, 255, 255, 0.08);
  --text-light: #f1f1f1;
  --sidebar-width: 260px;
  --glass-blur: blur(12px);
  --border-color: rgba(212,163,115,0.3);
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
  display: flex;
  min-height: 100vh;
  overflow-x: hidden;
}

/* ---------- SIDEBAR ---------- */
.sidebar {
  width: var(--sidebar-width);
  padding: 22px;
  position: fixed;
  height: 100vh;
  overflow-y: auto;
  border-right: 1px solid var(--border-color);
  backdrop-filter: var(--glass-blur);
  background: linear-gradient(180deg, rgba(212,163,115,0.1), rgba(0,0,0,0.7)), url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  border-radius: 0 20px 20px 0;
  z-index: 1001;
  transition: transform 0.3s ease-in-out;
}

.sidebar .logo {
  text-align: center;
  margin-bottom: 25px;
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
  color: var(--text-light);
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
  box-shadow: 0 0 18px var(--brand-accent);
  transform: translateX(8px);
}

/* ---------- MAIN CONTENT ---------- */
.main-content {
  margin-left: var(--sidebar-width);
  padding: 40px;
  flex: 1;
  width: calc(100% - var(--sidebar-width));
  position: relative;
  min-height: 100vh;
  background: linear-gradient(180deg, rgba(212,163,115,0.05), rgba(0,0,0,0.6)), url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  overflow-x: auto;
  border-radius: 20px;
}

.main-content::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: var(--glass-blur);
  z-index: 0;
}

.main-content > * {
  position: relative;
  z-index: 1;
}

/* ---------- HEADINGS ---------- */
h2 {
  font-size: 32px;
  color: var(--brand-accent);
  margin-bottom: 30px;
  text-align: center;
  text-shadow: 0 0 8px var(--brand-accent);
}

/* ---------- CONTAINER ---------- */
.container {
  background: var(--card-bg);
  padding: 30px;
  border-radius: 20px;
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  box-shadow: 0 0 30px rgba(0,0,0,0.25);
  border: 1px solid var(--border-color);
  overflow-x: auto;
}

/* ---------- TABLE ---------- */
table {
  width: 100%;
  border-collapse: collapse;
  color: var(--text-light);
  min-width: 800px;
}

th, td {
  padding: 14px 18px;
  text-align: center;
  border-bottom: 1px solid var(--border-color);
}

th {
  color: var(--brand-accent);
  font-weight: 600;
}

tr:hover {
  background-color: rgba(212,163,115,0.07);
}

img.employee-img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid var(--brand-accent);
}

/* ---------- FOOTER ---------- */
footer {
  text-align: center;
  padding: 30px 10px;
  font-size: 14px;
  margin-top: 40px;
  color: #aaa;
}

footer span {
  color: var(--brand-accent);
  font-weight: 500;
}

/* ---------- TOGGLE BUTTON ---------- */
.toggle-btn {
  display: none;
  position: fixed;
  top: 20px;
  left: 20px;
  background: var(--brand-accent);
  color: #fff;
  padding: 10px 14px;
  font-size: 20px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  z-index: 1100;
}

/* ---------- RESPONSIVE ---------- */
@media (max-width: 768px) {
  .main-content {
    margin-left: 0;
    width: 100%;
    padding: 20px;
  }

  .sidebar {
    transform: translateX(-100%);
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    z-index: 1001;
    background: rgba(18,18,18,0.95);
  }

  .sidebar.active {
    transform: translateX(0);
  }

  .toggle-btn {
    display: block;
  }

  .container {
    padding: 20px;
    width: 100%;
    max-width: 100%;
  }

  table {
    font-size: 14px;
    display: block;
    overflow-x: auto;
    white-space: nowrap;
  }

  th, td {
    padding: 10px 12px;
  }
}

  </style>
</head>
<body>

<button class="toggle-btn" onclick="toggleSidebar()">
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
<div class="main-content">
  <div class="container">
    <h2>Our Grooming Experts</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Image</th>
          <th>Name</th>
          <th>Designation</th>
          <th>Experience</th>
          <th>Available Days</th>
          <th>Available Time</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><img class="employee-img" src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>"></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['designation']) ?></td>
            <td><?= htmlspecialchars($row['experience']) ?></td>
            <td><?= htmlspecialchars($row['available_days']) ?></td>
            <td><?= date("g:i A", strtotime($row['available_time_from'])) . " - " . date("g:i A", strtotime($row['available_time_to'])) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <footer>
    &copy; <?= date("Y") ?> <span>ELEGANCE SALONE</span> | Admin Panel<br><br>
    <span>CREATED BY MOIN AKHTER & FARRUKH</span>
  </footer>
</div>

<script>
  function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
  }
</script>

</body>
</html>
