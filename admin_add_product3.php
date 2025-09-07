<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.com') {
    header("Location: login.php");
    exit;
}

// Handle form actions
$success = '';
$error = '';
$editing = false;
$edit_product = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['product_description']);
    $price = floatval($_POST['product_price']);
    $qty = intval($_POST['product_qty']);
    $image_path = '';

    if (!empty($_FILES['product_image']['name'])) {
        $image_name = basename($_FILES['product_image']['name']);
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir);
        $image_path = $target_dir . time() . '_' . $image_name;
        move_uploaded_file($_FILES['product_image']['tmp_name'], $image_path);
    }

    if ($id > 0) {
        $update_query = $image_path
            ? "UPDATE products SET name='$name', description='$description', price=$price, qty=$qty, image='$image_path' WHERE id=$id"
            : "UPDATE products SET name='$name', description='$description', price=$price, qty=$qty WHERE id=$id";
        mysqli_query($conn, $update_query);
        $success = "Product updated successfully!";
    } else {
        if ($image_path) {
            $query = "INSERT INTO products (name, description, price, qty, image) VALUES ('$name', '$description', $price, $qty, '$image_path')";
            mysqli_query($conn, $query);
            $success = "Product added successfully!";
        } else {
            $error = "Please upload a product image.";
        }
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    $success = "Product deleted!";
}

if (isset($_GET['edit'])) {
    $editing = true;
    $edit_id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id=$edit_id");
    if (mysqli_num_rows($result) > 0) {
        $edit_product = mysqli_fetch_assoc($result);
    }
}

// Pagination + Search
$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where = $search ? "WHERE name LIKE '%$search%'" : '';

$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products $where");
$total = mysqli_fetch_assoc($total_query)['total'];
$pages = ceil($total / $limit);
$result = mysqli_query($conn, "SELECT * FROM products $where ORDER BY id DESC LIMIT $start, $limit");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin | Add Product</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
:root {
  --brand-accent: #d4a373; /* DecorVista Gold */
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
  background: linear-gradient(180deg, rgba(212,163,115,0.1), rgba(0,0,0,0.7)), 
              url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  padding: 22px;
  position: fixed;
  height: 100vh;
  overflow-y: auto;
  border-right: 1px solid rgba(255, 255, 255, 0.08);
  backdrop-filter: var(--glass-blur);
  z-index: 999;
  border-radius: 0 20px 20px 0;
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
  background: rgba(0, 0, 0, 0.52);
  color: var(--brand-accent);
  box-shadow: var(--shadow-glow);
  transform: translateX(8px);
}

/* ---------- MAIN CONTENT ---------- */
.main-content {
  margin-left: var(--sidebar-width);
  padding: 20px;
  flex: 1;
  background: url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  position: relative;
  z-index: 1;
  min-height: 100vh;
  backdrop-filter: var(--glass-blur);
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

/* ---------- HEADINGS ---------- */
h2 {
  font-size: 32px;
  color: var(--brand-accent);
  margin-bottom: 30px;
  text-align: center;
  text-shadow: 0 0 10px var(--brand-accent);
}

/* ---------- FORM STYLING ---------- */
form input[type="text"],
form input[type="number"],
form input[type="file"],
form textarea {
  width: 100%;
  margin-bottom: 18px;
  padding: 12px 16px;
  font-size: 16px;
  border-radius: 12px;
  border: 2px solid rgba(255,255,255,0.1);
  background: rgba(255,255,255,0.04);
  color: var(--text-light);
  backdrop-filter: blur(6px);
  transition: 0.3s;
}

form input:focus, form textarea:focus {
  border-color: var(--brand-accent);
  box-shadow: var(--shadow-glow);
  outline: none;
}

form textarea {
  resize: vertical;
  min-height: 100px;
}

form button {
  background-color: var(--brand-accent);
  color: var(--accent-light);
  padding: 12px 20px;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  font-size: 16px;
  cursor: pointer;
  transition: 0.3s ease;
}

form button:hover {
  background-color: var(--brand-hover);
  box-shadow: var(--shadow-glow);
}

/* ---------- ALERTS ---------- */
.success, .error {
  text-align: center;
  font-weight: bold;
  margin-bottom: 20px;
  padding: 10px;
  border-radius: 8px;
}

.success {
  background-color: rgba(0, 200, 83, 0.2);
  color: #00e676;
}

.error {
  background-color: rgba(255, 82, 82, 0.2);
  color: #ff5252;
}

/* ---------- SEARCH BOX ---------- */
.search-box {
  margin: 30px 0;
  text-align: center;
}

.search-box input[type="text"] {
  padding: 10px 14px;
  border-radius: 8px;
  border: 2px solid rgba(255,255,255,0.1);
  background: rgba(255,255,255,0.04);
  color: var(--text-light);
  margin-right: 8px;
}

.search-box button {
  padding: 10px 14px;
  background-color: var(--brand-accent);
  color: #fff;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: 0.3s;
}

.search-box button:hover {
  background-color: var(--brand-hover);
  box-shadow: var(--shadow-glow);
}

/* ---------- TABLE ---------- */
table {
  width: 100%;
  border-collapse: collapse;
  color: var(--text-light);
  margin-top: 30px;
}

th, td {
  padding: 14px 18px;
  text-align: center;
  border-bottom: 1px solid rgba(255,255,255,0.08);
}

th {
  color: var(--brand-accent);
  font-weight: 600;
  text-shadow: 0 0 6px var(--brand-accent);
}

tr:hover {
  background-color: rgba(212,163,115,0.05);
  transition: 0.3s;
}

img {
  width: 60px;
  border-radius: 6px;
}

.actions a {
  margin: 0 6px;
  color: var(--brand-accent);
  text-decoration: none;
  font-weight: bold;
  transition: 0.2s;
}

.actions a:hover {
  text-decoration: underline;
  color: var(--brand-hover);
}

/* ---------- PAGINATION ---------- */
.pagination {
  margin-top: 30px;
  text-align: center;
}

.pagination a {
  display: inline-block;
  margin: 0 5px;
  padding: 8px 14px;
  background: rgba(0,0,0,0.8);
  color: var(--text-light);
  text-decoration: none;
  border-radius: 8px;
  font-weight: 500;
  transition: 0.2s ease;
}

.pagination a:hover {
  background: var(--brand-accent);
  color: #fff;
  box-shadow: var(--shadow-glow);
}

/* ---------- SIDEBAR TOGGLE ---------- */
.sidebar-toggle {
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

/* ---------- RESPONSIVE ---------- */
@media (max-width: 768px) {
  .main-content {
    margin-left: 0;
    border-radius: 0;
  }

  .sidebar {
    transform: translateX(-100%);
  }

  .sidebar.active {
    transform: translateX(0);
  }

  .sidebar-toggle {
    display: block;
  }

  table, thead, tbody, th, td, tr {
    display: block;
  }

  thead { display: none; }

  tr {
    margin-bottom: 20px;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    background: rgba(0,0,0,0.9);
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
  const toggleBtn = document.querySelector('.sidebar-toggle');
  const sidebar = document.querySelector('.sidebar');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('active');
  });
</script>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container">
      <h2><?= $editing ? 'Update Product' : 'Add New Product' ?></h2>

      <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>
      <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

      <form method="POST" enctype="multipart/form-data">
        <?php if ($editing): ?>
          <input type="hidden" name="product_id" value="<?= $edit_product['id'] ?>">
        <?php endif; ?>

        <input type="text" name="product_name" placeholder="Product Name" value="<?= $editing ? $edit_product['name'] : '' ?>" required>
        <textarea name="product_description" placeholder="Product Description" required><?= $editing ? $edit_product['description'] : '' ?></textarea>
        <input type="number" step="0.01" name="product_price" placeholder="Price" value="<?= $editing ? $edit_product['price'] : '' ?>" required>
        <input type="number" name="product_qty" placeholder="Quantity" value="<?= $editing ? $edit_product['qty'] : '' ?>" required>
        <input type="file" name="product_image" <?= $editing ? '' : 'required' ?>>
        <button type="submit"><?= $editing ? 'Update Product' : 'Add Product' ?></button>
      </form>

      <div class="search-box">
        <form method="GET">
          <input type="text" name="search" placeholder="Search by name" value="<?= $search ?>">
          <button type="submit">Search</button>
        </form>
      </div>

      <table>
        <thead>
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><img src="<?= $row['image'] ?>" alt=""></td>
              <td><?= $row['name'] ?></td>
              <td><?= $row['description'] ?></td>
              <td>$<?= number_format($row['price'], 2) ?></td>
              <td><?= $row['qty'] ?></td>
              <td class="actions">
                <a href="?edit=<?= $row['id'] ?>">Edit</a>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
          <a href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    </div>
  </div>

</body>
</html>
