<?php
session_start();
session_unset();  // Clear session data
session_destroy(); // Destroy session
header("Location: login2.php"); // Redirect to login page after logout
exit;
?>
