<?php
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Approval Pending | ELEGANCE SALONE</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    :root {
      --brand-accent: #d4a373; /* luxury gold */
      --brand-accent-hover: #b89560;
      --text-light: #fff;
      --glass-bg: rgba(255, 255, 255, 0.06);
      --glass-glow: rgba(255, 255, 255, 0.12);
    }

    * { 
      margin: 0; 
      padding: 0; 
      box-sizing: border-box; 
      font-family: 'Poppins', sans-serif; 
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
      text-align: center;
      transition: 0.3s ease;
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

    .form-wrapper i {
      font-size: 48px;
      color: var(--brand-accent);
      margin-bottom: 15px;
      text-shadow: 0 0 8px var(--brand-accent);
      animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
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
      text-decoration: none;
    }

    .btn:hover { 
      background: var(--brand-accent-hover); 
      box-shadow: 0 0 14px var(--brand-accent); 
      transform: scale(1.04);
    }

    @media (max-width: 480px) { 
      .form-wrapper { padding: 35px 25px; } 
    }
  </style>
</head>
<body>
  <div class="form-wrapper">
    <i class="fa fa-clock"></i>
    <h2>Approval Pending</h2>
    <p class="subtitle">
      Hello <b><?= $email ?></b>, your designer account is awaiting admin approval.<br>
      You will be able to log in once approved.
    </p>
    <a href="login.php" class="btn"><i class="fa fa-arrow-left"></i> Back to Login</a>
  </div>
</body>
</html>
