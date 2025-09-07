<?php
session_start();
include 'db.php';

// ✅ Admin session check
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.com') {
    header("Location: login.php");
    exit;
}

// ✅ Session timeout (10 min)
if (isset($_SESSION['time']) && time() - $_SESSION['time'] > 600) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
} else {
    $_SESSION['time'] = time();
}

// ✅ Approve order
if (isset($_GET['approve']) && is_numeric($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $update = "UPDATE orders SET status='Approved' WHERE id = $id AND status = 'Pending'";
    mysqli_query($conn, $update);
    header("Location: admin_order.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM orders ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Orders | Fashion Vibes</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
/* ---------- DecorVista Admin Dashboard Theme ---------- */
:root {
  --brand-accent: #d4a373; /* Gold Accent */
  --brand-hover: #b89560;
  --accent-light: #ffffff;
  --bg-dark: #0f0f0f;
  --card-bg: rgba(255, 255, 255, 0.06);
  --text-light: #f1f1f1;
  --sidebar-width: 260px;
  --shadow-glow: 0 0 18px var(--brand-accent);
  --glass-blur: blur(16px);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

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
  background: linear-gradient(180deg, rgba(212,163,115,0.1), rgba(0,0,0,0.7)), url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  padding: 22px;
  position: fixed;
  height: 100vh;
  overflow-y: auto;
  border-right: 1px solid rgba(255,255,255,0.08);
  backdrop-filter: var(--glass-blur);
  z-index: 999;
  border-radius: 0 20px 20px 0;
}

.sidebar .logo {
  text-align: center;
  margin-bottom: 25px;
}

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
  transition: 0.35s ease;
}

.sidebar nav a:hover {
  background: rgba(0,0,0,0.45);
  color: var(--brand-accent);
  box-shadow: var(--shadow-glow);
  transform: translateX(8px);
}

/* ---------- MAIN CONTENT ---------- */
.main-content {
  margin-left: var(--sidebar-width);
  padding: 20px;
  flex: 1;
  background: linear-gradient(180deg, rgba(212,163,115,0.08), rgba(0,0,0,0.55)), url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  position: relative;
  z-index: 1;
  min-height: 100vh;
  border-radius: 20px;
}

.main-content::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.55);
  z-index: 0;
}

.main-content > * {
  position: relative;
  z-index: 1;
}

.toggle-btn {
  display: none;
  position: fixed;
  top: 20px;
  left: 20px;
  background: var(--brand-accent);
  color: #fff;
  border: none;
  padding: 10px 14px;
  font-size: 20px;
  border-radius: 6px;
  cursor: pointer;
  z-index: 1100;
}

/* ---------- HEADINGS ---------- */
h2 {
  font-size: 32px;
  color: var(--brand-accent);
  margin-bottom: 30px;
  text-align: center;
  text-shadow: 0 0 10px var(--brand-accent);
}

/* ---------- TABLE ---------- */
.container {
  background: var(--card-bg);
  padding: 20px;
  border-radius: 20px;
  max-width: 1200px;
  margin: 0 auto;
  box-shadow: 0 8px 22px rgba(0,0,0,0.3);
  border: 1px solid rgba(212,163,115,0.2);
}

table {
  width: 100%;
  border-collapse: collapse;
  color: var(--text-light);
}

th, td {
  padding: 14px 18px;
  text-align: center;
  border-bottom: 1px solid rgba(212,163,115,0.2);
}

th {
  background: rgba(0,0,0,0.3);
  color: var(--brand-accent);
  font-weight: 600;
}

tr:hover {
  background-color: rgba(212,163,115,0.08);
}

img {
  width: 60px;
  border-radius: 6px;
}

/* ---------- BUTTONS ---------- */
.btn-approve {
  padding: 6px 12px;
  background-color: #00c853;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.3s;
}

.btn-approve:hover {
  background-color: #00b347;
}

/* ---------- STATUS ---------- */
.status.pending {
  color: #fdd835;
  font-weight: bold;
}

.status.approved {
  color: #00e676;
  font-weight: bold;
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

/* ---------- RESPONSIVE ---------- */
@media (max-width: 768px) {
  .main-content {
    margin-left: 0;
    padding: 20px;
    border-radius: 0;
  }

  .sidebar {
    transform: translateX(-100%);
  }

  .sidebar.active {
    transform: translateX(0);
  }

  .toggle-btn {
    display: block;
  }

  table, thead, tbody, th, td, tr {
    display: block;
  }

  thead {
    display: none;
  }

  tr {
    margin-bottom: 20px;
    border: 1px solid rgba(212,163,115,0.2);
    border-radius: 12px;
    background: rgba(0,0,0,0.2);
    padding: 14px;
  }

  td {
    text-align: left;
    padding: 10px 12px;
    position: relative;
    font-size: 15px;
  }

  td::before {
    content: attr(data-label);
    font-weight: 600;
    color: var(--brand-accent);
    display: block;
    margin-bottom: 6px;
  }

  img {
    max-width: 100px;
    width: 100%;
  }

  h2 {
    font-size: 22px;
  }

  .container {
    padding: 20px;
  }
}
  </style>
</head>
<body>

 <!-- <button class="sidebar-toggle" onclick="toggleSidebar()"> -->
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
            <a href="designer-approval.php" style="background:rgba(255,255,255,0.15);"><i
                    class="fa-solid fa-users-viewfinder"></i> Approve New Designer</a>
            <a href="designer-approval.php" style="background:rgba(255,255,255,0.15);"><i
                    class="fa-solid fa-users-viewfinder"></i> Approve Designer Visibility</a>
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
    <h2>All Orders</h2>
    <table>
      <thead>
        <tr>
          <th>Image</th>
          <th>Product</th>
          <th>Qty</th>
          <th>User</th>
          <th>Email</th>
          <th>Payment</th>
          <th>Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td data-label="Image"><img src="<?= $row['product_image'] ?>" alt=""></td>
          <td data-label="Product"><?= $row['product_name'] ?></td>
          <td data-label="Qty"><?= $row['quantity'] ?></td>
          <td data-label="User"><?= $row['username'] ?></td>
          <td data-label="Email"><?= $row['email'] ?></td>
          <td data-label="Payment"><?= $row['payment_method'] ?></td>
          <td data-label="Date"><?= $row['order_date'] ?></td>
          <td data-label="Status" class="status <?= strtolower($row['status']) ?>"><?= $row['status'] ?></td>
          <td data-label="Action">
            <?php if ($row['status'] == 'Pending'): ?>
              <a href="?approve=<?= $row['id'] ?>" class="btn-approve">Approve</a>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
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
