<?php
$conn = new mysqli("localhost", "root", "", "users2");

$name = htmlspecialchars($_POST['name']);
$comment = htmlspecialchars($_POST['comment']);
$rating = intval($_POST['rating']);

$conn->query("INSERT INTO testimonials (name, comment, rating) VALUES ('$name', '$comment', $rating)");
?>
