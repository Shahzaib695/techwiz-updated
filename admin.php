<?php
session_start();
include 'db.php';

date_default_timezone_set('Asia/Karachi');

// ✅ Admin session check
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.com') {
    header("Location: login.php");
    exit;
}

// ✅ Session timeout after 10 minutes
if (isset($_SESSION['time']) && time() - $_SESSION['time'] > 600) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
} else {
    $_SESSION['time'] = time();
}


// USERS
$userCount = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];

// PRODUCTS
$productCount = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];


// Appointments
$appointmentApproved = $conn->query("SELECT COUNT(*) AS count FROM appointments WHERE status = 'Approved'")->fetch_assoc()['count'];
$appointmentPending = $conn->query("SELECT COUNT(*) AS count FROM appointments WHERE status = 'Pending'")->fetch_assoc()['count'];
$appointmentRejected = $conn->query("SELECT COUNT(*) AS count FROM appointments WHERE status = 'Rejected'")->fetch_assoc()['count'];

// Orders
$orderApproved = $conn->query("SELECT COUNT(*) AS count FROM orders WHERE status = 'Approved'")->fetch_assoc()['count'];
$orderPending = $conn->query("SELECT COUNT(*) AS count FROM orders WHERE status = 'Pending'")->fetch_assoc()['count'];
$orderRejected = $conn->query("SELECT COUNT(*) AS count FROM orders WHERE status = 'Rejected'")->fetch_assoc()['count'];

// Packages Order
$packageOrderApproved = $conn->query("SELECT COUNT(*) AS count FROM package_orders WHERE status = 'Approved'")->fetch_assoc()['count'];
$packageOrderPending = $conn->query("SELECT COUNT(*) AS count FROM package_orders WHERE status = 'Pending'")->fetch_assoc()['count'];
$packageOrderRejected = $conn->query("SELECT COUNT(*) AS count FROM package_orders WHERE status = 'Rejected'")->fetch_assoc()['count'];


$totalAppointments = $appointmentApproved + $appointmentPending + $appointmentRejected;
$totalProductOrders = $orderApproved + $orderPending + $orderRejected;
$totalPackageOrders = $packageOrderApproved + $packageOrderPending + $packageOrderRejected;



?>

 <style>
  * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html, body {
  height: 100%;
  font-family: 'Barlow Condensed', sans-serif;
  overflow: hidden;
  background: #111;
}

#loader {
  position: fixed;
  inset: 0;
  background: url('decorevistaimages/214ae3e55fb608d768b3bc455ddcf18a.jpg') center/cover no-repeat;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  z-index: 9999;
  transition: opacity 0.8s ease, visibility 0.8s ease;
  animation: zoomBg 5s ease-out forwards;
  padding: 20px;
  text-align: center;
}

@keyframes zoomBg {
  0% { transform: scale(1); }
  100% { transform: scale(1.05); }
}

#loader::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
}

#loader.hide {
  opacity: 0;
  visibility: hidden;
}

.logo {
  max-width: 180px;
  z-index: 2;
  animation: logoIn 1s ease-out forwards;
  opacity: 0;
  transform: translateY(30px) skewY(6deg);
}

@keyframes logoIn {
  to {
    opacity: 1;
    transform: translateY(0) skewY(0deg);
  }
}

.circle {
  width: 60px;
  height: 60px;
  border: 5px solid rgba(255, 255, 255, 0.2);
  border-top: 5px solid #6c5ce7;
  border-radius: 50%;
  animation: spin 1.2s linear infinite, glow 1.5s ease-in-out infinite alternate;
  margin: 25px 0;
  z-index: 2;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

@keyframes glow {
  0%   { box-shadow: 0 0 6px #6c5ce7; }
  100% { box-shadow: 0 0 20px #6c5ce7; }
}

.text {
  font-size: 2.5rem;
  color: #fff;
  text-transform: uppercase;
  letter-spacing: 3px;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 6px;
  z-index: 2;
  text-align: center;
}

.text span {
  display: inline-block;
  opacity: 0;
  transform: translateY(40px) skewY(6deg);
  animation: letterIn 0.8s ease forwards;
}

.text span:nth-child(1) { animation-delay: 1.1s; }
.text span:nth-child(2) { animation-delay: 1.2s; }
.text span:nth-child(3) { animation-delay: 1.3s; }
.text span:nth-child(4) { animation-delay: 1.4s; }
.text span:nth-child(5) { animation-delay: 1.5s; }
.text span:nth-child(6) { animation-delay: 1.6s; }
.text span:nth-child(7) { animation-delay: 1.7s; }
.text span:nth-child(8) { animation-delay: 1.8s; }
.text span:nth-child(9) { animation-delay: 1.9s; }
.text span:nth-child(10) { animation-delay: 2s; }
.text span:nth-child(11) { animation-delay: 2.1s; }
.text span:nth-child(12) { animation-delay: 2.2s; }

@keyframes letterIn {
  to {
    opacity: 1;
    transform: translateY(0) skewY(0deg);
  }
}

.swipe-line {
  position: absolute;
  top: 50%;
  left: -100%;
  width: 100%;
  height: 4px;
  background: linear-gradient(90deg, transparent, #fff, transparent);
  animation: knifeSwipe 1.2s ease-out 1.2s forwards;
  z-index: 3;
}

@keyframes knifeSwipe {
  to { left: 100%; }
}

.main {
  display: none;
  opacity: 0;
  text-align: center;
  color: #111;
  padding: 60px 20px;
}

body.loaded {
  overflow: auto;
}

body.loaded .main {
  display: block;
  animation: fadeInMain 1s ease forwards 0.5s;
}

@keyframes fadeInMain {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* —————— RESPONSIVE QUERIES —————— */
@media (max-width: 768px) {
  .logo {
    max-width: 150px;
  }

  .text {
    font-size: 2rem;
    gap: 5px;
  }

  .circle {
    width: 50px;
    height: 50px;
  }
}

@media (max-width: 480px) {
  .logo {
    max-width: 120px;
  }

  .text {
    font-size: 1.4rem;
    letter-spacing: 2px;
    gap: 4px;
    flex-direction: row;
  }

  .circle {
    width: 40px;
    height: 40px;
    margin: 20px 0;
  }

  .main {
    padding: 40px 15px;
  }
}

  </style>
</head>
<body>

   <!-- Loader -->
  <div id="loader">
  <img src="decorevistaimages/logo.png" height="200px"  alt="Logo" class="logo" />

    <div class="circle"></div>
    <div class="text">
      <span>D</span><span>E</span><span>C</span><span>O</span><span>R</span><span>V</span><span>I</span>
      <span></span><span>S</span><span>T</span><span>A</span>
    </div>
    <div class="swipe-line"></div>
  </div>



  <!-- Optional Sound -->
  <audio id="loaderSound" src="load.mp3" preload="auto"></audio>

  <script>
    window.addEventListener("load", () => {
      const sound = document.getElementById("loaderSound");
      if (sound) {
        sound.volume = 0.4;
        sound.play().catch(() => {});
      }

      setTimeout(() => {
        document.body.classList.add("loaded");
        document.getElementById("loader").classList.add("hide");
      }, 2800); // Match animation timing
    });
  </script>
<?php
// session_start();

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.com') {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['time']) && time() - $_SESSION['time'] > 600) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
} else {
    $_SESSION['time'] = time();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | DECORVISTA</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
:root {
  --brand-accent: #d4a373; /* DecorVista gold */
  --brand-hover: #b89560;
  --text-light: #f1f1f1;
  --bg-dark: #0f0f0f;
  --card-bg: rgba(255, 255, 255, 0.06);
  --sidebar-width: 260px;
  --shadow-glow: 0 0 16px var(--brand-accent);
  --glass-blur: blur(16px);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html, body {
  min-height: 100%;
  font-family: 'Poppins', sans-serif;
  background: var(--bg-dark);
  color: var(--text-light);
  overflow-x: hidden;
}

body.loaded {
  overflow-y: auto;
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
  border-right: 1px solid rgba(255,255,255,0.08);
  backdrop-filter: var(--glass-blur);
  z-index: 999;
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
  box-shadow: 0 0 22px rgba(212,163,115,0.4);
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
  background: rgba(0, 0, 0, 0.5);
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
  flex: 1;
  margin-left: var(--sidebar-width);
  padding: 40px;
  background: url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
  backdrop-filter: blur(5px);
  position: relative;
  overflow: hidden;
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

/* ---------- TOPBAR ---------- */
.topbar {
  margin-top: 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid rgba(255,255,255,0.12);
  padding-bottom: 20px;
  flex-wrap: wrap;
  gap: 12px;
}

.topbar h1 {
  font-size: 34px;
  color: var(--brand-accent);
  text-shadow: 0 0 12px var(--brand-accent);
}

.admin-info {
  display: flex;
  align-items: center;
  gap: 18px;
  font-size: 15px;
  color: var(--text-light);
}

.admin-info i {
  color: var(--brand-accent);
}

/* ---------- CARDS ---------- */
.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
  gap: 30px;
  margin-top: 40px;
}

.card {
  background: var(--card-bg);
  padding: 26px;
  border-radius: 18px;
  text-align: center;
  transition: 0.4s;
  border: 1px solid rgba(255,255,255,0.05);
  box-shadow: 0 0 18px rgba(212,163,115,0.2);
  cursor: pointer;
}

.card:hover {
  transform: translateY(-6px) scale(1.03);
  box-shadow: 0 0 28px rgba(212,163,115,0.35);
}

.card i {
  font-size: 38px;
  margin-bottom: 12px;
  color: var(--brand-accent);
}

.card h3 {
  margin-bottom: 4px;
}

.card p {
  font-size: 24px;
  font-weight: bold;
}

/* ---------- RECENT ACTIVITY ---------- */
.recent-activity {
  overflow: hidden;
  background: #111;
  border-radius: 16px;
  box-shadow: 0 8px 24px rgba(212,163,115,0.1);
  padding: 24px 30px;
  margin-top: 40px;
  transition: 0.35s ease;
}

.recent-activity h2 {
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 20px;
  color: var(--brand-accent);
  border-left: 4px solid var(--brand-accent);
  padding-left: 12px;
}

.recent-activity table {
  width: 100%;
  border-collapse: collapse;
  font-size: 15px;
}

.recent-activity thead tr {
  background: #1a1a1a;
  color: #fff;
}

.recent-activity th, .recent-activity td {
  padding: 12px 14px;
  border-bottom: 1px solid #222;
}

.recent-activity tbody tr:hover {
  background-color: rgba(212,163,115,0.05);
  cursor: pointer;
  transition: background 0.3s;
}

.recent-activity td {
  color: #ddd;
}

.recent-activity td:first-child {
  font-weight: 500;
}

/* ---------- FOOTER ---------- */
footer {
  overflow: hidden;
  margin-top: 40px;
  text-align: center;
  font-size: 14px;
  color: #aaa;
  padding-bottom: 30px;
}

footer span {
  color: var(--brand-accent);
  font-weight: 500;
}

/* ---------- RESPONSIVE ---------- */
@media(max-width:1024px){
  .main-content { margin-left:0; }
  .sidebar { transform: translateX(-100%); }
  .sidebar.active { transform: translateX(0); }
  .sidebar-toggle { display: block; }
}

@media(max-width:768px){
  .main-content{ padding:24px; }
  .topbar{ flex-direction: column; align-items:flex-start; }
  .admin-info{ flex-direction: column; align-items:flex-start; gap:6px; }
  .cards{ grid-template-columns:1fr; }
}

@media(max-width:600px){
  .recent-activity{ padding:18px; }
  .recent-activity table, thead, tbody, th, td, tr{ display:block; width:100%; }
  .recent-activity thead{ display:none; }
  .recent-activity td{ position: relative; padding-left:50%; text-align:left; border-bottom:1px solid #222; }
  .recent-activity td::before{ position:absolute; top:12px; left:15px; width:45%; white-space:nowrap; font-weight:600; color:var(--brand-accent); }
  .recent-activity td:nth-child(1)::before{ content:"👤 Name"; }
  .recent-activity td:nth-child(2)::before{ content:"📧 Email"; }
  .recent-activity td:nth-child(3)::before{ content:"📅 Signup Date"; }
}

</style>
</head>
<body>
<!-- Your existing PHP + HTML structure should continue here -->

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
            <a href="designerapproval.php" style="background:rgba(255,255,255,0.15);"><i
                    class="fa-solid fa-users-viewfinder"></i> Approve New Designer</a>
            <a href="designerapproval.php" style="background:rgba(255,255,255,0.15);"><i
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
    <div class="topbar">
      <h1>Admin Dashboard</h1>
      <div class="admin-info">
        <i class="fa-solid fa-user-shield"></i>
        <span><?= htmlspecialchars($_SESSION['email']) ?></span>
        <span id="datetime"></span>
      </div>
    </div>

<div class="cards">
  <!-- USERS -->
  <div class="card">
    <i class="fa-solid fa-users"></i>
    <h3>Users</h3>
    <p><?= $userCount ?></p>
  </div>

<!-- Status Summary Section -->
  <div class="card">
    <i class="fa-solid fa-calendar-check"></i>
    <h3>Appointments</h3>
    <p>✅ Approved: <?= $appointmentApproved ?></p>
    <p>⏳ Pending: <?= $appointmentPending ?></p>
    <p>❌ Rejected: <?= $appointmentRejected ?></p>
  </div>

  <div class="card">
    <i class="fa-solid fa-file-invoice-dollar"></i>
    <h3>Product Orders</h3>
    <p>✅ Approved: <?= $orderApproved ?></p>
    <p>⏳ Pending: <?= $orderPending ?></p>
    <p>❌ Rejected: <?= $orderRejected ?></p>
  </div>

  <div class="card">
    <i class="fa-solid fa-box-open"></i>
    <h3>Package Orders</h3>
    <p>✅ Approved: <?= $packageOrderApproved ?></p>
    <p>⏳ Pending: <?= $packageOrderPending ?></p>
    <p>❌ Rejected: <?= $packageOrderRejected ?></p>
  </div>


  <!-- PRODUCTS -->
  <div class="card">
    <i class="fa-solid fa-boxes-stacked"></i>
    <h3>Products</h3>
    <p><?= $productCount ?></p>
  </div>

<!-- TOTAL APPOINTMENTS -->
<div class="card">
  <i class="fa-solid fa-calendar-days"></i>
  <h3>Total Appointments</h3>
  <p><?= $totalAppointments ?></p>
</div>

<!-- TOTAL PRODUCT ORDERS -->
<div class="card">
  <i class="fa-solid fa-cart-shopping"></i>
  <h3>Total Product Orders</h3>
  <p><?= $totalProductOrders ?></p>
</div>

<!-- TOTAL PACKAGE ORDERS -->
<div class="card">
  <i class="fa-solid fa-box-archive"></i>
  <h3>Total Package Orders</h3>
  <p><?= $totalPackageOrders ?></p>
</div>



</div>


<?php
include 'db.php'; // Make sure db connection is included

// Fetch 5 latest users
$activityQuery = "SELECT name, email, created_at FROM users ORDER BY created_at DESC LIMIT 5";
$activityResult = $conn->query($activityQuery);
?>

<!-- ✅ RECENT ACTIVITY UI SECTION -->
<div >
    <h2 >🕒 Recent User Activity</h2>
    
    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
        <thead>
            <tr >
                <th style="padding: 10px; text-align: left;">👤 Name</th>
                <th style="padding: 10px; text-align: left;">📧 Email</th>
                <th style="padding: 10px; text-align: left;">📅 Signup Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($activityResult && $activityResult->num_rows > 0): ?>
                <?php while ($row = $activityResult->fetch_assoc()): ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo htmlspecialchars($row['email']); ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="padding: 10px; color: #777;">No recent activity found.</td>
                </tr>
            <?php endif; ?>
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
    function updateDateTime() {
      const now = new Date();
      const formatted = now.toLocaleString('en-GB', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
      });
      document.getElementById('datetime').textContent = formatted;
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();
  </script>
</body>
</html>
