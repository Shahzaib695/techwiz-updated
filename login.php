<?php
session_start();
require "db.php";

// ✅ Validation functions
function valid_email($e) {
    return filter_var($e, FILTER_VALIDATE_EMAIL);
}

function valid_pass($p) {
    return preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/', $p);
}

$emailError = $passwordError = "";
$invalidLogin = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $isValid = true;

    // ✅ Validate email
    if (!valid_email($email)) {
        $emailError = "Invalid email address.";
        $isValid = false;
    }

    // ✅ Validate password
    if (!valid_pass($pass)) {
        $passwordError = "Password must be 8+ chars, with 1 capital letter & 1 number.";
        $isValid = false;
    }

    if ($isValid) {
        // ✅ Admin login (hardcoded)
        if ($email === "admin@admin.com" && $pass === "Admin123") {
            $_SESSION['email'] = $email;
            $_SESSION['role'] = "admin";
            $_SESSION['time'] = time();
            echo "<script>alert('Welcome $email'); window.location.href='admin.php';</script>";
            exit();
        }

        // ✅ User/Designer login
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($pass, $row['password'])) {

                // ⚡ Designer not approved check
                if ($row['role'] === 'designer' && $row['is_approved'] == 0) {
                    header("Location: pending.php?email=" . urlencode($email));
                    exit();
                }

                // ✅ Set session
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $row['role'];
                $_SESSION['time'] = time();

                // ✅ Redirect based on role
                if ($row['role'] === 'designer') {
                    header("Location: designer-dashboard.php");
                    exit();
                } elseif ($row['role'] === 'user') {
                    header("Location: home.php");
                    exit();
                } else {
                    // fallback
                    header("Location: home.php");
                    exit();
                }
            }
        }

        // ❌ Invalid login
        $invalidLogin = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | DECORVISTA</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    :root {
  --brand-accent: #d4a373; /* DecorVista gold */
  --brand-accent-hover: #b89560;
  --text-light: #fff;
  --glass-bg: rgba(255, 255, 255, 0.06);
  --glass-glow: rgba(255, 255, 255, 0.12);
}

* { 
  margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; 
}

body {
  background: url('decorevistaimages/214ae3e55fb608d768b3bc455ddcf18a.jpg') no-repeat center/cover;
  min-height: 100vh; 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  padding: 20px;
}

.form-wrapper {
  width: 100%; 
  max-width: 400px; 
  background: var(--glass-bg); 
  backdrop-filter: blur(24px);
  border-radius: 22px; 
  padding: 45px 35px; 
  color: var(--text-light);
  box-shadow: 0 16px 55px rgba(0,0,0,0.45); 
  border: 1px solid rgba(255,255,255,0.12); 
  animation: fadeUp 0.8s ease;
  transition: 0.3s;
}

@keyframes fadeUp { 
  0% {opacity:0; transform:translateY(60px);} 
  100% {opacity:1; transform:translateY(0);} 
}

h2 {
  font-size: 30px; 
  text-align: center; 
  margin-bottom: 12px; 
  color: var(--brand-accent);
  text-shadow: 0 0 8px var(--brand-accent);
}

.subtitle { 
  text-align: center; 
  font-size: 13px; 
  color: #ddd; 
  margin-bottom: 28px; 
}

.form-group { 
  margin-bottom: 24px; 
  position: relative; 
}

.input-wrap {
  background: var(--glass-glow); 
  border-radius: 14px; 
  display: flex; 
  align-items: center;
  padding: 18px 14px 6px; 
  position: relative;
  transition: 0.3s ease;
}

.input-wrap i { 
  margin-right: 10px; 
  color: #eee; 
  font-size: 14px; 
}

.input-wrap input {
  background: transparent; 
  border: none; 
  outline: none; 
  flex: 1; 
  font-size: 14px; 
  padding: 8px 0 5px; 
  color: white;
  transition: 0.3s;
}

.input-wrap input::placeholder { 
  color: transparent; 
}

.input-wrap label {
  position: absolute; 
  left: 44px; 
  top: 15px; 
  color: #ccc; 
  font-size: 13px;
  pointer-events: none; 
  transition: 0.3s;
}

.input-wrap input:focus + label,
.input-wrap input:not(:placeholder-shown) + label {
  top: -8px; 
  left: 38px; 
  font-size: 10px;
  background: rgba(0,0,0,0.4); 
  padding: 0 6px; 
  border-radius: 4px; 
  color: var(--brand-accent);
}

.btn {
  width: 100%; 
  padding: 14px; 
  background: var(--brand-accent); 
  border: none;
  border-radius: 12px; 
  font-size: 15px; 
  font-weight: 600; 
  color: white;
  cursor: pointer; 
  display: flex; 
  justify-content: center; 
  align-items: center; 
  gap: 8px; 
  transition: 0.3s ease, transform 0.2s ease; 
  margin-top: 8px;
}

.btn:hover { 
  background: var(--brand-accent-hover); 
  box-shadow: 0 0 14px var(--brand-accent); 
  transform: scale(1.04);
}

.bottom-text {
  text-align: center; 
  margin-top: 20px; 
  font-size: 12px; 
  color: #ccc;
}

.bottom-text a {
  font-weight: bold; 
  color: white; 
  text-decoration: none;
  transition: 0.3s;
}

.bottom-text a:hover { 
  text-decoration: underline; 
  color: var(--brand-accent);
}

.error {
  color: #fc5757; 
  font-size: 12px; 
  margin-top: 6px; 
  padding-left: 4px;
  animation: fadeIn 0.3s ease-in-out; 
  text-shadow: 0 0 6px rgba(255, 87, 87, 0.6);
}

@keyframes fadeIn { 
  from { opacity: 0; transform: translateY(-5px); } 
  to { opacity: 1; transform: translateY(0); } 
}

@media (max-width: 480px) { 
  .form-wrapper { padding: 35px 25px; } 
}

  </style>
</head>
<body>
  <form class="form-wrapper" method="POST" autocomplete="off" novalidate>
    <h2>Sign In</h2>
    <div class="subtitle">Welcome back to ELEGANCE SALONE</div>

    <?php if ($invalidLogin): ?>
      <div class="error" style="text-align:center;">Invalid credentials. Please try again.</div>
    <?php endif; ?>

    <div class="form-group">
      <div class="input-wrap">
        <i class="fa fa-envelope"></i>
        <input type="email" name="email" placeholder=" " required />
        <label for="email">Email</label>
      </div>
      <?php if ($emailError): ?>
        <div class="error"><?= $emailError ?></div><?php endif; ?>
    </div>

    <div class="form-group">
      <div class="input-wrap">
        <i class="fa fa-lock"></i>
        <input type="password" name="password" placeholder=" " required />
        <label for="password">Password</label>
      </div>
      <?php if ($passwordError): ?>
        <div class="error"><?= $passwordError ?></div><?php endif; ?>
    </div>

    <button type="submit" class="btn" name="login">SIGN IN <i class="fa fa-arrow-right"></i></button>
    <div class="bottom-text">Don’t have an account? <a href="signup.php">SIGN UP</a></div>
  </form>
</body>
</html>
