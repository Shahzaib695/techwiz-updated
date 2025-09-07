<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.com') {
    header("Location: login.php");
    exit;
}

// Handle form actions
$success = '';
$error = '';
$editing = false;
$edit_package = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST['package_id']) ? intval($_POST['package_id']) : 0;
    $name = mysqli_real_escape_string($conn, $_POST['package_name']);
    $description = mysqli_real_escape_string($conn, $_POST['package_description']);
    $price = floatval($_POST['package_price']);
    $qty = intval($_POST['package_qty']);
    $image_path = '';

    if (!empty($_FILES['package_image']['name'])) {
        $image_name = basename($_FILES['package_image']['name']);
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir);
        $image_path = $target_dir . time() . '_' . $image_name;
        move_uploaded_file($_FILES['package_image']['tmp_name'], $image_path);
    }

    if ($id > 0) {
        $update_query = $image_path
            ? "UPDATE packages SET name='$name', description='$description', price=$price, qty=$qty, image='$image_path' WHERE id=$id"
            : "UPDATE packages SET name='$name', description='$description', price=$price, qty=$qty WHERE id=$id";
        mysqli_query($conn, $update_query);
        $success = "Package updated successfully!";
    } else {
        if ($image_path) {
            $query = "INSERT INTO packages (name, description, price, qty, image) VALUES ('$name', '$description', $price, $qty, '$image_path')";
            mysqli_query($conn, $query);
            $success = "Package added successfully!";
        } else {
            $error = "Please upload a package image.";
        }
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM packages WHERE id=$id");
    $success = "Package deleted!";
}

if (isset($_GET['edit'])) {
    $editing = true;
    $edit_id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM packages WHERE id=$edit_id");
    if (mysqli_num_rows($result) > 0) {
        $edit_package = mysqli_fetch_assoc($result);
    }
}

// Pagination + Search
$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where = $search ? "WHERE name LIKE '%$search%'" : '';

$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM packages $where");
$total = mysqli_fetch_assoc($total_query)['total'];
$pages = ceil($total / $limit);
$result = mysqli_query($conn, "SELECT * FROM packages $where ORDER BY id DESC LIMIT $start, $limit");
?>
