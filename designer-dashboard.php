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

// Check if designer profile exists
$profileQuery = $conn->prepare("SELECT * FROM designer_profiles WHERE user_id=? LIMIT 1");
$profileQuery->bind_param("i", $user['id']);
$profileQuery->execute();
$profileResult = $profileQuery->get_result();
$profile = $profileResult->fetch_assoc();

// Handle profile submission
$successMsg = $errorMsg = "";
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit_request']) && !$profile) {
    $name      = trim($_POST['name']);
    $bio       = trim($_POST['bio']);
    $expertise = trim($_POST['expertise']);
    $day       = $_POST['day'] ?? "";
    $from      = $_POST['from_time'] ?? "";
    $to        = $_POST['to_time'] ?? "";

    if (empty($name) || empty($bio) || empty($expertise) || empty($day) || empty($from) || empty($to)) {
        $errorMsg = "⚠️ Please fill all fields.";
    } else {
        $target_dir = "uploads/designers/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $filename = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $target_dir . $filename;
            move_uploaded_file($_FILES["image"]['tmp_name'], $targetFile);
            $imagePath = $targetFile;
        } else {
            $errorMsg = "⚠️ Please upload your profile image.";
        }

        if (!$errorMsg) {
            $stmt = $conn->prepare(
                "INSERT INTO designer_profiles (user_id, name, bio, expertise, image, day, from_time, to_time, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')"
            );
            $stmt->bind_param("isssssss", $user['id'], $name, $bio, $expertise, $imagePath, $day, $from, $to);
            $stmt->execute();
            $successMsg = "✅ Request submitted! Waiting for admin approval.";
            $profileQuery->execute();
            $profileResult = $profileQuery->get_result();
            $profile = $profileResult->fetch_assoc();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Designer Dashboard | DECORVISTA</title>
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

* {margin:0;padding:0;box-sizing:border-box;}
body {font-family:'Poppins',sans-serif;background:var(--bg-dark);color:var(--text-light);min-height:100vh;overflow-x:hidden;}
.sidebar {width:var(--sidebar-width);background:linear-gradient(180deg, rgba(212,163,115,0.1), rgba(0,0,0,0.7)), url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;position:fixed;height:100vh;padding:20px;border-right:1px solid rgba(255,255,255,0.08);backdrop-filter:var(--glass-blur);border-radius:0 20px 20px 0;transition:0.3s;}
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
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:30px;margin-top:40px;}
.card{background:var(--card-bg);padding:26px;border-radius:18px;text-align:center;transition:0.4s;border:1px solid rgba(255,255,255,0.05);box-shadow:0 0 18px rgba(212,163,115,0.2);cursor:pointer;}
.card:hover{transform:translateY(-6px) scale(1.03);box-shadow:0 0 28px rgba(212,163,115,0.35);}
.card i{font-size:38px;margin-bottom:12px;color:var(--brand-accent);}
.card h3{margin-bottom:4px;}
.card p{font-size:20px;font-weight:bold;}

/* Input, textarea, select styles */
form input, form textarea, form select {
    width:100%;
    padding:10px;
    margin:8px 0;
    border-radius:8px;
    border:none;
    background: rgba(255,255,255,0.08);
    color:#fff;
    font-size:15px;
}

/* Placeholder color */
form input::placeholder,
form textarea::placeholder {
    color: rgba(255,255,255,0.6);
}

/* Select dropdown */
form select {
    color:#fff;
    background: rgba(255,255,255,0.08);
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

form select option {
    color: #000;
    background: #f1f1f1;
}

form button {
    background: var(--brand-accent);
    padding: 10px 14px;
    border:none;
    border-radius:8px;
    color:#fff;
    font-weight:600;
    margin-top:10px;
    cursor:pointer;
    transition:0.3s;
}

form button:hover {
    background: var(--brand-hover);
}

footer{overflow:hidden;margin-top:40px;text-align:center;font-size:14px;color:#aaa;padding-bottom:30px;}
footer span{color:var(--brand-accent);font-weight:500;}

@media(max-width:1024px){.main-content{margin-left:0;}.sidebar{transform:translateX(-100%);}.sidebar.active{transform:translateX(0);}.sidebar-toggle{display:block;}}
@media(max-width:768px){.main-content{padding:24px;}.topbar{flex-direction:column;align-items:flex-start;}.admin-info{flex-direction:column;align-items:flex-start;gap:6px;}.cards{grid-template-columns:1fr;}}
</style>
</head>
<body>

<button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
<div class="sidebar">
  <div class="logo"><img src="decorevistaimages/logo.png" alt="Logo"></div>
  <nav>
    <a href="designer-dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="appointments.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
    <!-- <a href="add-designs.php"><i class="fa-solid fa-pen-to-square"></i> Add Designs</a> -->
    <a href="reviews.php"><i class="fa-solid fa-star"></i> Reviews</a>
    <a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a>
    <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
  </nav>
</div>

<div class="main-content">
  <div class="topbar">
    <h1>Designer Dashboard</h1>
    <div class="admin-info">
      <i class="fa-solid fa-user-shield"></i>
      <span><?= htmlspecialchars($email) ?></span>
      <span id="datetime"></span>
    </div>
  </div>

<?php if (!$profile): ?>
  <!-- First-time profile submission -->
  <div class="cards">
    <div class="card" style="grid-column:1/-1;">
      <h3>Submit Your Designer Profile</h3>
      <?php if($errorMsg) echo "<p style='color:red;'>$errorMsg</p>"; ?>
      <?php if($successMsg) echo "<p style='color:lime;'>$successMsg</p>"; ?>
      <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Full Name" required>
        <textarea name="bio" placeholder="Short Bio" rows="3" required></textarea>
        <input type="text" name="expertise" placeholder="Expertise" required>
        <select name="day" required>
          <option value="">Select Available Day</option>
          <option value="Monday">Monday</option>
          <option value="Tuesday">Tuesday</option>
          <option value="Wednesday">Wednesday</option>
          <option value="Thursday">Thursday</option>
          <option value="Friday">Friday</option>
        </select>
        <input type="time" name="from_time" required>
        <input type="time" name="to_time" required>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="submit_request">Submit Profile</button>
      </form>
    </div>
  </div>

<?php elseif($profile['status'] === 'Pending'): ?>
  <!-- Waiting approval -->
  <div class="cards">
    <div class="card" style="grid-column:1/-1; color:yellow;">
      <i class="fa-solid fa-hourglass-half"></i>
      <h3>Your request is pending admin approval!</h3>
      <p>Please wait until admin approves your profile.</p>
    </div>
  </div>

<?php elseif($profile['status'] === 'Approved'): ?>
  <!-- Full Dashboard Cards -->
  <div class="cards">
    <div class="card"><i class="fa-solid fa-calendar-check"></i><h3>Appointments</h3><a href="appointments.php"></a><p>View all appointments</p></div>
    <div class="card"><i class="fa-solid fa-pen-to-square"></i><h3>Designs</h3><a href="add-design.php"></a><p>Add or manage designs</p></div>
    <div class="card"><i class="fa-solid fa-star"></i><h3>Reviews</h3><a href="reviews.php"></a><p>Check reviews</p></div>
    <div class="card"><i class="fa-solid fa-user"></i><h3>Profile</h3><a href="profile.php"></a><p>Update your profile</p></div>
  </div>
<?php endif; ?>

<footer>
  &copy; <?= date("Y") ?> <span>DECORVISTA</span> | Designer Panel
</footer>
</div>

<script>
function toggleSidebar() {document.querySelector('.sidebar').classList.toggle('active');}
function updateDateTime() {
  const now = new Date();
  document.getElementById('datetime').textContent = now.toLocaleString('en-GB', {day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
setInterval(updateDateTime, 1000);
updateDateTime();
</script>
</body>
</html>
