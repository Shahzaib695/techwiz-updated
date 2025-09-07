<?php
$conn = new mysqli("localhost", "root", "", "users2");
$result = $conn->query("SELECT * FROM testimonials ORDER BY created_at DESC");

$testimonials = [];
while($row = $result->fetch_assoc()) {
  $testimonials[] = [
    'name' => $row['name'],
    'comment' => $row['comment'],
    'rating' => $row['rating']
  ];
}

header('Content-Type: application/json');
echo json_encode($testimonials);
?>
