<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.com') {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';
$editing = false;
$edit_package = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST['package_id']) ? intval($_POST['package_id']) : 0;
    $name = mysqli_real_escape_string($conn, $_POST['package_name']);
    $description = mysqli_real_escape_string($conn, $_POST['package_description']);
    $price = floatval($_POST['package_price']);
    $image_path = '';

    if (!empty($_FILES['package_image']['name'])) {
        $image_name = basename($_FILES['package_image']['name']);
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir);
        $image_path = $target_dir . time() . '_' . $image_name;
        move_uploaded_file($_FILES['package_image']['tmp_name'], $image_path);
    }

    if ($id > 0) {
        $update_query = $image_path
            ? "UPDATE packages SET name='$name', description='$description', price=$price, image='$image_path' WHERE id=$id"
            : "UPDATE packages SET name='$name', description='$description', price=$price WHERE id=$id";
        mysqli_query($conn, $update_query);
        $success = "Package updated successfully!";
    } else {
        if ($image_path) {
            $query = "INSERT INTO packages (name, description, price, image) VALUES ('$name', '$description', $price, '$image_path')";
            mysqli_query($conn, $query);
            $success = "Package added successfully!";
        } else {
            $error = "Please upload a package image.";
        }
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM packages WHERE id=$id");
    $success = "Package deleted!";
}

if (isset($_GET['edit'])) {
    $editing = true;
    $edit_id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM packages WHERE id=$edit_id");
    if (mysqli_num_rows($result) > 0) {
        $edit_package = mysqli_fetch_assoc($result);
    }
}

// Pagination + Search
$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where = $search ? "WHERE name LIKE '%$search%'" : '';

$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM packages $where");
$total = mysqli_fetch_assoc($total_query)['total'];
$pages = ceil($total / $limit);
$result = mysqli_query($conn, "SELECT * FROM packages $where ORDER BY id DESC LIMIT $start, $limit");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin | Add Package</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
  text-shadow: 0 0 12px var(--brand-accent);
}

/* ---------- CONTAINER ---------- */
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

/* ---------- FORMS ---------- */
input[type="text"],
input[type="number"],
textarea,
input[type="file"],
button {
  width: 100%;
  padding: 12px 16px;
  margin: 10px 0;
  border-radius: 10px;
  border: 1px solid rgba(255,255,255,0.1);
  background: rgba(255, 255, 255, 0.06);
  color: var(--text-light);
  font-size: 15px;
}

button {
  background-color: var(--brand-accent);
  border: none;
  cursor: pointer;
  color: white;
  font-weight: 600;
  transition: 0.3s ease;
}

button:hover {
  background-color: var(--brand-hover);
  box-shadow: var(--shadow-glow);
}

/* ---------- MESSAGES ---------- */
.success {
  background: rgba(0, 255, 0, 0.2);
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 12px;
  color: #00ff90;
  font-weight: bold;
}

.error {
  background: rgba(255, 0, 0, 0.2);
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 12px;
  color: #ff4c4c;
  font-weight: bold;
}

/* ---------- TABLES ---------- */
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

table img {
  width: 70px;
  height: 60px;
  object-fit: cover;
  border-radius: 10px;
}

/* ---------- ACTION LINKS ---------- */
.actions a {
  margin: 0 6px;
  color: var(--brand-accent);
  text-decoration: none;
  font-weight: bold;
}

.actions a:hover {
  text-decoration: underline;
  color: var(--brand-hover);
}

/* ---------- PAGINATION ---------- */
.pagination {
  text-align: center;
  margin-top: 25px;
}

.pagination a {
  display: inline-block;
  padding: 8px 14px;
  margin: 0 5px;
  border-radius: 8px;
  background: rgba(255,255,255,0.05);
  color: var(--text-light);
  text-decoration: none;
  transition: 0.3s ease;
}

.pagination a:hover {
  background: var(--brand-accent);
  color: #fff;
}

/* ---------- SEARCH ---------- */
.search-box {
  margin: 20px 0;
  text-align: center;
}

.search-box input[type="text"] {
  width: 70%;
  max-width: 400px;
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
  }

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


  </style>
</head>
<body>
 <button class="sidebar-toggle" onclick="toggleSidebar()">
    <i class="fa fa-bars"></i>
  </button>

  <div class="sidebar">
    <div class="logo">
      <img src="elegance salon images/logo.jpg" alt="Logo" />
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
  <script>
  function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
  }
</script>



  <div class="main-content">
    <div class="container">
      <h2><?= $editing ? 'Update Package' : 'Add New Package' ?></h2>

      <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>
      <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

      <form method="POST" enctype="multipart/form-data">
        <?php if ($editing): ?>
          <input type="hidden" name="package_id" value="<?= $edit_package['id'] ?>">
        <?php endif; ?>
        <input type="text" name="package_name" placeholder="Package Name" value="<?= $editing ? $edit_package['name'] : '' ?>" required>
        <textarea name="package_description" placeholder="Package Description" required><?= $editing ? $edit_package['description'] : '' ?></textarea>
        <input type="number" step="0.01" name="package_price" placeholder="Price" value="<?= $editing ? $edit_package['price'] : '' ?>" required>
        <input type="file" name="package_image" <?= $editing ? '' : 'required' ?>>
        <button type="submit"><?= $editing ? 'Update Package' : 'Add Package' ?></button>
      </form>

      <div class="search-box">
        <form method="GET">
          <input type="text" name="search" placeholder="Search by package name" value="<?= $search ?>">
          <button type="submit">Search</button>
        </form>
      </div>

      <table>
        <thead>
          <tr>
            <th>Image</th>
            <th>Package Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><img src="<?= $row['image'] ?>" alt=""></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td class="description"><?= nl2br(htmlspecialchars($row['description'])) ?></td>
              <td>Rs <?= number_format($row['price'], 2) ?></td>
              <td class="actions">
                <a href="?edit=<?= $row['id'] ?>">Edit</a>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this package?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
          <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    </div>
  </div>
</body>
</html>
