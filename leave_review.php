<?php
session_start();
include 'db.php';

// ✅ Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

// ✅ Get logged-in user ID
$userQuery = $conn->prepare("SELECT id FROM users WHERE email=?");
$userQuery->bind_param("s", $email);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userRow = $userResult->fetch_assoc();
if (!$userRow) {
    die("❌ User not found.");
}
$user_id = $userRow['id'];

// ✅ Get target (employee or designer)
$type = isset($_GET['type']) ? $_GET['type'] : '';
$id   = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!in_array($type, ['employee','designer']) || $id <= 0) {
    die("❌ Invalid review target.");
}

// ✅ Handle form submission
$successMsg = $errorMsg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rating  = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($rating < 1 || $rating > 5 || empty($comment)) {
        $errorMsg = "⚠️ Please provide a valid rating (1-5) and a comment.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO reviews (user_id, target_type, target_id, rating, comment, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())"
        );
        $stmt->bind_param("isiis", $user_id, $type, $id, $rating, $comment);

        if ($stmt->execute()) {
            $successMsg = "✅ Review submitted successfully!";
        } else {
            $errorMsg = "❌ Error submitting review: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leave a Review | DecorVista</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #000;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }
    .review-box {
      background: rgba(255,255,255,0.06);
      padding: 30px;
      border-radius: 18px;
      max-width: 500px;
      width: 100%;
      backdrop-filter: blur(16px);
      box-shadow: 0 0 18px rgba(212,163,115,0.3);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #d4a373;
      text-shadow: 0 0 8px #d4a373;
    }
    label {
      display: block;
      margin-top: 12px;
      font-weight: 600;
    }
    select, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border-radius: 8px;
      border: none;
      background: rgba(255,255,255,0.08);
      color: #fff;
    }
    textarea { resize: vertical; min-height: 80px; }
    button {
      margin-top: 18px;
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      font-size: 16px;
      background: #d4a373;
      color: #fff;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background: #b89560;
      box-shadow: 0 0 12px #d4a373;
    }
    .msg { text-align: center; margin-top: 12px; }
    .success { color: #2ecc71; }
    .error { color: #e74c3c; }
  </style>
</head>
<body>
  <div class="review-box">
    <h2>Leave a Review</h2>
    <?php if ($successMsg): ?>
      <p class="msg success"><?= $successMsg ?></p>
    <?php elseif ($errorMsg): ?>
      <p class="msg error"><?= $errorMsg ?></p>
    <?php endif; ?>
    
    <form method="POST">
      <label for="rating">Rating (1-5):</label>
      <select name="rating" id="rating" required>
        <option value="">--Select--</option>
        <?php for($i=1;$i<=5;$i++): ?>
          <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor; ?>
      </select>

      <label for="comment">Your Review:</label>
      <textarea name="comment" id="comment" required></textarea>

      <button type="submit">Submit Review</button>
    </form>
  </div>
</body>
</html>
