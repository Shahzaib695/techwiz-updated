<?php
session_start();
include 'db.php';

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

// ✅ Handle approval/rejection
if (isset($_POST['action']) && isset($_POST['designer_id'])) {
    $designerId = intval($_POST['designer_id']);
    if ($_POST['action'] === 'approve') {
        $conn->query("UPDATE users SET is_approved = 1 WHERE id = $designerId AND role='designer'");
    } elseif ($_POST['action'] === 'reject') {
        $conn->query("UPDATE users SET is_approved = 0 WHERE id = $designerId AND role='designer'");
    }
}

// ✅ Fetch designers
$designers = $conn->query("
    SELECT u.id, u.name, u.email, u.is_approved, d.bio, d.expertise, d.image
    FROM users u
    LEFT JOIN designer_profiles d ON u.id = d.user_id
    WHERE u.role='designer'
    ORDER BY u.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Designer Approvals | DECORVISTA</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --brand-accent: #d4a373;
      --brand-hover: #b89560;
      --text-light: #f1f1f1;
      --bg-dark: #0f0f0f;
      --card-bg: rgba(255, 255, 255, 0.06);
      --sidebar-width: 260px;
      --shadow-glow: 0 0 16px var(--brand-accent);
      --glass-blur: blur(16px);
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Poppins', sans-serif; background: var(--bg-dark); color: var(--text-light); }
    .sidebar {
      width: var(--sidebar-width);
      background: linear-gradient(180deg, rgba(212,163,115,0.1), rgba(0,0,0,0.7)),
                  url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
      padding: 22px;
      position: fixed; height: 100vh;
      border-right: 1px solid rgba(255,255,255,0.08);
      backdrop-filter: var(--glass-blur);
      z-index: 999;
      border-radius: 0 20px 20px 0;
    }
    .sidebar .logo { text-align: center; margin-bottom: 35px; }
    .sidebar .logo img { width: 130px; border-radius: 14px; border: 2px solid var(--brand-accent); box-shadow: 0 0 22px rgba(212,163,115,0.4); }
    .sidebar nav a {
      display: flex; align-items: center; color: #fff; text-decoration: none;
      padding: 14px 16px; margin: 10px 0; border-radius: 14px;
      font-weight: 500; font-size: 16px; gap: 12px;
      transition: 0.35s ease, transform 0.2s ease;
    }
    .sidebar nav a:hover {
      background: rgba(0, 0, 0, 0.5);
      color: var(--brand-accent);
      box-shadow: var(--shadow-glow);
      transform: translateX(8px);
    }
    .main-content {
      margin-left: var(--sidebar-width);
      padding: 40px;
      background: url('decorevistaimages/aaca7cec36ff5dd44456a145fc7a6b70.jpg') center/cover no-repeat;
      backdrop-filter: blur(5px);
      min-height: 100vh;
      position: relative;
    }
    .main-content::before {
      content: ''; position: absolute; inset: 0; background: rgba(0,0,0,0.55); z-index: 0;
    }
    .main-content > * { position: relative; z-index: 1; }
    .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .topbar h1 { font-size: 34px; color: var(--brand-accent); text-shadow: 0 0 12px var(--brand-accent); }
    .designer-table {
      width: 100%; border-collapse: collapse; margin-top: 20px;
      background: var(--card-bg); border-radius: 14px; overflow: hidden;
    }
    .designer-table th, .designer-table td {
      padding: 14px; border-bottom: 1px solid rgba(255,255,255,0.08); text-align: left;
    }
    .designer-table th { background: rgba(0,0,0,0.4); color: var(--brand-accent); }
    .designer-table td img {
      width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid var(--brand-accent);
    }
    .actions form { display: inline; }
    .btn {
      padding: 8px 14px; border: none; border-radius: 8px; cursor: pointer;
      font-weight: 600; transition: 0.3s ease;
    }
    .btn-approve { background: #27ae60; color: #fff; }
    .btn-reject { background: #c0392b; color: #fff; }
    .btn:hover { opacity: 0.85; transform: scale(1.05); }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="logo"><img src="decorevistaimages/logo.png" alt="Logo"></div>
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
            <a href="admin-designer-approval.php" style="background:rgba(255,255,255,0.15);"><i
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
      <h1>Designer Approvals</h1>
      <div class="admin-info">
        <i class="fa-solid fa-user-shield"></i>
        <span><?= htmlspecialchars($_SESSION['email']) ?></span>
      </div>
    </div>

    <table class="designer-table">
      <thead>
        <tr>
          <th>Picture</th>
          <th>Name</th>
          <th>Email</th>
          <th>Bio</th>
          <th>Expertise</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($designer = $designers->fetch_assoc()): ?>
        <tr>
          <td>
            <?php if ($designer['image']): ?>
              <img src="<?= htmlspecialchars($designer['image']) ?>" alt="Designer">
            <?php else: ?>
              <img src="default-profile.png" alt="Default">
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($designer['name']) ?></td>
          <td><?= htmlspecialchars($designer['email']) ?></td>
          <td><?= htmlspecialchars($designer['bio'] ?? '—') ?></td>
          <td><?= htmlspecialchars($designer['expertise'] ?? '—') ?></td>
          <td><?= $designer['is_approved'] ? '✅ Approved' : '⏳ Pending' ?></td>
          <td class="actions">
            <form method="post" style="display:inline;">
              <input type="hidden" name="designer_id" value="<?= $designer['id'] ?>">
              <button type="submit" name="action" value="approve" class="btn btn-approve">Approve</button>
            </form>
            <form method="post" style="display:inline;">
              <input type="hidden" name="designer_id" value="<?= $designer['id'] ?>">
              <button type="submit" name="action" value="reject" class="btn btn-reject">Reject</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
