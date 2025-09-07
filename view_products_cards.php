<?php
session_start();
include 'db.php';

// ✅ Admin session check
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.com') {
    header("Location: login.php");
    exit;
}

// ✅ Session timeout
if (isset($_SESSION['time']) && time() - $_SESSION['time'] > 600) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
} else {
    $_SESSION['time'] = time();
}

$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Products | ELEGANCE SALONE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Reuse Full Neon Admin Theme */
    /* ---------- DecorVista Admin Theme ---------- */
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
  z-index: 1001;
  border-radius: 0 20px 20px 0;
  transition: transform 0.3s ease-in-out;
}

.sidebar .logo img {
  width: 130px;
  border-radius: 14px;
  border: 2px solid var(--brand-accent);
  box-shadow: var(--shadow-glow);
  display: block;
  margin: 0 auto 25px;
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
  padding: 40px;
  flex: 1;
  width: calc(100% - var(--sidebar-width));
  background: url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg ') center/cover no-repeat;
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

/* ---------- HEADINGS ---------- */
h2 {
  font-size: 32px;
  color: var(--brand-accent);
  margin-bottom: 30px;
  text-align: center;
  text-shadow: 0 0 10px var(--brand-accent);
}

/* ---------- PRODUCT GRID ---------- */
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 2rem;
  justify-items: center;
}

.product-card {
  background: var(--card-bg);
  border-radius: 20px;
  padding: 18px;
  border: 1px solid var(--brand-accent);
  text-align: center;
  color: var(--text-light);
  box-shadow: 0 8px 22px rgba(0,0,0,0.35);
  transition: 0.3s ease, transform 0.3s ease;
  max-width: 300px;
}

.product-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 12px 30px rgba(212,163,115,0.4);
}

.product-card img {
  width: 100%;
  height: 220px;
  object-fit: cover;
  border-radius: 12px;
  margin-bottom: 16px;
  border: 1px solid rgba(255,255,255,0.1);
}

.product-card h3 {
  font-size: 20px;
  margin-bottom: 6px;
}

.product-price {
  color: var(--brand-accent);
  font-weight: 600;
  margin-bottom: 14px;
}

.buy-btn {
  background: var(--brand-accent);
  color: var(--accent-light);
  padding: 10px 24px;
  border: none;
  border-radius: 30px;
  cursor: pointer;
  transition: background 0.3s ease, box-shadow 0.3s ease;
}

.buy-btn:hover {
  background: var(--brand-hover);
  box-shadow: var(--shadow-glow);
}

/* ---------- MODAL ---------- */
.product-modal {
  display: none;
  position: fixed;
  z-index: 9999;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0,0,0,0.8);
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.modal-content {
  background: rgba(15,15,15,0.95);
  padding: 30px;
  border-radius: 16px;
  max-width: 480px;
  color: #fff;
  border: 1px solid var(--brand-accent);
  text-align: center;
  position: relative;
}

.modal-content img {
  max-width: 100%;
  border-radius: 12px;
  margin-bottom: 20px;
}

.modal-content .close {
  position: absolute;
  top: 20px;
  right: 30px;
  font-size: 30px;
  cursor: pointer;
  color: #fff;
}

/* ---------- FOOTER ---------- */
footer {
  text-align: center;
  padding: 40px 10px 20px;
  font-size: 14px;
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
    width: 100%;
    padding: 20px;
    border-radius: 0;
  }
  .sidebar {
    transform: translateX(-100%);
    position: fixed;
  }
  .sidebar.active {
    transform: translateX(0);
  }
  .toggle-btn {
    display: block;
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
  <h2>All Products</h2>
  <div class="product-grid">
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
      <div class="product-card">
        <img src="<?= $row['image']; ?>" alt="<?= $row['name']; ?>">
        <h3><?= $row['name']; ?></h3>
        <p class="product-price">Rs. <?= $row['price']; ?></p>
        <!-- <button class="buy-btn" onclick="openModal(<?= $row['id']; ?>)">Buy Now</button> -->
      </div>

      <div id="modal<?= $row['id']; ?>" class="product-modal">
        <div class="modal-content">
          <span class="close" onclick="closeModal(<?= $row['id']; ?>)">&times;</span>
          <img src="<?= $row['image']; ?>" alt="<?= $row['name']; ?>">
          <h3><?= $row['name']; ?></h3>
          <p><?= $row['description']; ?></p>
          <label>Quantity:</label>
          <input type="number" id="qty<?= $row['id']; ?>" min="1" value="1">
          <br><br>
          <!-- <button class="buy-btn">Add to Bag</button> -->
        </div>
      </div>
    <?php endwhile; ?>
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

  function openModal(id) {
    document.getElementById("modal" + id).style.display = "flex";
  }

  function closeModal(id) {
    document.getElementById("modal" + id).style.display = "none";
  }
</script>

</body>
</html>
