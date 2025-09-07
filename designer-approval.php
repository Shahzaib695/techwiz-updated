<?php
session_start();
include 'db.php';

// ✅ Hardcoded admin check
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

// ✅ Approve designer
if (isset($_GET['approve'])) {
    $id = (int) $_GET['approve'];
    $update = mysqli_query($conn, "UPDATE users SET is_approved=1 WHERE id=$id AND role='designer'");
    if ($update) {
        echo "<script>alert('Designer approved successfully!'); window.location.href='designer-approval.php';</script>";
        exit();
    }
}
// ✅ Fetch pending designers
$pendingDesigners = mysqli_query($conn, "SELECT id, name, email, created_at FROM users WHERE role='designer' AND is_approved=0");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Approve Designers | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="designer.css">
</head>

<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="elegance salon images/logo.jpg" alt="Logo" />
        </div>
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
            <a href="appointments_view.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
            <a href="admin_add_pakages.php"><i class="fa-solid fa-box-open"></i> Add Packages</a>
            <a href="pakages_view.php"><i class="fa-solid fa-boxes-stacked"></i> View Packages</a>
            <a href="pakages_order_view.php"><i class="fa-solid fa-truck-arrow-right"></i> Packages Order</a>
            <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h1>Designer Approvals</h1>
            <div class="admin-info">
                <i class="fa-solid fa-user-shield"></i>
                <span><?= htmlspecialchars($_SESSION['email']) ?></span>
            </div>
        </div>

        <div class="table-container">
            <?php if (mysqli_num_rows($pendingDesigners) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Signup Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($pendingDesigners)): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= $row['created_at'] ?></td>
                                <td>
                                    <a class="btn btn-approve" href="designer-approval.php?approve=<?= $row['id'] ?>">
                                        <i class="fa fa-check"></i> Approve
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data"><i class="fa fa-info-circle"></i> No pending designers right now.</div>
            <?php endif; ?>
        </div>

        <footer>
            &copy; <?= date("Y") ?> <span>ELEGANCE SALONE</span> | Admin Panel
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
</body>

</html>