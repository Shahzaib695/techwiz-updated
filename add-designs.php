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

// Approval check
if (!$profile || $profile['status'] !== 'Approved') {
    $notApproved = true;
} else {
    $notApproved = false;
}

// Handle design submission
$msg = "";
if (!$notApproved && $_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['add_design'])) {
    $title = trim($_POST['title']);
    $desc  = trim($_POST['description']);

    if (empty($title) || empty($desc) || empty($_FILES['image']['name'])) {
        $msg = "<p style='color:red;'>⚠️ Please fill all fields and upload an image.</p>";
    } else {
        $target_dir = "uploads/designs/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $filename = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $target_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO designs (designer_id, title, description, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user['id'], $title, $desc, $targetFile);
            $stmt->execute();
            $msg = "<p style='color:lime;'>✅ Design added successfully!</p>";
        } else {
            $msg = "<p style='color:red;'>❌ Failed to upload image.</p>";
        }
    }
}

// Fetch existing designs
if (!$notApproved) {
    $designsQuery = $conn->prepare("SELECT * FROM designs WHERE designer_id=? ORDER BY id DESC");
    $designsQuery->bind_param("i", $user['id']);
    $designsQuery->execute();
    $designs = $designsQuery->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Designs | DECORVISTA</title>
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
.card{background:var(--card-bg);padding:26px;border-radius:18px;transition:0.4s;border:1px solid rgba(255,255,255,0.05);box-shadow:0 0 18px rgba(212,163,115,0.2);}
.card h3{margin-bottom:14px;color:var(--brand-accent);}
form input, form textarea{width:100%;padding:10px;margin:8px 0;border-radius:8px;border:none;background:rgba(255,255,255,0.08);color:#fff;}
form input::placeholder, form textarea::placeholder{color:rgba(255,255,255,0.6);}
form button{background:var(--brand-accent);padding:10px 14px;border:none;border-radius:8px;color:#fff;font-weight:600;margin-top:10px;cursor:pointer;transition:0.3s;}
form button:hover{background:var(--brand-hover);}
.design-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-top:20px;}
.design-card{background:rgba(255,255,255,0.05);border-radius:12px;padding:10px;text-align:center;}
.design-card img{width:100%;border-radius:10px;margin-bottom:10px;}
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
    <h1>Add Designs</h1>
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
        <p>Your profile is awaiting admin approval. You cannot add designs until approved.</p>
      <?php else: ?>
        <h3><i class="fa-solid fa-pen-to-square"></i> Upload New Design</h3>
        <?= $msg ?>
        <form method="POST" enctype="multipart/form-data">
          <input type="text" name="title" placeholder="Design Title" required>
          <textarea name="description" placeholder="Short Description" rows="3" required></textarea>
          <input type="file" name="image" accept="image/*" required>
          <button type="submit" name="add_design">Add Design</button>
        </form>

        <h3 style="margin-top:30px;">My Designs</h3>
        <?php if ($designs->num_rows > 0): ?>
          <div class="design-grid">
            <?php while($d = $designs->fetch_assoc()): ?>
              <div class="design-card">
                <img src="<?= htmlspecialchars($d['image']) ?>" alt="Design">
                <h4><?= htmlspecialchars($d['title']) ?></h4>
                <p><?= htmlspecialchars($d['description']) ?></p>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <p>No designs uploaded yet.</p>
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
