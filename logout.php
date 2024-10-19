<?php
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page with a message
header("Location: login.php?logout=success");
exit();
?>
