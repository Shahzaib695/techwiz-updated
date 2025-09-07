<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

// Fetch designer user
$userQuery = $conn->prepare("SELECT * FROM users WHERE email=?");
$userQuery->bind_param("s", $email);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user = $userResult->fetch_assoc();

if (!$user || $user['role'] !== 'designer') {
    header("Location: login.php");
    exit;
}

// Fetch designer profile
$profileQuery = $conn->prepare("SELECT * FROM designer_profiles WHERE user_id=? LIMIT 1");
$profileQuery->bind_param("i", $user['id']);
$profileQuery->execute();
$profileResult = $profileQuery->get_result();
$profile = $profileResult->fetch_assoc();

// If no profile or not approved
$notApproved = !$profile || $profile['status'] !== 'Approved';

// Cancel appointment (only if approved)
if (!$notApproved && isset($_GET['cancel_id'])) {
    $cancelId = intval($_GET['cancel_id']);
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id=? AND person_name=?");
    $stmt->bind_param("is", $cancelId, $user['name']); // match the designer name
    $stmt->execute();
    header("Location: appointments.php");
    exit;
}

// Fetch appointments (only if approved)
if (!$notApproved) {
    $appointmentsQuery = $conn->prepare("
        SELECT a.id, a.date, a.time, u.name AS client_name, u.email AS client_email
        FROM appointments a
        JOIN users u ON a.user_id = u.id
        WHERE a.person_name=?
        ORDER BY a.date DESC, a.time DESC
    ");
    $appointmentsQuery->bind_param("s", $user['name']); // match the designer name
    $appointmentsQuery->execute();
    $appointments = $appointmentsQuery->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Appointments | DECORVISTA</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root {
    --brand-accent: #d4a373;
    --brand-hover: #b89560;
    --text-light: #f1f1f1;
    --bg-dark: #0f0f0f;
    --card-bg: rgba(255,255,255,0.06);
    --sidebar-width: 260px;
    --shadow-glow: 0 0 16px var(--brand-accent);
    --glass-blur: blur(16px);
}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Poppins',sans-serif;background:var(--bg-dark);color:var(--text-light);min-height:100vh;overflow-x:hidden;}
.sidebar{width:var(--sidebar-width);background:linear-gradient(180deg, rgba(212,163,115,0.1), rgba(0,0,0,0.7)), url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;position:fixed;height:100vh;padding:20px;border-right:1px solid rgba(255,255,255,0.08);backdrop-filter:var(--glass-blur);border-radius:0 20px 20px 0;transition:0.3s;}
.sidebar .logo{text-align:center;margin-bottom:30px;}
.sidebar .logo img{width:130px;border-radius:14px;border:2px solid var(--brand-accent);box-shadow:0 0 22px rgba(212,163,115,0.4);}
.sidebar nav a{display:flex;align-items:center;color:#fff;text-decoration:none;padding:12px 16px;margin:8px 0;border-radius:14px;font-weight:500;font-size:16px;gap:12px;transition:0.35s ease, transform 0.2s ease;}
.sidebar nav a:hover{background:rgba(0,0,0,0.5);color:var(--brand-accent);box-shadow:var(--shadow-glow);transform:translateX(8px);}
.sidebar-toggle{display:none;position:fixed;top:18px;left:18px;background:var(--brand-accent);color:#fff;border:none;padding:10px 14px;border-radius:8px;font-size:20px;cursor:pointer;z-index:1100;}
.main-content{margin-left:var(--sidebar-width);padding:40px;position:relative;overflow:hidden;min-height:100vh;background: url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;backdrop-filter:blur(5px);}
.main-content::before{content:'';position:absolute;inset:0;background:rgba(0,0,0,0.55);z-index:0;}
.main-content > *{position:relative;z-index:1;}
.topbar{margin-top:30px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,0.12);padding-bottom:20px;flex-wrap:wrap;gap:12px;}
.topbar h1{font-size:34px;color:var(--brand-accent);text-shadow:0 0 12px var(--brand-accent);}
.admin-info{display:flex;align-items:center;gap:18px;font-size:15px;color:var(--text-light);}
.admin-info i{color:var(--brand-accent);}
.cards{display:grid;grid-template-columns:1fr;gap:30px;margin-top:40px;}
.card{background:var(--card-bg);padding:26px;border-radius:18px;text-align:center;transition:0.4s;border:1px solid rgba(255,255,255,0.05);box-shadow:0 0 18px rgba(212,163,115,0.2);}
.card:hover{transform:translateY(-6px) scale(1.02);box-shadow:0 0 28px rgba(212,163,115,0.35);}
.card h3{margin-bottom:14px;color:var(--brand-accent);}
table{width:100%;border-collapse:collapse;margin-top:20px;}
table th, table td{padding:12px;border-bottom:1px solid rgba(255,255,255,0.15);text-align:center;}
.cancel-btn{color:#fff;background:#e74c3c;padding:6px 12px;border-radius:6px;text-decoration:none;transition:0.3s;}
.cancel-btn:hover{background:#c0392b;}
footer{margin-top:40px;text-align:center;font-size:14px;color:#aaa;padding-bottom:30px;}
footer span{color:var(--brand-accent);font-weight:500;}
@media(max-width:1024px){.main-content{margin-left:0;}.sidebar{transform:translateX(-100%);}.sidebar.active{transform:translateX(0);}.sidebar-toggle{display:block;}}
@media(max-width:768px){.main-content{padding:24px;}.topbar{flex-direction:column;align-items:flex-start;}.admin-info{flex-direction:column;align-items:flex-start;gap:6px;}}
</style>
</head>
<body>
<button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
<div class="sidebar">
  <div class="logo"><img src="decorevistaimages/logo.png" alt="Logo"></div>
  <nav>
    <a href="designer-dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="appointments.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
    <a href="add-design.php"><i class="fa-solid fa-pen-to-square"></i> Add Designs</a>
    <a href="reviews.php"><i class="fa-solid fa-star"></i> Reviews</a>
    <a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a>
    <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
  </nav>
</div>

<div class="main-content">
  <div class="topbar">
    <h1>Appointments</h1>
    <div class="admin-info">
      <i class="fa-solid fa-user-shield"></i>
      <span><?= htmlspecialchars($email) ?></span>
      <span id="datetime"></span>
    </div>
  </div>

  <div class="cards">
    <div class="card">
      <?php if ($notApproved): ?>
        <h3 style="color:yellow;"><i class="fa-solid fa-hourglass-half"></i> Approval Pending</h3>
        <p>Your profile is awaiting admin approval. You cannot view appointments until approved.</p>
      <?php else: ?>
        <h3><i class="fa-solid fa-calendar-check"></i> My Appointments</h3>
        <?php if ($appointments->num_rows > 0): ?>
          <table>
            <tr>
              <th>Client</th>
              <th>Email</th>
              <th>Date</th>
              <th>Time</th>
              <th>Action</th>
            </tr>
            <?php while($row = $appointments->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['client_name']) ?></td>
                <td><?= htmlspecialchars($row['client_email']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= htmlspecialchars($row['time']) ?></td>
                <td><a class="cancel-btn" href="?cancel_id=<?= $row['id'] ?>" onclick="return confirm('Cancel this appointment?')">Cancel</a></td>
              </tr>
            <?php endwhile; ?>
          </table>
        <?php else: ?>
          <p>No appointments yet.</p>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>

  <footer>
    &copy; <?= date("Y") ?> <span>DECORVISTA</span> | Designer Panel
  </footer>
</div>

<script>
function toggleSidebar() {
  document.querySelector('.sidebar').classList.toggle('active');
}
function updateDateTime() {
  const now = new Date();
  document.getElementById('datetime').textContent =
    now.toLocaleString('en-GB',{day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
setInterval(updateDateTime,1000);
updateDateTime();
</script>
</body>
</html>
