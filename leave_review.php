<?php
session_start();
include 'db.php';

// Check login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

// get logged-in user's id (reviewer)
$userQuery = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
$userQuery->bind_param("s", $email);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userRow = $userResult->fetch_assoc();
if (!$userRow) {
    die("User not found.");
}
$reviewer_id = (int)$userRow['id'];

// read GET params
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$raw_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!in_array($type, ['employee','designer']) || $raw_id <= 0) {
    die("Invalid target.");
}

// Resolve target_id -> make sure it becomes the users.id of the designer (or employee)
$target_user_id = 0;

if ($type === 'designer') {
    // First check: maybe $raw_id is already a users.id
    $chk = $conn->prepare("SELECT id FROM users WHERE id = ? AND role = 'designer' LIMIT 1");
    $chk->bind_param("i", $raw_id);
    $chk->execute();
    if ($chk->get_result()->num_rows === 1) {
        $target_user_id = $raw_id; // it's users.id
    } else {
        // Otherwise maybe it's designer_profiles.id -> fetch user_id
        $chk2 = $conn->prepare("SELECT user_id FROM designer_profiles WHERE id = ? LIMIT 1");
        $chk2->bind_param("i", $raw_id);
        $chk2->execute();
        $r2 = $chk2->get_result()->fetch_assoc();
        if ($r2 && !empty($r2['user_id'])) {
            $target_user_id = (int)$r2['user_id'];
        }
    }
} else { // employee - adapt as needed; try users table first, then employee_profiles if you have
    $chk = $conn->prepare("SELECT id FROM users WHERE id = ? LIMIT 1");
    $chk->bind_param("i", $raw_id);
    $chk->execute();
    if ($chk->get_result()->num_rows === 1) {
        $target_user_id = $raw_id;
    } else {
        // fallback: if you have employee_profiles table, map it:
        $chk2 = $conn->prepare("SELECT user_id FROM employee_profiles WHERE id = ? LIMIT 1");
        $chk2->bind_param("i", $raw_id);
        $chk2->execute();
        $r2 = $chk2->get_result()->fetch_assoc();
        if ($r2 && !empty($r2['user_id'])) {
            $target_user_id = (int)$r2['user_id'];
        }
    }
}

// If still not resolved, show error
if ($target_user_id <= 0) {
    die("Could not resolve the target user for review. (ID mapping mismatch)");
}

// Handle POST
$successMsg = $errorMsg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rating  = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($rating < 1 || $rating > 5 || empty($comment)) {
        $errorMsg = "Please provide a rating (1–5) and a comment.";
    } else {
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, target_type, target_id, rating, comment, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        // ensure target_type saved as lowercase 'designer' or 'employee'
        $target_type_db = strtolower($type);
        $stmt->bind_param("isiis", $reviewer_id, $target_type_db, $target_user_id, $rating, $comment);

        if ($stmt->execute()) {
            $successMsg = "Review submitted — thank you!";
            // optional: redirect the reviewer to the designer's public page
            // header("Location: designer_public.php?id={$target_user_id}");
            // exit;
        } else {
            $errorMsg = "Error: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Leave a Review</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<style>
  body{font-family:'Poppins',sans-serif;background:#000;color:#fff;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px;}
  .box{background:rgba(255,255,255,0.05);padding:28px;border-radius:14px;width:100%;max-width:520px;box-shadow:0 8px 24px rgba(0,0,0,0.6);}
  h2{color:#d4a373;text-align:center;margin-bottom:14px}
  label{display:block;margin-top:12px;font-weight:600}
  select, textarea, input{width:100%;padding:12px;border-radius:8px;border:none;background:rgba(255,255,255,0.06);color:#fff;margin-top:6px;font-size:14px;outline:none}
  select option{background:#111;color:#fff}
  textarea{min-height:120px;resize:vertical}
  .hint{font-size:13px;color:rgba(255,255,255,0.6);margin-top:6px}
  button{margin-top:16px;width:100%;padding:12px;border-radius:10px;border:none;background:#d4a373;color:#000;font-weight:700;cursor:pointer}
  .msg{margin-top:10px;text-align:center}
  .success{color:#2ecc71}
  .error{color:#ff6b6b}
</style>
</head>
<body>
  <div class="box">
    <h2>Leave a Review</h2>
    <?php if ($successMsg): ?><p class="msg success"><?=htmlspecialchars($successMsg)?></p><?php endif; ?>
    <?php if ($errorMsg): ?><p class="msg error"><?=htmlspecialchars($errorMsg)?></p><?php endif; ?>

    <form method="POST" novalidate>
      <label for="rating">Rating</label>
      <select id="rating" name="rating" required>
        <option value="" disabled selected>⭐ Choose rating</option>
        <option value="5">5 — Excellent</option>
        <option value="4">4 — Very good</option>
        <option value="3">3 — Good</option>
        <option value="2">2 — Fair</option>
        <option value="1">1 — Poor</option>
      </select>

      <label for="comment">Your Review</label>
      <textarea id="comment" name="comment" placeholder="Write your feedback here..." required></textarea>

      <button type="submit">Submit Review</button>
    </form>
  </div>
</body>
</html>
