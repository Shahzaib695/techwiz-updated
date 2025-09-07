<?php
session_start();
include 'db.php';

date_default_timezone_set('Asia/Karachi');

// Admin session check
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.com') {
    header("Location: login.php");
    exit;
}

// Session timeout check
if (isset($_SESSION['time']) && time() - $_SESSION['time'] > 600) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
} else {
    $_SESSION['time'] = time();
}

// Handle Employee Delete
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $imgQ = $conn->prepare("SELECT image FROM employees WHERE id = ?");
    $imgQ->bind_param("i", $id);
    $imgQ->execute();
    $imgQ->bind_result($imageFile);
    $imgQ->fetch();
    $imgQ->close();

    if ($imageFile && file_exists("uploads/" . $imageFile)) {
        unlink("uploads/" . $imageFile);
    }

    $stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $empMsg = "Employee deleted successfully!";
}

if (isset($_POST['add_employee'])) {
    $name = $_POST['emp_name'];
    $designation = $_POST['emp_designation'];
    $experience = $_POST['emp_experience'];
    $available_days = $_POST['emp_available_days'];
    $available_from = $_POST['emp_available_time_from'];
    $available_to = $_POST['emp_available_time_to'];
    $created_at = date("Y-m-d H:i:s");

    $imageName = $_FILES['emp_image']['name'];
    $tmpName = $_FILES['emp_image']['tmp_name'];
    $targetDir = "uploads/";
    $uniqueName = "";

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (isset($_POST['emp_id']) && !empty($_POST['emp_id'])) {
        $id = $_POST['emp_id'];

        if (!empty($imageName)) {
            $uniqueName = uniqid() . "_" . basename($imageName);
            move_uploaded_file($tmpName, $targetDir . $uniqueName);

            $imgQ = $conn->prepare("SELECT image FROM employees WHERE id = ?");
            $imgQ->bind_param("i", $id);
            $imgQ->execute();
            $imgQ->bind_result($oldImg);
            $imgQ->fetch();
            $imgQ->close();

            if ($oldImg && file_exists("uploads/" . $oldImg)) {
                unlink("uploads/" . $oldImg);
            }

            $stmt = $conn->prepare("UPDATE employees SET name=?, designation=?, experience=?, available_days=?, available_time_from=?, available_time_to=?, image=? WHERE id=?");
            $stmt->bind_param("sssssssi", $name, $designation, $experience, $available_days, $available_from, $available_to, $uniqueName, $id);
        } else {
            $stmt = $conn->prepare("UPDATE employees SET name=?, designation=?, experience=?, available_days=?, available_time_from=?, available_time_to=? WHERE id=?");
            $stmt->bind_param("ssssssi", $name, $designation, $experience, $available_days, $available_from, $available_to, $id);
        }

        $stmt->execute();
        $empMsg = "Employee updated successfully!";
    } else {
        $uniqueName = uniqid() . "_" . basename($imageName);
        move_uploaded_file($tmpName, $targetDir . $uniqueName);

        $stmt = $conn->prepare("INSERT INTO employees (name, designation, experience, available_days, available_time_from, available_time_to, image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $name, $designation, $experience, $available_days, $available_from, $available_to, $uniqueName, $created_at);
        $stmt->execute();
        $empMsg = "Employee added successfully!";
    }
}

$editEmp = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $result = $conn->query("SELECT * FROM employees WHERE id = $edit_id");
    $editEmp = $result->fetch_assoc();
}

// Search & Sort
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'created_at';
$order = $_GET['order'] ?? 'DESC';

$allowedSorts = ['name', 'designation', 'created_at'];
$allowedOrders = ['ASC', 'DESC'];

if (!in_array($sort, $allowedSorts)) $sort = 'created_at';
if (!in_array($order, $allowedOrders)) $order = 'DESC';

$searchSql = $search ? "WHERE name LIKE '%$search%' OR designation LIKE '%$search%'" : '';
$sql = "SELECT * FROM employees $searchSql ORDER BY $sort $order";
$allEmployees = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add/View Employees</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
   :root {
  --brand-accent: #d4a373; /* Gold Accent */
  --brand-hover: #b89560;
  --sidebar-width: 250px;
  --background: #0f0f0f;
  --button-bg: #d4a373;
  --button-hover: #b89560;
  --border-color: rgba(212,163,115,0.3);
  --text-light: #f1f1f1;
  --glass-blur: blur(12px);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  margin: 0;
  display: flex;
  min-height: 100vh;
  background: linear-gradient(180deg, rgba(212,163,115,0.05), rgba(0,0,0,0.6)), url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  color: var(--text-light);
}

/* ---------- SIDEBAR ---------- */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: var(--sidebar-width);
  background: linear-gradient(180deg, rgba(212,163,115,0.1), rgba(0,0,0,0.7)), url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  padding: 22px;
  overflow-y: auto;
  border-right: 1px solid var(--border-color);
  backdrop-filter: var(--glass-blur);
  z-index: 1000;
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
  padding: 20px;
  flex: 1;
  overflow-y: auto;
  max-height: 100vh;
  position: relative;
  background: linear-gradient(180deg, rgba(212,163,115,0.05), rgba(0,0,0,0.6)), url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  border-radius: 20px;
}

.main-content::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  backdrop-filter: var(--glass-blur);
  z-index: 0;
}

.main-content > * {
  position: relative;
  z-index: 1;
}

/* ---------- HEADINGS ---------- */
h2 {
  color: var(--brand-accent);
  text-align: center;
  margin: 20px 0;
  font-size: 26px;
  font-weight: 600;
  text-shadow: 0 0 8px var(--brand-accent);
}

/* ---------- FORMS ---------- */
form, .search-sort, table {
  border-radius: 12px;
  backdrop-filter: var(--glass-blur);
  padding: 20px;
  margin-bottom: 30px;
  border: 1px solid var(--border-color);
  background: rgba(255,255,255,0.05);
}

form input, form select, form label, form button {
  background: transparent;
  color: var(--text-light);
  display: block;
  width: 100%;
  margin: 10px 0;
  font-size: 14px;
}

form input[type="text"],
form input[type="time"],
form input[type="file"],
form select {
  padding: 10px;
  border: 1px solid var(--border-color);
  border-radius: 6px;
}

form button {
  background-color: var(--button-bg);
  color: #fff;
  padding: 10px;
  font-weight: bold;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: 0.3s;
}

form button:hover {
  background-color: var(--button-hover);
}

/* ---------- SEARCH/SORT ---------- */
.search-sort form {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items: center;
}

.search-sort input,
.search-sort select {
  padding: 8px 12px;
  border: 1px solid var(--border-color);
  border-radius: 6px;
}

.search-sort button {
  padding: 8px 16px;
  background-color: var(--button-bg);
  color: #fff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: 0.3s ease;
}

.search-sort button:hover {
  background-color: var(--button-hover);
}

/* ---------- TABLE ---------- */
table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background-color: rgba(0,0,0,0.6);
  color: #fff;
}

th, td {
  color: var(--text-light);
  padding: 14px;
  text-align: center;
  border-bottom: 1px solid var(--border-color);
  vertical-align: middle;
}

td img {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 8px;
  border: 2px solid var(--border-color);
}

tbody tr:hover {
  background-color: rgba(212,163,115,0.07);
}

.actions a {
  color: var(--brand-accent);
  text-decoration: none;
  margin: 0 5px;
  font-weight: 500;
}

.actions a:hover {
  text-decoration: underline;
}

/* ---------- TOGGLE BUTTON ---------- */
.toggle-btn {
  display: none;
  position: fixed;
  top: 20px;
  left: 20px;
  background: var(--button-bg);
  color: #fff;
  padding: 10px 14px;
  font-size: 18px;
  border: none;
  border-radius: 6px;
  z-index: 1001;
  cursor: pointer;
}

/* ---------- RESPONSIVE ---------- */
@media (max-width: 991px) {
  .sidebar {
    transform: translateX(-100%);
  }

  .sidebar.active {
    transform: translateX(0);
  }

  .main-content {
    margin-left: 0;
  }

  .toggle-btn {
    display: block;
  }

  body.sidebar-open {
    overflow: hidden;
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
    <!-- Place PHP and form/table HTML content here -->
     
  <h2><?= $editEmp ? "Update Employee" : "Add New Employee" ?></h2>
  <?php if (isset($empMsg)) echo "<p class='message'>$empMsg</p>"; ?>

  <form method="post" enctype="multipart/form-data">
    <?php if ($editEmp): ?>
      <input type="hidden" name="emp_id" value="<?= $editEmp['id'] ?>">
    <?php endif; ?>
    <input type="text" name="emp_name" placeholder="Name" value="<?= $editEmp['name'] ?? '' ?>" required>
    <input type="file" name="emp_image" accept="image/*" <?= $editEmp ? '' : 'required' ?>>
    <input type="text" name="emp_designation" placeholder="Role" value="<?= $editEmp['designation'] ?? '' ?>" required>
    <input type="text" name="emp_experience" placeholder="Experience" value="<?= $editEmp['experience'] ?? '' ?>" required>
    <input type="text" name="emp_available_days" placeholder="Available Days" value="<?= $editEmp['available_days'] ?? '' ?>" required>
    <label>Available From:</label>
    <input type="time" name="emp_available_time_from" value="<?= $editEmp['available_time_from'] ?? '' ?>" required>
    <label>Available To:</label>
    <input type="time" name="emp_available_time_to" value="<?= $editEmp['available_time_to'] ?? '' ?>" required>
    <button type="submit" name="add_employee"><?= $editEmp ? "Update" : "Add" ?> Employee</button>
  </form>

  <div class="search-sort">
    <form method="get">
      <input type="text" name="search" placeholder="Search by name or role" value="<?= htmlspecialchars($search) ?>">
      <select name="sort">
        <option value="created_at" <?= $sort=='created_at'?'selected':'' ?>>Newest</option>
        <option value="name" <?= $sort=='name'?'selected':'' ?>>Name</option>
        <option value="designation" <?= $sort=='designation'?'selected':'' ?>>Role</option>
      </select>
      <select name="order">
        <option value="DESC" <?= $order=='DESC'?'selected':'' ?>>Descending</option>
        <option value="ASC" <?= $order=='ASC'?'selected':'' ?>>Ascending</option>
      </select>
      <button type="submit">Apply</button>
    </form>
  </div>

  <h2>All Employees</h2>
  <table>
    <thead>
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Role</th>
        <th>Experience</th>
        <th>Availability</th>
        <th>Created</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($emp = $allEmployees->fetch_assoc()): ?>
        <tr>
          <td><img src="uploads/<?= $emp['image'] ?>" alt=""></td>
          <td><?= $emp['name'] ?></td>
          <td><?= $emp['designation'] ?></td>
          <td><?= $emp['experience'] ?></td>
          <td><?= $emp['available_days'] ?><br><?= date('g:i A', strtotime($emp['available_time_from'])) ?> - <?= date('g:i A', strtotime($emp['available_time_to'])) ?></td>
          <td><?= date('d M Y', strtotime($emp['created_at'])) ?></td>
          <td class="actions">
            <a href="?edit_id=<?= $emp['id'] ?>">Update</a> |
            <a href="?delete_id=<?= $emp['id'] ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      const body = document.querySelector('body');
      sidebar.classList.toggle('active');
      body.classList.toggle('sidebar-open');
    }
  </script>


</body>
</html>
