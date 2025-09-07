<?php
session_start();
include "db.php";

$user_email = $_SESSION['email'];

$query = "SELECT * FROM orders WHERE email = '$user_email' ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);
?>

<h2>Your Orders</h2>
<table border="1" cellpadding="12" cellspacing="0">
  <tr>
    <th>Image</th>
    <th>Product</th>
    <th>Quantity</th>
    <th>Payment</th>
    <th>Date</th>
    <th>Status</th>
  </tr>
  <?php while($row = mysqli_fetch_assoc($result)): ?>
  <tr>
    <td><img src="<?= $row['product_image'] ?>" width="60"></td>
    <td><?= $row['product_name'] ?></td>
    <td><?= $row['quantity'] ?></td>
    <td><?= $row['payment_method'] ?></td>
    <td><?= $row['order_date'] ?></td>
    <td><?= $row['status'] ?></td>
  </tr>
  <?php endwhile; ?>
</table>
