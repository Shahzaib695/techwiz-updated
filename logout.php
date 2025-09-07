<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php?logout=1");
exit;
?>
<link rel="stylesheet" href="style.css">
