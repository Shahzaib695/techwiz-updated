<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

// Fetch logged in user
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

$successMsg = $errorMsg = "";

// Update profile
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['update_profile']) && $profile && $profile['status'] === 'Approved') {
    $name      = trim($_POST['name']);
    $bio       = trim($_POST['bio']);
    $expertise = trim($_POST['expertise']);
    $day       = $_POST['day'] ?? "";
    $from      = $_POST['from_time'] ?? "";
    $to        = $_POST['to_time'] ?? "";

    if (empty($name) || empty($bio) || empty($expertise) || empty($day) || empty($from) || empty($to)) {
        $errorMsg = "⚠️ Please fill all fields.";
    } else {
        $imagePath = $profile['image']; // keep old image if not updated
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "uploads/designers/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

            $filename = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $target_dir . $filename;
            if (move_uploaded_file($_FILES["image"]['tmp_name'], $targetFile)) {
                $imagePath = $targetFile;
            }
        }

        $stmt = $conn->prepare(
            "UPDATE designer_profiles SET name=?, bio=?, expertise=?, image=?, day=?, from_time=?, to_time=? WHERE user_id=?"
        );
        $stmt->bind_param("sssssssi", $name, $bio, $expertise, $imagePath, $day, $from, $to, $user['id']);
        $stmt->execute();
        $successMsg = "✅ Profile updated successfully!";

        // Refresh profile data
        $profileQuery->execute();
        $profileResult = $profileQuery->get_result();
        $profile = $profileResult->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Designer Profile | DECORVISTA</title>
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
.sidebar{width:var(--sidebar-width);background:linear-gradient(180deg, rgba(212,163,115,0.1), rgba(0,0,0,0.7)), url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;position:fixed;height:100vh;padding:20px;border-right:1px solid rgba(255,255,255,0.08);backdrop-filter:var(--glass-blur);border-radius:0 20px 20px 0;}
.sidebar .logo{text-align:center;margin-bottom:30px;}
.sidebar .logo img{width:130px;border-radius:14px;border:2px solid var(--brand-accent);box-shadow:0 0 22px rgba(212,163,115,0.4);}
.sidebar nav a{display:flex;align-items:center;color:#fff;text-decoration:none;padding:12px 16px;margin:8px 0;border-radius:14px;font-weight:500;font-size:16px;gap:12px;transition:0.35s ease, transform 0.2s ease;}
.sidebar nav a:hover{background:rgba(0,0,0,0.5);color:var(--brand-accent);box-shadow:var(--shadow-glow);transform:translateX(8px);}
.sidebar-toggle{display:none;position:fixed;top:18px;left:18px;background:var(--brand-accent);color:#fff;border:none;padding:10px 14px;border-radius:8px;font-size:20px;cursor:pointer;z-index:1100;}
.main-content{margin-left:var(--sidebar-width);padding:40px;position:relative;min-height:100vh;background: url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;}
.main-content::before{content:'';position:absolute;inset:0;background:rgba(0,0,0,0.55);z-index:0;}
.main-content > *{position:relative;z-index:1;}
.topbar{margin-top:30px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,0.12);padding-bottom:20px;flex-wrap:wrap;gap:12px;}
.topbar h1{font-size:34px;color:var(--brand-accent);text-shadow:0 0 12px var(--brand-accent);}
.designer-info{display:flex;align-items:center;gap:18px;font-size:15px;color:var(--text-light);}
.designer-info i{color:var(--brand-accent);}
.card{background:var(--card-bg);padding:26px;border-radius:18px;margin-top:40px;box-shadow:0 0 18px rgba(212,163,115,0.2);}
.card h3{margin-bottom:20px;color:var(--brand-accent);}
.card form input,.card form textarea,.card form select{width:100%;padding:12px;margin:8px 0;border-radius:8px;border:none;background:rgba(255,255,255,0.08);color:#fff;}
.card form input::placeholder,.card form textarea::placeholder{color:#ccc;}
.card form button{background:var(--brand-accent);padding:12px;border:none;border-radius:8px;color:#fff;font-weight:600;margin-top:10px;cursor:pointer;transition:0.3s;}
.card form button:hover{background:var(--brand-hover);}
.success{margin:15px 0;padding:12px;background:#00b894;border-radius:8px;}
.error{margin:15px 0;padding:12px;background:#d63031;border-radius:8px;}
footer{margin-top:40px;text-align:center;font-size:14px;color:#aaa;padding-bottom:30px;}
footer span{color:var(--brand-accent);font-weight:500;}
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
    <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
  </nav>
</div>

<div class="main-content">
  <div class="topbar">
    <h1>My Profile</h1>
    <div class="designer-info"><i class="fa-solid fa-user"></i> <?= htmlspecialchars($user['name']) ?></div>
  </div>

  <?php if($errorMsg): ?><div class="error"><?= $errorMsg ?></div><?php endif; ?>
  <?php if($successMsg): ?><div class="success"><?= $successMsg ?></div><?php endif; ?>

  <?php if(!$profile): ?>
    <div class="card"><h3>No profile found!</h3><p>Please submit your details from dashboard first.</p></div>

  <?php elseif($profile['status'] === 'Pending'): ?>
    <div class="card"><h3><i class="fa-solid fa-hourglass-half"></i> Waiting for Admin Approval</h3><p>Please wait until your profile is approved.</p></div>

  <?php else: ?>
    <div class="card">
      <h3>Update Profile</h3>
      <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" value="<?= htmlspecialchars($profile['name']) ?>" placeholder="Full Name" required>
        <textarea name="bio" rows="3" placeholder="Short Bio" required><?= htmlspecialchars($profile['bio']) ?></textarea>
        <input type="text" name="expertise" value="<?= htmlspecialchars($profile['expertise']) ?>" placeholder="Expertise" required>
        <select name="day" required>
          <?php
          $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
          foreach($days as $d){
              $sel = $profile['day'] === $d ? 'selected' : '';
              echo "<option value='$d' $sel>$d</option>";
          }
          ?>
        </select>
        <input type="time" name="from_time" value="<?= htmlspecialchars($profile['from_time']) ?>" required>
        <input type="time" name="to_time" value="<?= htmlspecialchars($profile['to_time']) ?>" required>
        <input type="file" name="image" accept="image/*">
        <?php if($profile['image']): ?><img src="<?= $profile['image'] ?>" alt="Profile Image" style="max-width:120px;border-radius:8px;margin-top:10px;"><?php endif; ?>
        <button type="submit" name="update_profile">Update Profile</button>
      </form>
    </div>
  <?php endif; ?>

  <footer>
    &copy; <?= date("Y") ?> <span>DECORVISTA</span> | Designer Panel
  </footer>
</div>

<script>
function toggleSidebar(){document.querySelector('.sidebar').classList.toggle('active');}
</script>
</body>
</html>
