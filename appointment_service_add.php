<?php
include 'db.php';

$success = '';
$error = '';
$editing = false;
$edit_id = 0;
$edit_name = '';
$edit_amount = '';

// Add or update service
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['service_name']);
    $amount = (int)$_POST['amount'];

    if ($name != '' && $amount > 0) {
        if (isset($_POST['update_id'])) {
            $update_id = (int)$_POST['update_id'];
            $stmt = $conn->prepare("UPDATE services SET service_name=?, amount=? WHERE id=?");
            $stmt->bind_param("sii", $name, $amount, $update_id);
            $stmt->execute();
            $success = "✅ Service updated successfully!";
        } else {
            $stmt = $conn->prepare("INSERT INTO services (service_name, amount) VALUES (?, ?)");
            $stmt->bind_param("si", $name, $amount);
            $stmt->execute();
            $success = "✅ Service added successfully!";
        }
    } else {
        $error = "❌ Please enter valid data.";
    }
}

// Delete service
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM services WHERE id = $id");
    header("Location: appointment_service_add.php");
    exit;
}

// Prefill edit
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM services WHERE id = $edit_id");
    if ($result->num_rows > 0) {
        $editing = true;
        $row = $result->fetch_assoc();
        $edit_name = $row['service_name'];
        $edit_amount = $row['amount'];
    }
}

// Search & Sort
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$valid_sorts = ['id', 'service_name', 'amount'];
$sort = in_array($sort, $valid_sorts) ? $sort : 'id';

$sql = "SELECT * FROM services";
if ($search != '') {
    $sql .= " WHERE service_name LIKE '%$search%'";
}
$sql .= " ORDER BY $sort DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Services | Fashion Vibes</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
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

<div class="box">
  <h2><?= $editing ? '✏️ Update Service' : '➕ Add New Service' ?></h2>

  <form method="POST">
    <input type="text" name="service_name" placeholder="Service Name" value="<?= htmlspecialchars($edit_name) ?>" required>
    <input type="number" name="amount" placeholder="Service Amount" value="<?= htmlspecialchars($edit_amount) ?>" required>
    <?php if ($editing): ?>
      <input type="hidden" name="update_id" value="<?= $edit_id ?>">
    <?php endif; ?>
    <button type="submit"><?= $editing ? 'Update Service' : 'Add Service' ?></button>
    <?php if ($success): ?><div class="msg"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="msg error"><?= $error ?></div><?php endif; ?>
  </form>

  <form method="GET" class="search-sort">
    <input type="text" name="search" placeholder="🔍 Search Service..." value="<?= htmlspecialchars($search) ?>">
    <select name="sort">
      <option value="id" <?= $sort == 'id' ? 'selected' : '' ?>>Sort: Default</option>
      <option value="service_name" <?= $sort == 'service_name' ? 'selected' : '' ?>>Sort by Name</option>
      <option value="amount" <?= $sort == 'amount' ? 'selected' : '' ?>>Sort by Amount</option>
    </select>
    <button type="submit">Apply</button>
  </form>

  <h3>📋 All Services</h3>
  <table>
    <tr>
      <th>#</th>
      <th>Service</th>
      <th>Amount</th>
      <th>Action</th>
    </tr>
    <?php $i = 1; while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars($row['service_name']) ?></td>
      <td>$<?= $row['amount'] ?></td>
      <td>
        <a class="edit" href="?edit=<?= $row['id'] ?>">Edit</a> |
        <a class="delete" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this service?')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>

<script>
  function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("open");
  }
</script>

</body>
</html>
