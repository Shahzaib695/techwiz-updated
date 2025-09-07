<?php
session_start();
include 'db.php';

$result = mysqli_query($conn, "SELECT * FROM appointments ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
  <title>View Appointments</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f7f7f7;
      margin: 0;
      padding: 20px;
    }
    h2 {
      color: #6c5ce7;
      text-align: center;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      background: white;
      border-collapse: collapse;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #eee;
    }
    th {
      background-color: #6c5ce7;
      color: white;
    }
    .action-btns button {
      margin: 0 5px;
      padding: 5px 10px;
      border: none;
      color: white;
      border-radius: 4px;
      cursor: pointer;
    }
    .approve { background: #2ecc71; }
    .reject { background: #e74c3c; }
  </style>
</head>
<body>

  <h2><i class="fas fa-calendar-check"></i> All Appointments</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Staff</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Service</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['staff']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['service']) ?></td>
        <td><?= htmlspecialchars($row['date']) ?></td>
        <td><?= htmlspecialchars($row['time']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td class="action-btns">
          <form method="POST" action="appointment_action.php" style="display:inline;">
            <input type="hidden" name="id" value="<?= $row['id'] ?>" />
            <button class="approve" name="approve">Approve</button>
            <button class="reject" name="reject">Reject</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</body>
</html>
