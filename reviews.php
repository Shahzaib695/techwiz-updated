<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

// Fetch logged-in user
$userQuery = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
$userQuery->bind_param("s", $email);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user = $userResult->fetch_assoc();

if (!$user || $user['role'] !== 'designer') {
    header("Location: login.php");
    exit;
}

// Fetch profile (if you use designer_profiles)
$profileQuery = $conn->prepare("SELECT * FROM designer_profiles WHERE user_id=? LIMIT 1");
$profileQuery->bind_param("i", $user['id']);
$profileQuery->execute();
$profileResult = $profileQuery->get_result();
$profile = $profileResult->fetch_assoc();

// Fetch reviews for this designer
$reviews = [];
if ($profile && strtolower(trim($profile['status'])) === 'approved') {
    $reviewQuery = $conn->prepare("
        SELECT r.*, u.name AS user_name
        FROM reviews r
        LEFT JOIN users u ON r.user_id = u.id
        WHERE LOWER(r.target_type) = 'designer' AND r.target_id = ?
        ORDER BY r.created_at DESC
    ");
    $reviewQuery->bind_param("i", $user['id']);
    $reviewQuery->execute();
    $res = $reviewQuery->get_result();
    if ($res) {
        $reviews = $res->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Designer Reviews | DECORVISTA</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* keep your existing styles — paste the CSS from your other dashboard files */
:root{--brand-accent:#d4a373;--brand-hover:#b89560;--text-light:#f1f1f1;--bg-dark:#0f0f0f;--card-bg:rgba(255,255,255,0.06);--sidebar-width:260px;--shadow-glow:0 0 16px var(--brand-accent);--glass-blur:blur(16px)}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Poppins',sans-serif;background:var(--bg-dark);color:var(--text-light);min-height:100vh;overflow-x:hidden;}
.sidebar{width:var(--sidebar-width);background:linear-gradient(180deg, rgba(212,163,115,0.1), rgba(0,0,0,0.7)), url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;position:fixed;height:100vh;padding:20px;border-right:1px solid rgba(255,255,255,0.08);backdrop-filter:var(--glass-blur);border-radius:0 20px 20px 0;}
.sidebar .logo{text-align:center;margin-bottom:30px;}
.sidebar .logo img{width:130px;border-radius:14px;border:2px solid var(--brand-accent);box-shadow:0 0 22px rgba(212,163,115,0.4);}
.sidebar nav a{display:flex;align-items:center;color:#fff;text-decoration:none;padding:12px 16px;margin:8px 0;border-radius:14px;font-weight:500;font-size:16px;gap:12px;transition:0.35s ease, transform 0.2s ease;}
.sidebar nav a:hover{background:rgba(0,0,0,0.5);color:var(--brand-accent);box-shadow:var(--shadow-glow);transform:translateX(8px);}
.main-content{margin-left:var(--sidebar-width);padding:40px;position:relative;overflow:hidden;min-height:100vh;background: url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;backdrop-filter:blur(5px);}
.main-content::before{content:'';position:absolute;inset:0;background:rgba(0,0,0,0.55);z-index:0;}
.main-content > *{position:relative;z-index:1;}
.topbar{margin-top:30px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,0.12);padding-bottom:20px;flex-wrap:wrap;gap:12px;}
.topbar h1{font-size:34px;color:var(--brand-accent);text-shadow:0 0 12px var(--brand-accent);}
.admin-info{display:flex;align-items:center;gap:18px;font-size:15px;color:var(--text-light);}
.cards{margin-top:40px;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;}
.card{background:var(--card-bg);padding:20px;border-radius:18px;transition:0.3s;border:1px solid rgba(255,255,255,0.05);box-shadow:0 0 18px rgba(212,163,115,0.2);}
.card:hover{transform:translateY(-5px);}
.card .stars{color:gold;font-size:16px;margin-bottom:6px;}
.card h3{margin-bottom:4px;font-size:18px;}
.card p{font-size:14px;line-height:1.5;color:#ddd;}
.pending-box{grid-column:1/-1;text-align:center;color:yellow;background:rgba(255,255,0,0.1);padding:30px;border-radius:14px;}
footer{margin-top:40px;text-align:center;font-size:14px;color:#aaa;padding-bottom:30px;}
footer span{color:var(--brand-accent);font-weight:500;}
@media(max-width:1024px){.main-content{margin-left:0;}.sidebar{transform:translateX(-100%);}.sidebar.active{transform:translateX(0);}}
@media(max-width:768px){.main-content{padding:24px;}.topbar{flex-direction:column;align-items:flex-start;}.cards{grid-template-columns:1fr;}}
</style>
</head>
<body>
<button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
<div class="sidebar">
  <div class="logo"><img src="decorevistaimages/logo.png" alt="Logo"></div>
  <nav>
    <a href="designer-dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="appointments.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
    <!-- <a href="add-design.php"><i class="fa-solid fa-pen-to-square"></i> Add Designs</a> -->
    <a href="reviews.php"><i class="fa-solid fa-star"></i> Reviews</a>
    <a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a>
    <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
  </nav>
</div>

<div class="main-content">
  <div class="topbar">
    <h1>Reviews</h1>
    <div class="admin-info">
      <i class="fa-solid fa-user-shield"></i>
      <span><?= htmlspecialchars($email) ?></span>
    </div>
  </div>

  <div class="cards">
    <?php if (!$profile || strtolower(trim($profile['status'])) !== 'approved'): ?>
      <div class="pending-box">
        <i class="fa-solid fa-hourglass-half fa-2x"></i>
        <h3>Your profile is awaiting admin approval.</h3>
        <p>You will be able to see reviews once approved.</p>
      </div>
    <?php elseif (empty($reviews)): ?>
      <div class="card">
        <h3>No Reviews Yet</h3>
        <p>You have not received any reviews from homeowners.</p>
      </div>
    <?php else: ?>
      <?php foreach ($reviews as $review): ?>
        <div class="card">
          <div class="stars">
            <?php for ($i=1;$i<=5;$i++): ?>
              <i class="<?= $i <= (int)$review['rating'] ? 'fas' : 'far' ?> fa-star"></i>
            <?php endfor; ?>
          </div>
          <h3><?= htmlspecialchars($review['user_name'] ?? 'User') ?></h3>
          <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
          <small>🗓 <?= htmlspecialchars(date("d M Y", strtotime($review['created_at'] ?? 'now'))) ?></small>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <footer>&copy; <?= date("Y") ?> <span>DECORVISTA</span> | Designer Panel</footer>
</div>

<script>
function toggleSidebar(){document.querySelector('.sidebar').classList.toggle('active');}
</script>
</body>
</html>
