<?php
session_start();

// ✅ Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.com') {
    header("Location: login.php");
    exit;
}

// ✅ Check for session timeout
if (isset($_SESSION['time']) && time() - $_SESSION['time'] > 600) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
} else {
    $_SESSION['time'] = time();
}

include 'db.php';

// ✅ Handle Employee Add
if (isset($_POST['add_employee'])) {
    $name = $_POST['emp_name'];
    $role = $_POST['emp_role'];
    mysqli_query($conn, "INSERT INTO employees (name, role) VALUES ('$name', '$role')");
}

// ✅ Handle Product Add
if (isset($_POST['add_product'])) {
    $name = $_POST['product_name'];
    $image = $_POST['product_image'];
    $qty = $_POST['product_qty'];
    mysqli_query($conn, "INSERT INTO products (name, image, quantity) VALUES ('$name', '$image', '$qty')");
}

// ✅ Fetch all products
$products = mysqli_query($conn, "SELECT * FROM products");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Data | Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
      color: #f1f1f1;
      padding-bottom: 100px;
    }
    nav {
      background: rgba(255,255,255,0.05);
      padding: 20px 30px;
      display: flex;
      justify-content: space-between;
      position: sticky;
      top: 0;
    }
    .logo {
      color: #6c5ce7;
      font-weight: bold;
      font-size: 22px;
    }
    .nav-links a {
      color: #f1f1f1;
      margin-left: 20px;
      text-decoration: none;
    }
    h2 {
      text-align: center;
      margin-top: 40px;
      color: #a29bfe;
    }
    form {
      width: 90%;
      max-width: 500px;
      margin: 20px auto;
      background: rgba(255, 255, 255, 0.05);
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(255,255,255,0.05);
    }
    input {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      margin-bottom: 20px;
      border: none;
      border-radius: 8px;
    }
    button {
      padding: 10px 20px;
      background: #6c5ce7;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    table {
      width: 90%;
      margin: 40px auto;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.03);
    }
    table th, table td {
      padding: 12px;
      border: 1px solid rgba(255,255,255,0.1);
      text-align: center;
    }
    footer {
      background: rgba(255,255,255,0.05);
      padding: 20px;
      text-align: center;
      position: fixed;
      bottom: 0;
      width: 100%;
      color: #bbb;
    }
  </style>
</head>
<body>

<nav>
  <div class="logo">Fashion Vibes Admin</div>
  <div class="nav-links">
    <a href="admin_panel.php">Dashboard</a>
    <a href="manage_data.php">Manage Data</a>
    <a href="logout.php">Logout</a>
  </div>
</nav>

<h2>Add Employee</h2>
<form method="post">
  <input type="text" name="emp_name" placeholder="Employee Name" required>
  <input type="text" name="emp_role" placeholder="Role (e.g. Hair Stylist)" required>
  <button type="submit" name="add_employee">Add Employee</button>
</form>

<h2>Add Product</h2>
<form method="post">
  <input type="text" name="product_name" placeholder="Product Name" required>
  <input type="text" name="product_image" placeholder="Image URL" required>
  <input type="number" name="product_qty" placeholder="Quantity in Stock" required>
  <button type="submit" name="add_product">Add Product</button>
</form>

<h2>Current Product Stock</h2>
<table>
  <tr>
    <th>#</th>
    <th>Product</th>
    <th>Image</th>
    <th>Quantity</th>
  </tr>
  <?php $i=1; while($row = mysqli_fetch_assoc($products)) { ?>
  <tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><img src="<?= htmlspecialchars($row['image']) ?>" height="40"></td>
    <td><?= $row['quantity'] ?></td>
  </tr>
  <?php } ?>
</table>

<footer>
  &copy; <?= date("Y") ?> Fashion Vibes | Manage Panel <br>
  <span>Created by MOIN AKHTER & Farrukh</span>
</footer>

</body>
</html>
